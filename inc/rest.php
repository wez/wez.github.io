<?php # vim:ts=2:sw=2:et:

if (php_sapi_name() == 'cli') {
  $REST_CACHE_TIME = 3600;
} else {
  $REST_CACHE_TIME = 7 * 86400;
}

/* multi-process consistent file-get-contents */
function wfo_file_get($path)
{
  $fp = fopen($path, 'rb');
  if (!$fp) {
    return false;
  }
  flock($fp, LOCK_SH);
  $res = stream_get_contents($fp);
  flock($fp, LOCK_UN);
  fclose($fp);
  return $res;
}

/* multi-process consistent file-put-contents */
function wfo_file_put($path, $content)
{
  $fp = @fopen($path, 'r+b');
  if (!$fp) {
    /* file doesn't exist; try to exclusively create */
    $fp = @fopen($path, 'xb');
    if (!$fp) {
      /* couldn't exclusively create; assume that someone
       * beat us to it and try to open an existing file */
      $fp = @fopen($path, 'r+b');
    }
  }
  if (!$fp) {
    return false;
  }
  flock($fp, LOCK_EX);
  /* truncate and populate only *after* we have the exclusive lock */
  ftruncate($fp, 0);
  fseek($fp, 0);

  for ($wrote = 0; $wrote < strlen($content); $wrote += $x) {
    $x = fwrite($fp, substr($content, $wrote));
    if ($x === false) {
      unlink($path);
      flock($fp, LOCK_UN);
      fclose($fp);
      return false;
    }
  }

  flock($fp, LOCK_UN);
  fclose($fp);
  return $wrote;
}

function wfo_rest($verb, $url, $params = null, $format = 'json')
{
  global $REST_CACHE_TIME;

  $cparams = array(
    'http' => array(
      'method' => $verb,
      'ignore_errors' => true
    )
  );
  if ($params !== null) {
    $params = http_build_query($params);
    if ($verb == 'POST') {
      $cparams['http']['content'] = $params;
    } else {
      $url .= '?' . $params;
    }
  }

  $res = null;

  if ($verb == 'GET') {
    // Make use of a cache
    $cache_path = '/tmp/.wfo/' . md5($url . ":$format");
    $now = time();
    if (file_exists($cache_path) &&
        (filemtime($cache_path) + $REST_CACHE_TIME) > $now) {
      $res = wfo_file_get($cache_path);
    }
  }

  if ($res === null) {
    $context = stream_context_create($cparams);
    $fp = fopen($url, 'rb', false, $context);
    if (!$fp) {
      $res = false;
    } else {
      $meta = stream_get_meta_data($fp);
//      var_dump($meta['wrapper_data']);
      $res = stream_get_contents($fp);
    }

    if ($res !== false && $cache_path !== null) {
      if (!is_dir('/tmp/.wfo')) {
        mkdir('/tmp/.wfo');
      }
      wfo_file_put($cache_path, $res);
    }

    // fall back to using the cached version if we can't make a request
    if ($res === false && $cache_path !== null && file_exists($cache_path)) {
      $res = wfo_file_get($cache_path);
    }

    if ($res === false) {
      throw new Exception("$verb $url failed: $php_errormsg");
    }
  }

  switch ($format) {
    case 'json':
      $r = json_decode($res);
      if ($r === null) {
        throw new Exception("failed to decode $res as json");
      }
      return $r;

    case 'xml':
      $r = simplexml_load_string($res);
      if ($r === null) {
        throw new Exception("failed to decode $res as xml");
      }
      return $r;
  }
  return $res;
}

