<?php # vim:ts=2:sw=2:et:

if ($_SERVER['REMOTE_ADDR'] == '::1') {
  $WEBROOT = 'http://localhost/~wez/wfo/';
} else {
  $WEBROOT = 'http://wezfurlong.org/';
}

$AREA = null;
$AREAS = array(
  'home' => array(
    'title' => 'Wez Furlong',
    'strap' => 'Software Architect, OpenSourceror',
    'boxes' => array('me-short', 'main-links', 'feeds'),
  ),
);

$BOXES = array(
  'me' => <<<HTML

<span id="mugshot"><img alt="Wez Furlong" border="0"
      class="photo" src="http://netevil.org/images/wez-head-75.jpg"></span>
  I am Wez Furlong, Chief Software Architect at
  <a title="Message Systems, Inc" href="http://messagesystems.com"
    >Message Systems</a>.
  We're responsible for building an
    <a href="http://messagesystems.com/">awesome Messaging Platform</a>.
</p>
<p>
  I'm also a <a href="http://php.net">PHP</a> Core developer and
  OpenSource contributor, residing in Maryland, USA
  with Juliette, Xander and Lily. <a href="{$WEBROOT}about.php" title="read more">(read more)</a>
</p>
HTML
  ,
  'me-short' => <<<HTML

<p>
<span id="mugshot"><img alt="Wez Furlong" border="0"
      class="photo" src="http://netevil.org/images/wez-head-75.jpg"></span>
  I am Wez Furlong, Chief Software Architect at
  <a href="http://messagesystems.com/">Message Systems</a>,
  <a href="http://php.net">PHP</a> Core developer and
  OpenSource contributor.  I live in Maryland, USA
  with Juliette, Xander and Lily.
  <a href="${WEBROOT}about.php" title="read more">(read more)</a>
</p>
HTML
  ,

  'main-links' => <<<HTML
<p>
  <a href="{$WEBROOT}about.php">About</a><br/>
  <a href="{$WEBROOT}blog/">Blog</a><br/>
  <a href="{$WEBROOT}projects.php">Projects</a><br/>
  <a href="{$WEBROOT}publications.php">Publications</a><br/>
</p>
HTML
  ,

  'feeds' => <<<HTML
   <p>
    <a title="Subscribe to my blog" rel="alternate"
      type="application/rss+xml"
      href="http://feeds.netevil.org/EvilAsInDr"><img
        src="http://www.feedburner.com/fb/images/pub/feed-icon32x32.png"
        width="32" height="32" alt="Subscribe to my blog"></a>
    &nbsp;
    <a title="Subscribe to my blog" rel="alternate"
      type="application/rss+xml"
      href="http://feeds.netevil.org/EvilAsInDr"><img
        src="http://feeds.feedburner.com/~fc/EvilAsInDr?bg=ff6633&amp;fg=000000&amp;anim=0"
        height="26" width="88" style="border:0" alt="" /></a>
    </p>
    <p>
      <a href="http://www.twitter.com/wezfurlong"><img src="http://twitter-badges.s3.amazonaws.com/follow_me-a.png" alt="Follow wezfurlong on Twitter"/></a>
    </p>

HTML
  ,

);

function wfo_html_esc($string)
{
  return htmlentities($string, ENT_QUOTES, 'utf-8');
}

function wfo_head($title, $area = 'home')
{
  global $AREAS;
  global $AREA;
  global $BOXES;
  global $WEBROOT;

  $A = $AREAS[$area];
  $AREA = $area;
  $AREA_TITLE = wfo_html_esc($A['title']);
  $AREA_STRAP = wfo_html_esc($A['strap']);

  if (strlen($title)) {
    $title .= ' - ';
  }
  $title .= $A['title'];

  $TITLE = wfo_html_esc($title);

  $boxes = array();
  foreach ($A['boxes'] as $name) {
    $boxes[] = $BOXES[$name];
  }

  if (false) {
    // http://wezfurlong.org
    $GOOG_API = 'ABQIAAAA9vTGCH2CFotsHMU2kskGnxSPCRp_aUHnqMsqXKz9BRrSI8fZfBTrGy9vEssfs76UXSSoyuP18dfERg';
  } else {
    // http://localhost/~wez/wfo/
    $GOOG_API = 'ABQIAAAA9vTGCH2CFotsHMU2kskGnxTcMPbcf6onPCdieVoDEvN3laEg8xR_ddmnFoRx_4Or617G8Sss-kgC0Q';
  }

  echo <<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="Wez Furlong">
    <meta name="copyright"
      content="&copy; 2003-2010, Wez Furlong, unless otherwise attributed">
    
    <link rel="stylesheet" href="{$WEBROOT}style.css" type="text/css">
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">

    <link rel="openid.server" 
        href="https://api.screenname.aol.com/auth/openidServer">
    <link rel="openid.delegate"
        href="http://openid.aol.com/wezfurlong">

    <meta http-equiv="X-XRDS-Location" 
          content="http://netevil.org/yadis.xrdf">
    <meta http-equiv="X-YADIS-Location"
          content="http://netevil.org/yadis.xrdf">

    <!-- link rel="alternate" type="application/rss+xml"
          title="Evil, as in Dr."
          href="/feeds/rss.xml" -->
    <title>$TITLE</title>
    <script src="http://www.google.com/jsapi?key=$GOOG_API" type="text/javascript"></script>
    <script type="text/javascript">
      google.load('jquery', '1.4.2');
    </script>
  </head>
  <body>
    <div id="navtitle">
      <div id="tpad">
        <div id="maintitle"><a href="$WEBROOT">$AREA_TITLE</a></div>
        <div id="strapline">$AREA_STRAP</div>
      </div>
    </div>
HTML;

  wfo_box('infobar', $boxes);

}

function wfo_box($id, $COLS, $class = null)
{
  if ($class) {
    $class = " $class";
  } else {
    $class = '';
  }
  if (count($COLS) == 1) {
    list($COL1) = $COLS;
    echo <<<HTML
    <div id="$id" class="colmask $class">
        <div class="onlycol">
          $COL1
        </div>
    </div>
HTML;

  } else if (count($COLS) == 2) {
    list($COL1, $COL2) = $COLS;

echo <<<HTML
    <div id="$id" class="colmask rightmenu $class">
      <div class="colleft">
        <div class="col1">
          $COL1
        </div>
        <div class='col2'>
          $COL2
        </div>
      </div>
    </div>
HTML;

  } else if (count($COLS) == 3) {
    list($COL1, $COL2, $COL3) = $COLS;
    echo <<<HTML
    <div id="$id" class="colmask $class">
      <div class="colmid">
        <div class="colleft">
          <div class="col1">
            $COL1
          </div>
          <div class="col2">
            $COL2
          </div>
          <div class="col3">
            $COL3
          </div>
        </div>
      </div>
    </div>
HTML;
  }
}


function wfo_foot()
{
  echo <<<HTML
    <div id="footer">
      <p id="copyright">



<a rel="license" href="http://creativecommons.org/licenses/by/3.0/"
><img alt="Creative Commons License" style="border-width:0"
  valign="middle"
   src="http://i.creativecommons.org/l/by/3.0/80x15.png"/></a>
        <a rel="license" 
          href="http://creativecommons.org/licenses/by/3.0/"
          >Copyright &copy; 2003-2010</a>
  <a xmlns:cc="http://creativecommons.org/ns#"
        property="cc:attributionName" rel="cc:attributionURL"
        href="http://wezfurlong.org">Wez Furlong</a>
      </p>

    </div>
  </body>
</html>

HTML;
}

function wfo_taglink($name)
{
  global $WEBROOT;
  if (is_array($name)) {
    $tags = $name;
    if (count($tags) > 1) {
      $last_tag = array_pop($tags);
      $t = array();
      foreach ($tags as $tag) {
        $t[] = wfo_taglink($tag);
      }
      $tags = join(", ", $t) . " and " . wfo_taglink($last_tag);
    } else if (count($tags) == 0) {
      $tags = '';
    } else {
      $tags = wfo_taglink($tags[0]);
    }
    return $tags;
  }
  return "<a href='{$WEBROOT}blog/tag/" . urlencode($name) . "'>" .
    wfo_html_esc($name) . "</a>";
}


