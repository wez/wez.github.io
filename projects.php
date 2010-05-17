<?php # vim:ts=2:sw=2:et:
include 'inc/layout.php';
include 'inc/rest.php';
include 'inc/projects.php';

wfo_head('Projects');

wfo_box('projabout', array(<<<HTML
<p>
  This page lists a number of OpenSource projects that I've been involved
  with.  This is not an exhaustive list (but may well be exhausting!).
  This information is aggregated from the following services:
</p>
<style>
.svclogo {
  padding-right: 2em;
}
.svclogo img {
  vertical-align: middle;
}
</style>
<p>
  <a class='svclogo' href="http://bitbucket.org"><img src="images/bitbucketlogo.png"></a>
  <a class='svclogo' href="http://github.com"><img src="images/githublogo.png"></a>
  <a class='svclogo' href="https://labs.omniti.com"><img src="images/omnitilabslogo.png"></a>
  <a class='svclogo' href="http://www.ohloh.net"><img src="http://www.ohloh.net/images/badges/mini.gif" width="80" height="15" /></a>
</p>

HTML
));

$repos = wfo_get_project_data();

foreach ($repos as $repo) {
  $COL1 = '';

  if ($repo->website) {
    $url = wfo_html_esc($repo->website);
  } else {
    $url = wfo_html_esc($repo->source);
  }

  if ($repo->logo_url) {
    $COL1 .= "<a href='$url'><img class='logo' src='$repo->logo_url'></a>";
  }

  $COL1 .= "<a href='$url'><b>" . wfo_html_esc($repo->name) . "</b></a><br>\n";
  $COL1 .= "<p>" . wfo_html_esc($repo->description) . "</p><br>\n";

  $COL2 = "<div class='bitbucketinfo'><div class='bbsummary'>";
  if (strlen($repo->website)) {
    $COL2 .= "<a href='" . wfo_html_esc($repo->website) . "'>Web</a> ";
  }
  $COL2 .= "<a href='" . wfo_html_esc($repo->source) . "'>Source</a>";
  if (strlen($repo->ohloh_url)) {
    $COL2 .= " <a href='$repo->ohloh_url'>Stats</a>";
  }
  $COL2 .= "<br>";
    
  if ($repo->followers) {
    $COL2 .= $repo->followers . " followers<br>";
  }

  if ($repo->total_commits) {
    $COL2 .= $repo->total_commits . " commits<br>\n";
  } else if ($repo->recent_commits) {
    $COL2 .= $repo->recent_commits . " commits<br>\n";
  }

  if ($repo->last_change) {
    $COL2 .= date('M j, Y', $repo->last_change) . "\n";
  }
  if ($repo->commit_msg) {
    $msg = wfo_html_esc(join("\n", 
      array_slice(explode("\n", $repo->commit_msg), 0, 3)));
    $COL2 .= "</div>";
    $COL2 .= "<p class='commitlog'>" . nl2br($msg) . "</p>";
  } else {
    $COL2 .= "</div>";
  }

  $COL2 .= "</div>";

  wfo_box('bb-repo-' . $repo->slug, array($COL1, $COL2), 'projinfo');
}

wfo_foot();

