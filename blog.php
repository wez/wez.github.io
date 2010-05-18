<?php # vim:ts=2:sw=2:et:
include 'inc/layout.php';

$pi = $_SERVER['PATH_INFO'];
if (strpos($pi, '..') !== false) {
  header('HTTP/1.1 400 Invalid Request');
  wfo_head('Not found');
  echo "Invalid request";
  wfo_foot();
  exit();
}

$levels = explode('/', $pi);
$RELROOT = str_repeat("../", count($levels)-1);

$article = "posts$pi";
if (!is_dir($article) || !file_exists("$article/index.html")) {
  header('HTTP/1.1 404 Not found');
  wfo_head('Article not found');
  wfo_box('notfound', array(
    "Article " . wfo_html_esc($pi) . " was not found on this server"));
  wfo_foot();
  exit();
}

$meta = json_decode(file_get_contents("$article/meta.json"));
$content = file_get_contents("$article/index.html");
if (file_exists("$article/comments.json")) {
  $comments = json_decode(file_get_contents("$article/comments.json"));
} else {
  $comments = array();
}

function format_date($datestr)
{
  return date('M j, Y', strtotime($datestr));
}

wfo_head($meta->subject);

$ctime = format_date($meta->date);
$subj = wfo_html_esc($meta->subject);

$tags = $meta->tags;
if (count($tags) > 1) {
  $last_tag = array_pop($tags);
  $t = array();
  foreach ($tags as $tag) {
    $t[] = wfo_html_esc($tag);
  }
  $tags = join(", ", $t) . " and " . wfo_html_esc($last_tag);
} else if (count($tags) == 0) {
  $tags = '';
} else {
  $tags = wfo_html_esc($tags[0]);
}

$header = <<<HTML
<h1 class='subject'><a href='#'>$subj</a></h1>
$tags<br/>
<span class='when'>$ctime</span>
HTML;

if ($meta->changed) {
  $mtime = format_date($meta->changed);
  $header .= "<br><span class='when'>Updated $mtime</span>";
}

wfo_box('subject', array($header));

wfo_box('content', array(
  $content
));

$com = '';
if (count($comments)) {
  wfo_box('comments', array(<<<HTML
  <div class='comment'>
<h2>Comments</h2>
</div>
HTML
  ));
  foreach ($comments as $c) {
    $when = format_date($c->d);
    $img = 'http://www.gravatar.com/avatar/' . md5($c->email) 
            . '?s=32&amp;d=identicon';
    $who = wfo_html_esc($c->n);
    if (preg_match("/^https?:\/\//", $c->url)) {
      $url = wfo_html_esc($c->url);
    } else {
      $url = "#$c->i";
    }
    $text = $c->c;
    $box = <<<HTML
<div class='comment'>
  <div class='who'>
    <a name='$c->u'></a>
    <img src='$img'>
    <a class='who' href='$url' rel='nofollow'>$who</a>
    <span class='when'>$when</span>
  </div>
  <div class='commenttext'>$text</div>
</div>
HTML;
    wfo_box("comment-$c->i", array($box));
  }
}

echo $com;

wfo_foot();
