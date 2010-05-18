<?php # vim:ts=2:sw=2:et:

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
  with Juliette, Xander and Lily. <a href="bio.php" title="read more">(read more)</a>
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
  <a href="bio.php" title="read more">(read more)</a>
</p>
HTML
  ,

  'main-links' => <<<HTML
<p>
  <a href="bio.php">Biography</a><br/>
  <a href="blog/">Blog</a><br/>
  <a href="projects.php">Projects</a><br/>
  <a href="publications.php">Publications</a><br/>
</p>
HTML
  ,

  'feeds' => <<<HTML
   <p>
    <a title="Subscribe to my feed, Evil, as in Dr." rel="alternate"
      type="application/rss+xml"
      href="http://feeds.netevil.org/EvilAsInDr"><img
        src="http://netevil.org/images/feed-icon16x16.png"
        width="16" height="16" alt=""></a>
    <a title="Subscribe to my feed, Evil, as in Dr." rel="alternate"
      type="application/rss+xml"
      href="http://feeds.netevil.org/EvilAsInDr">Subscribe to my Blog</a>
    </p>
    <p>
      <a href="http://feeds.netevil.org/EvilAsInDr"><img
        src="http://feeds.feedburner.com/~fc/EvilAsInDr?bg=ff6633&amp;fg=000000&amp;anim=0"
        height="26" width="88" style="border:0" alt="" /></a>
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
  global $RELROOT;

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
    
    <link rel="stylesheet" href="{$RELROOT}style.css" type="text/css">
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
        <div id="maintitle"><a href="$RELROOT">$AREA_TITLE</a></div>
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
        <a href="/publications.php#copyright">&copy; 2003-2010 Wez Furlong</a>
      </p>
    </div>
  </body>
</html>

HTML;
}
