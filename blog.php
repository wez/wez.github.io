<?php # vim:ts=2:sw=2:et:
include 'inc/layout.php';
include 'inc/rest.php';

$pi = urldecode($_SERVER['PATH_INFO']);

if (strpos($pi, '..') !== false) {
  header('HTTP/1.1 400 Invalid Request');
  wfo_head('Not found');
  echo "Invalid request";
  wfo_foot();
  exit();
}

$A = json_decode(wfo_file_get('posts/articles.json'));
function format_date($datestr)
{
  return date('M j, Y', strtotime($datestr));
}

function make_article_link($ent)
{
  global $WEBROOT;
  $target = $WEBROOT . 'blog' . $ent->path;
  $subj = wfo_html_esc($ent->subject);

  return "<a href='$target'>$subj</a>";
}

function show_excerpt($ent)
{
  global $WEBROOT;

  $subj = wfo_html_esc($ent->subject);
  $ctime = format_date($ent->date);
  $subj = wfo_html_esc($ent->subject);

  $tags = wfo_taglink($ent->tags);
  $artlink = make_article_link($ent);

  $box = <<<HTML
    <h2 class='subject'>$artlink</h2>
    $tags<br/>
    <span class='when'>$ctime</span>
HTML;

  if ($ent->changed) {
    $mtime = format_date($ent->changed);
    $box .= "<br><span class='when'>Updated $mtime</span>";
  }

  $box .= "<div class='excerpt'>$ent->excerpt</div>\n";

  return $box;
}

$i = 0;
$taglist = '';
$recent = '';

$tags = array();
foreach ($A->tags as $tag => $tids) {
  $tags[] = $tag;
}
usort($tags, 'strnatcasecmp');
foreach ($tags as $tag) {
  $taglist .= '<li>' . wfo_taglink($tag) . "</li>\n";
}

foreach ($A->byid as $ent) {
  if ($i++ <= 20) {
    $recent .= '<li>' . make_article_link($ent) . "</li>\n";
  }
}

$rightbox = <<<HTML
<h3>Recent Articles</h3>
<ul class='recentarticles'>
$recent
</ul>
<h3>Articles by tag</h3>
<ul class='taglist'>
$taglist
</ul>
HTML;

if ($pi == '/') {

  wfo_head('Recent Articles');

  if (!isset($_GET['limit'])) {
    $limit = 10;
  } else {
    $limit = (int)$_GET['limit'];
  }
  if (isset($_GET['offset'])) {
    $off = (int)$_GET['offset'];
  } else {
    $off = 0;
  }

  $snip = '';
  $arts = get_object_vars($A->byid);
  $more = $off + $limit < count($arts);
  $less = $off > 0;
  $arts = array_slice($arts, $off, $limit);
  foreach ($arts as $ent) {
    $snip .= show_excerpt($ent);
  }

  if ($less) {
    $p = $off - $limit;
    if ($p < 0) {
      $p = '';
    } else {
      $p = "?offset=$p";
    }
    $snip .= "<a href='${WEBROOT}blog/$p' class='blognav newer'>Newer Entries</a> ";
  }
  if ($more) {
    $p = '?offset=' . ($off + $limit);
    $snip .= "<a href='${WEBROOT}blog/$p' class='blognav older'>Older Entries</a> ";
  }

  wfo_box('recentarticles', array($snip, $rightbox));


} else if (!strncmp($pi, "/tag/", 5)) {

  $tag = substr($pi, 5);

  if (!isset($A->tags->$tag)) {
    header('HTTP/1.1 404 Not found');
    wfo_head("No Articles tagged $tag");
    wfo_box('notfound', array(
      "No Articles tagged " . wfo_html_esc($tag) . 
      " were found on this server"));
    wfo_foot();
    exit();
  }
  wfo_head("Articles tagged $tag");

  if (!isset($_GET['limit'])) {
    $limit = 10;
  } else {
    $limit = (int)$_GET['limit'];
  }
  if (isset($_GET['offset'])) {
    $off = (int)$_GET['offset'];
  } else {
    $off = 0;
  }

  $snip = '';
  $tags = array_slice($A->tags->$tag, $off, $limit);
  $ntags = count($A->tags->$tag);
  $more = $off + $limit < $ntags;
  $less = $off > 0;
  foreach ($tags as $id) {
    $ent = $A->byid->$id;
    $snip .= show_excerpt($ent);
  }

  $ut = urlencode($tag);
  if ($less) {
    $p = $off - $limit;
    if ($p < 0) {
      $p = '';
    } else {
      $p = "?offset=$p";
    }
    $snip .= "<a href='${WEBROOT}blog/tag/$ut$p'>Newer Entries</a> ";
  }
  if ($more) {
    $p = '?offset=' . ($off + $limit);
    $snip .= "<a href='${WEBROOT}blog/tag/$ut$p'>Older Entries</a> ";
  }
  wfo_box('tagged', array($snip, $rightbox));

} else {

  if (isset($A->alias->$pi)) {
    $u = $A->alias->$pi;
    $pi = $A->byid->$u->path;
  }

  $article = "posts$pi";
  if (!is_dir($article) || !file_exists("$article/index.html")) {
    header('HTTP/1.1 404 Not found');
    wfo_head('Article not found');
    wfo_box('notfound', array(
      "Article " . wfo_html_esc($pi) . " was not found on this server"));
    wfo_foot();
    exit();
  }

  $meta = json_decode(wfo_file_get("$article/meta.json"));
  $content = wfo_file_get("$article/index.html");
  wfo_head($meta->subject);

  $ctime = format_date($meta->date);
  $subj = wfo_html_esc($meta->subject);

  $tags = wfo_taglink($meta->tags);

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
  if (isset($_GET['comments'])) {
    if (file_exists("$article/comments.json")) {
      $comments = json_decode(wfo_file_get("$article/comments.json"));
    } else {
      $comments = array();
    }
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
  } else {
    $title = addcslashes($meta->subject, "'\"\n\r\t");
    $commentlink = $WEBROOT . "blog/" . $meta->url . "?comments=1";
    $id = $meta->uniqid;
    $com = <<<HTML
  <div class='comment'>
<h2>Comments</h2>
<div id="disqus_thread">
  <a href="$commentlink">See this page with comments inline</a>
</div>
<script type="text/javascript">
var disqus_developer = 1;
var disqus_title = '$title';
var disqus_identifier = '$id';
$('#disqus_thread').html('');
  (function() {
   var dsq = document.createElement('script');
   dsq.type = 'text/javascript';
   dsq.async = true;
   dsq.src = 'http://testingevilasindr2.disqus.com/embed.js';
   (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
  })();
</script>
</div>
HTML;
    wfo_box('comments', array($com));
  }
}

wfo_foot();
