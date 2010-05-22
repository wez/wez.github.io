<?php # vim:ts=2:sw=2:et:

class wfo_repo_info {
  var $name;
  var $description;
  var $website;
  var $source;
  var $total_commits;
  var $recent_commits;
  var $followers;
  var $commit_msg;
  var $last_change;
  var $ohloh_url;
  var $logo_url;
};

function wfo_sort_repos($a, $b)
{
  $A = max($a->total_commits, $a->recent_commits);
  $B = max($b->total_commits, $b->recent_commits);
  $d = $B - $A;
  if ($d) {
    return $d;
  }
  return strnatcasecmp($a->name, $b->name);
}

function wfo_get_project_data()
{
  $repos = array();

  $bb = wfo_rest('GET', 'http://api.bitbucket.org/1.0/users/wez/');
  foreach ($bb->repositories as $repo) {
    $R = new wfo_repo_info;
    $R->name = $repo->name;
    $R->description = $repo->description;
    $R->website = $repo->website;
    $R->source = "http://bitbucket.org/wez/$repo->slug";
    $R->followers = $repo->followers_count;

    $cs = wfo_rest('GET', "http://api.bitbucket.org/1.0/repositories/wez/$repo->slug/changesets/", array('limit' => 1));
    if (is_object($cs)) {
      $R->total_commits = $cs->count;

      $r = $cs->changesets[0];
      $R->last_change = strtotime($r->timestamp);
      $R->commit_msg = $r->message;
    }

    $repos[] = $R;
  }

  $gh = wfo_rest('GET', 'http://github.com/api/v2/json/repos/show/wez');

  foreach ($gh->repositories as $repo) {
    if ($repo->private) continue;

    $R = new wfo_repo_info;
    $R->name = $repo->name;
    $R->description = $repo->description;
    $R->website = $repo->homepage;
    $R->source = $repo->url;
    $R->followers = $repo->watchers;

    $cs = wfo_rest('GET',
        "http://github.com/api/v2/json/commits/list/wez/$repo->name/master");
    if (is_object($cs)) {
      $R->recent_commits = count($cs->commits);
      $r = $cs->commits[0];
      $R->last_change = strtotime($r->committed_date);
      $R->commit_msg = $r->message;
    }

    $repos[] = $R;
  }

  foreach (array(
      //'gimli',
      'jlog', 'portableumem') as $name) {

    $trac = wfo_rest('GET', 
        "http://labs.omniti.com/trac/$name/log/?limit=1&mode=stop_on_copy&format=rss", null, 'xml');

    $R = new wfo_repo_info;
    $R->name = $name;
    $R->source = "https://labs.omniti.com/$R->name/trunk";
    $R->website = "https://labs.omniti.com/trac/$R->name";
    $lines = explode("\n", 
        wfo_rest('GET',
          "http://labs.omniti.com/trac/$name/wiki/Abstract?format=txt", null, 'txt'));
    if (preg_match("/^\s*=+\s*(.*)\s+=+\s*$/", $lines[0])) {
      array_shift($lines);
    }
    $R->description = join("\n", $lines);

    foreach ($trac->channel->item as $item) {
      $R->last_change = strtotime($item->pubDate);
      $R->commit_msg = html_entity_decode($item->description,
          ENT_QUOTES, 'utf-8');
      if (preg_match("/^Revision\s(\d+):/", $item->title, $M)) {
        $R->total_commits = $M[1];
      }
      break;
    }

    $repos[] = $R;
  }

  $sf_skip = array(
    'libumem' => true
  );
  // See: http://sourceforge.net/apps/trac/sourceforge/wiki/API
  $sfuser = wfo_rest('GET',
              'http://sourceforge.net/api/user/username/wez/json');
  foreach ($sfuser->User->projects as $proj) {
    if (isset($sf_skip[$proj->name])) {
      continue;
    }
    $pdata = wfo_rest('GET',
      "http://sourceforge.net/api/project/id/$proj->id/json");
    $proj = $pdata->Project;
    $R = new wfo_repo_info;
    $R->name = $proj->name;
    $R->description = $proj->description;
    $R->website = $proj->homepage;
    $R->source = $proj->CVSRepository->browse;
    $repos[] = $R;
  }

  // Query ohloh
  $key = trim(file_get_contents(".ohloh"));
  $me = '0616d52b31da6ce7bd0d296a5f61a96e'; // email md5

  $ohverrides = array(
    'PHP' => 'http://svn.php.net/repository/php/php-src/trunk/',
  );


  $ohloh = wfo_rest('GET',
      "http://www.ohloh.net/accounts/$me/projects.xml",
      array(
        'api_key' => $key
        ),
      'xml');
  foreach ($ohloh->result->project as $proj) {
    $found = false;
    foreach ($repos as $repo) {
      if (rtrim($repo->source, '/') == rtrim($proj->homepage_url, '/') ||
          rtrim($repo->website, '/') == rtrim($proj->homepage_url, '/')) {
        $found = true;
        $repo->ohloh_url = "https://www.ohloh.net/p/$proj->url_name";
        $repo->followers += (int)$proj->user_count;
        if (!$repo->logo_url) {
          if ($proj->medium_logo_url) {
            $repo->logo_url = (string)$proj->medium_logo_url;
          } else if ($proj->small_logo_url) {
            $repo->logo_url = (string)$proj->small_logo_url;
          }
        }
        break;
      }
    }
    if (!$found) {
//          var_dump($proj);
      $R = new wfo_repo_info;

      $R->ohloh_url = "https://www.ohloh.net/p/$proj->url_name";
      $R->followers = (int)$proj->user_count;
      if ($proj->medium_logo_url) {
        $R->logo_url = (string)$proj->medium_logo_url;
      } else if ($proj->small_logo_url) {
        $R->logo_url = (string)$proj->small_logo_url;
      }
      $R->name = (string)$proj->name;
      $R->description = (string)$proj->description;
      $R->website = (string)$proj->homepage_url;

      if (isset($ohverrides[$R->name])) {
        $R->source = $ohverrides[$R->name];
      } else {
        $sources = wfo_rest('GET',
            "http://www.ohloh.net/projects/$proj->id/enlistments.xml",
            array(
              'api_key' => $key
              ),
            'xml');
        foreach ($sources->result->enlistment as $enl) {
          $R->source = (string)$enl->repository->url;
        }
      }

      $sizes = wfo_rest('GET',
          "http://www.ohloh.net/projects/$proj->id/analyses/latest/size_facts.xml",
          array(
            'api_key' => $key
            ),
          'xml');
      foreach ($sizes->result->size_fact as $size) {
        $R->total_commits = $size->commits;
      }

      $repos[] = $R;
    }
  }

  usort($repos, 'wfo_sort_repos');

  return $repos;
}

