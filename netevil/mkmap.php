<?php # vim:ts=2:sw=2:et:
if (function_exists('date_default_timezone_set')) {
  date_default_timezone_set('UTC');
}

$map = array();

$names = array('nidmap', 'uuidmap', 'newuuid');

$art = json_decode(file_get_contents('../posts/articles.json'));

foreach ($names as $name) {
  foreach (file($name) as $line) {
    list($nid, $pretty) = explode(' ', trim($line));
    if (strlen($pretty)) {
      if (preg_match("/^(\d+)\/(\d+)\/(.*)$/", $pretty, $M)) {
        $year = $M[1];
        $month = $M[2];
        $path = $M[3];
        $date = strtolower(date('Y/M', mktime(0, 0, 0, $month, 1, $year)));
        $pretty = "$date/$path";
      }
      $pretty = "blog/$pretty";

      if (preg_match(",blog/\d+/[a-z]+/$,", $pretty)) {
        /* blog/2006/jan/ -> no logical equivalent, take them to the home
         * page instead */
        $pretty = '';
      }
    }
    if (strlen($pretty) && strpos($nid, '-')) {
      // does this correspond with our json map?
      if (isset($art->byid->$nid)) {
        $j = $art->byid->$nid;
        if ($pretty != "blog" . $j->path) {
          $pretty = "blog" . $j->path;
        }
      } else {
        // probably a comment, or child block
        $pretty = '';
      }
    }
    if (isset($map[$nid]) && $map[$nid] != $pretty) {
      echo "Compare " . $map[$nid] . " and $pretty\n";
      exit;
    }
    $map[$nid] = $pretty;
  }
}

$fp = fopen('netevil.map', 'wb');
foreach ($map as $k => $v) {
  fprintf($fp, "$k $v\n");
  if (preg_match("/^blog\/(.*)$/", $v, $M)) {
    fprintf($fp, "%s %s\n", $M[1], $v);
  }
}
fclose($fp);


