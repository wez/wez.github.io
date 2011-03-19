---
layout: post
title: "HTTP POST from PHP, without cURL"
date: 2006-11-15 13:59:51
updated: 2010-05-23
uniqid: 455b1d57-9d3c-5501-4134-55b1d579e745
categories: 
 - PHP
 - POST
 - REST
 - API
---
<p>
<em>Update May 2010: This is one of my most popular blog entries, so it seems
worthwhile to modernize it a little.  I've added an example of a generic REST
helper that I've been using in a couple of places below the original
do_post_request function in this entry.  Enjoy!</em>
</p>

<p>I don't think we do a very good job of evangelizing some of the nice things
that the PHP streams layer does in the PHP manual, or even in general.  At
least, every time I search for the code snippet that allows you to do an HTTP
POST request, I don't find it in the manual and resort to reading the source.
(You can find it if you search for &quot;HTTP wrapper&quot; in the online
documentation, but that's not really what you think you're searching for when
you're looking).

</p>

<p>
So, here's an example of how to send a POST request with straight up PHP, no
cURL:

</p>

<pre class='phpcode'>
&lt;?php
function do_post_request($url, $data, $optional_headers = null)
{
  $params = array('http' =&gt; array(
              'method' =&gt; 'POST',
              'content' =&gt; $data
            ));
  if ($optional_headers !== null) {
    $params['http']['header'] = $optional_headers;
  }
  $ctx = stream_context_create($params);
  $fp = @fopen($url, 'rb', false, $ctx);
  if (!$fp) {
    throw new Exception("Problem with $url, $php_errormsg");
  }
  $response = @stream_get_contents($fp);
  if ($response === false) {
    throw new Exception("Problem reading data from $url, $php_errormsg");
  }
  return $response;
}
</pre>

<!--more-->

<p>$optional_headers is a string containing additional HTTP headers that you
would like to send in your request.   </p>

<p>PHP's HTTP wrapper will automatically fill out the Content-Length header
based on the length of the $data that you pass in.  It will also automatically
set the Content-Type to application/x-www-form-urlencoded if you don't specify
one in the $optional_headers.   </p>

<p>I find this very handy; I don't need to code in redirection logic, HTTP auth
handling, user agent setting and so on; they are handled for me by PHP.   This works for HTTPS as well, if you have openssl enabled.</p>

<p>You may also want to look into <a
href="http://php.net/http_build_query">http_build_query()</a> which is a
convenience function that allows you to assemble query/post parameters from a
PHP variable, applying appropriate escaping.  You can find an example of this
in the REST helper below.

</p>

<p>Kudos to <a href="http://blog.libssh2.org/">Sara Golemon</a>
for both http_build_query and exposing the
HTTP context parameters up to userspace.
</p>


<h2>A Generic REST helper</h2>

<p>
Many web services offer a REST-ful interface for consuming their data, using
GET requests for information retrieval and POST requests for making changes.
Below you'll find a helper function that can very easily be used to consume a
REST API.
</p>

<p>
The <b>$url</b> parameter is the HTTP or HTTPS URL for the web service.
<b>$params</b> is an associative array of form parameters to pass to the web
service; they will be passed as _GET parameters for GET requests or _POST
parameters for POST requests.  The <b>$verb</b> parameter can be GET or POST
(and presumably any other valid HTTP REQUEST verb, such as PUT or DELETE,
although I haven't tried those and can't say whether they will work as
expected).  The <b>$format</b> parameter can be "json" or "xml" and will
automatically return a decoded json or XML document, respectively.

</p>

<p>
I've used simplexml here because it is... simple.  You could very easily add a
"dom" format to return the object using the richer and more complex DOM API
instead.

</p>

<p>
This function uses the <b>ignore_errors</b> context parameter.  Without this
set (the default is false), PHP will treat 400 and 500 HTTP status codes as a
failure to open the stream and won't return you any data.  This is usually what
you want when using fopen or file_get_contents, but REST services tend to set
the HTTP status to indicate the error <em>and</em> will usually send back a
payload that describes the error.  We turn on <b>ignore_errors</b> so that we
treat any returned payload as json or xml.
</p>

<p>
When using POST with REST, take care: PHP's HTTP redirection handler will drop
your POST payload if the endpoint issues a redirect.  If you experience
problems using POST with the function below, it might be due to redirects.
Most of the POST calls I've run into issue redirects if the URL is missing a
trailing '/' character.  In other words, if you experience problems where it
seems like your parameters are not being sent in, try appending a '/' to the
end of the URL and try it again.

</p>

<pre class='phpcode'>
&lt;?php
function rest_helper($url, $params = null, $verb = 'GET', $format = 'json')
{
  $cparams = array(
    'http' =&gt; array(
      'method' =&gt; $verb,
      'ignore_errors' =&gt; true
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

  $context = stream_context_create($cparams);
  $fp = fopen($url, 'rb', false, $context);
  if (!$fp) {
    $res = false;
  } else {
    // If you're trying to troubleshoot problems, try uncommenting the
    // next two lines; it will show you the HTTP response headers across
    // all the redirects:
    // $meta = stream_get_meta_data($fp);
    // var_dump($meta['wrapper_data']);
    $res = stream_get_contents($fp);
  }

  if ($res === false) {
    throw new Exception("$verb $url failed: $php_errormsg");
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

// This lists projects by Ed Finkler on GitHub:
foreach (
    rest_helper('http://github.com/api/v2/json/repos/show/funkatron')
    -&gt;repositories as $repo) {
  echo $repo-&gt;name, "&lt;br&gt;\n";
  echo htmlentities($repo-&gt;description), "&lt;br&gt;\n";
  echo "&lt;hr&gt;\n";
}

// This incomplete snippet demonstrates using POST with the Disqus API
var_dump(
  rest_helper(
    "http://disqus.com/api/thread_by_identifier/",
    array(
      'api_version' =&gt; '1.1',
      'user_api_key' =&gt; $my_disqus_api_key,
      'identifier' =&gt; $thread_unique_id,
      'forum_api_key' =&gt; $forum_api_key,
      'title' =&gt; 'HTTP POST from PHP, without cURL',
    ), 'POST'
  )
);

</pre>

<p>
You can find more documentation on the HTTP wrapper options in the <a
href="http://www.php.net/manual/en/wrappers.http.php">HTTP and HTTPS</a> page
in the PHP manual, more on <a href="http://develop.github.com/">the GitHub API
at github.com</a>, more on <a href="http://groups.google.com/group/disqus-dev/web/api-1-1">the Disqus API</a> and more on <a href="http://funkatron.com">Ed Finkler at his blog</a>.

</p>
