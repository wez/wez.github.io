<?php # vim:ts=2:sw=2:et:

if (function_exists('date_default_timezone_set')) {
  date_default_timezone_set('UTC');
}

function prettify($title)
{
  $pretty = strtolower(trim($title));
  $pretty = str_replace('@', '-at-', $pretty);
  $pretty = str_replace('=', '-equals-', $pretty);
  $pretty = str_replace("i'll", '-i-will-', $pretty);
  $pretty = str_replace("i'm", '-i-am-', $pretty);
  $pretty = str_replace('\'', '', $pretty);
  $pretty = preg_replace('/[^a-z0-9]+/', '-', $pretty);
  $pretty = preg_replace('/-+/', '-', $pretty);
  $pretty = trim($pretty, '-');
  return $pretty;
}


