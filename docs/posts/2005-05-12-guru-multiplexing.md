---
layout: post
title: "Guru - Multiplexing"
date: 2005-05-12 19:49:30

uniqid: 427d6d12-c107-9816-7428-27d6d128f5e5
categories: 
 - PHP
 - Publications
---
<p>[The following mini-article is something that I wrote for the International PHP Magazine a while back, as part of the 'ask a guru' column; I'm re-publishing it here because it's useful and because people have asked me about the topic twice in the last two days]   </p>
<p><b>Question:</b>   </p>
<p>Is there a way to do a form of threading in PHP?   </p>
<p>Say for instance you write a PHP application to monitor a service on a number of servers, it would be nice to be able query a number of servers at the same time rather then query them one-by-one.   </p>
<p>Can it be done?   </p>
<p><b>Answer:</b>   </p>
<p>People often assume that you need to fork or spawn threads whenever you need to do several things at the same time - and when they realize that PHP doesn't support threading they move on to something less nice, like perl.   </p>
<p>The good news is that in the majority of cases you <b>don't</b> need to fork or thread at all, and that you will often get much better performance for not forking/threading in the first place.   </p>
<p>Say you need to check up on web servers running on a number of hosts to make sure that they are still responding to the outside world.  You might write a script like this:   </p>
<pre class="phpcode"><span class="default">&lt;?php
$hosts </span><span class="keyword">= array(</span><span class="string">"host1.sample.com"</span><span class="keyword">, </span><span class="string">"host2.sample.com"</span><span class="keyword">, </span><span class="string">"host3.sample.com"</span><span class="keyword">);
</span><span class="default">$timeout </span><span class="keyword">= </span><span class="default">15</span><span class="keyword">;
</span><span class="default">$status </span><span class="keyword">= array();
foreach (</span><span class="default">$hosts </span><span class="keyword">as </span><span class="default">$host</span><span class="keyword">) {
    </span><span class="default">$errno </span><span class="keyword">= </span><span class="default">0</span><span class="keyword">;
    </span><span class="default">$errstr </span><span class="keyword">= </span><span class="string">""</span><span class="keyword">;
    </span><span class="default">$s </span><span class="keyword">= </span><span class="default">fsockopen</span><span class="keyword">(</span><span class="default">$host</span><span class="keyword">, </span><span class="default">80</span><span class="keyword">, </span><span class="default">$errno</span><span class="keyword">, </span><span class="default">$errstr</span><span class="keyword">, </span><span class="default">$timeout</span><span class="keyword">);
    if (</span><span class="default">$s</span><span class="keyword">) {
        </span><span class="default">$status</span><span class="keyword">[</span><span class="default">$host</span><span class="keyword">] = </span><span class="string">"Connected\\n"</span><span class="keyword">;
        </span><span class="default">fwrite</span><span class="keyword">(</span><span class="default">$s</span><span class="keyword">, </span><span class="string">"HEAD / HTTP/1.0\\r\\nHost: $host\\r\\n\\r\\n"</span><span class="keyword">);
        do {
            </span><span class="default">$data </span><span class="keyword">= </span><span class="default">fread</span><span class="keyword">(</span><span class="default">$s</span><span class="keyword">, </span><span class="default">8192</span><span class="keyword">);
            if (</span><span class="default">strlen</span><span class="keyword">(</span><span class="default">$data</span><span class="keyword">) == </span><span class="default">0</span><span class="keyword">) {
                break;
            }
            </span><span class="default">$status</span><span class="keyword">[</span><span class="default">$host</span><span class="keyword">] .= </span><span class="default">$data</span><span class="keyword">;
        } while (</span><span class="default">true</span><span class="keyword">);
        </span><span class="default">fclose</span><span class="keyword">(</span><span class="default">$s</span><span class="keyword">);
    } else {
        </span><span class="default">$status</span><span class="keyword">[</span><span class="default">$host</span><span class="keyword">] = </span><span class="string">"Connection failed: $errno $errstr\\n"</span><span class="keyword">;
    }
}
</span><span class="default">print_r</span><span class="keyword">(</span><span class="default">$status</span><span class="keyword">);
</span><span class="default">?&gt;
</span></pre><p>This works fine, but since fsockopen() doesn't return until it has resolved the hostname and made a successful connection (or waited up to $timeout seconds), extending this script to monitor a larger number of hosts makes it slow to complete.   </p>
<p>There is no reason why we have to do it sequentially; we can make asynchronous connections - that is, connections where we don't have to wait for fsockopen to return an opened connection.  PHP will still need to resolve the hostname (so its better to use IP addresses), but will return as soon as it has started to open the connection, so that we can move on to the next host.   </p>
<p>There are two ways to achieve this; in PHP 5, you can use the new stream_socket_client() function as a drop-in replacement for fsockopen().  In earlier versions of PHP, you need to get your hands dirty and use the sockets extension.   </p>
<p>Here's how to do it in PHP 5:   </p>
<pre class="phpcode"><span class="default">&lt;?php
$hosts </span><span class="keyword">= array(</span><span class="string">"host1.sample.com"</span><span class="keyword">, </span><span class="string">"host2.sample.com"</span><span class="keyword">, </span><span class="string">"host3.sample.com"</span><span class="keyword">);
</span><span class="default">$timeout </span><span class="keyword">= </span><span class="default">15</span><span class="keyword">;
</span><span class="default">$status </span><span class="keyword">= array();
</span><span class="default">$sockets </span><span class="keyword">= array();
</span><span class="comment">/* Initiate connections to all the hosts simultaneously */
</span><span class="keyword">foreach (</span><span class="default">$hosts </span><span class="keyword">as </span><span class="default">$id </span><span class="keyword">=&gt; </span><span class="default">$host</span><span class="keyword">) {
    </span><span class="default">$s </span><span class="keyword">= </span><span class="default">stream_socket_client</span><span class="keyword">(</span><span class="string">"$host:80"</span><span class="keyword">, </span><span class="default">$errno</span><span class="keyword">, </span><span class="default">$errstr</span><span class="keyword">, </span><span class="default">$timeout</span><span class="keyword">, 
        </span><span class="default">STREAM_CLIENT_ASYNC_CONNECT</span><span class="keyword">|</span><span class="default">STREAM_CLIENT_CONNECT</span><span class="keyword">);
    if (</span><span class="default">$s</span><span class="keyword">) {
        </span><span class="default">$sockets</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">] = </span><span class="default">$s</span><span class="keyword">;
        </span><span class="default">$status</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">] = </span><span class="string">"in progress"</span><span class="keyword">;
    } else {
        </span><span class="default">$status</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">] = </span><span class="string">"failed, $errno $errstr"</span><span class="keyword">;
    }
}
</span><span class="comment">/* Now, wait for the results to come back in */
</span><span class="keyword">while (</span><span class="default">count</span><span class="keyword">(</span><span class="default">$sockets</span><span class="keyword">)) {
    </span><span class="default">$read </span><span class="keyword">= </span><span class="default">$write </span><span class="keyword">= </span><span class="default">$sockets</span><span class="keyword">;
    </span><span class="comment">/* This is the magic function - explained below */
    </span><span class="default">$n </span><span class="keyword">= </span><span class="default">stream_select</span><span class="keyword">(</span><span class="default">$read</span><span class="keyword">, </span><span class="default">$write</span><span class="keyword">, </span><span class="default">$e </span><span class="keyword">= </span><span class="default">null</span><span class="keyword">, </span><span class="default">$timeout</span><span class="keyword">);
    if (</span><span class="default">$n </span><span class="keyword">&gt; </span><span class="default">0</span><span class="keyword">) {
        </span><span class="comment">/* readable sockets either have data for us, or are failed
         * connection attempts */
        </span><span class="keyword">foreach (</span><span class="default">$read </span><span class="keyword">as </span><span class="default">$r</span><span class="keyword">) {
            </span><span class="default">$id </span><span class="keyword">= </span><span class="default">array_search</span><span class="keyword">(</span><span class="default">$r</span><span class="keyword">, </span><span class="default">$sockets</span><span class="keyword">);
            </span><span class="default">$data </span><span class="keyword">= </span><span class="default">fread</span><span class="keyword">(</span><span class="default">$r</span><span class="keyword">, </span><span class="default">8192</span><span class="keyword">);
            if (</span><span class="default">strlen</span><span class="keyword">(</span><span class="default">$data</span><span class="keyword">) == </span><span class="default">0</span><span class="keyword">) {
                if (</span><span class="default">$status</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">] == </span><span class="string">"in progress"</span><span class="keyword">) {
                    </span><span class="default">$status</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">] = </span><span class="string">"failed to connect"</span><span class="keyword">;
                }
                </span><span class="default">fclose</span><span class="keyword">(</span><span class="default">$r</span><span class="keyword">);
                unset(</span><span class="default">$sockets</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">]);
            } else {
                </span><span class="default">$status</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">] .= </span><span class="default">$data</span><span class="keyword">;
            }
        }
        </span><span class="comment">/* writeable sockets can accept an HTTP request */
        </span><span class="keyword">foreach (</span><span class="default">$write </span><span class="keyword">as </span><span class="default">$w</span><span class="keyword">) {
            </span><span class="default">$id </span><span class="keyword">= </span><span class="default">array_search</span><span class="keyword">(</span><span class="default">$w</span><span class="keyword">, </span><span class="default">$sockets</span><span class="keyword">);
            </span><span class="default">fwrite</span><span class="keyword">(</span><span class="default">$w</span><span class="keyword">, </span><span class="string">"HEAD / HTTP/1.0\\r\\nHost: "
                </span><span class="keyword">. </span><span class="default">$hosts</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">] .  </span><span class="string">"\\r\\n\\r\\n"</span><span class="keyword">);
            </span><span class="default">$status</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">] = </span><span class="string">"waiting for response"</span><span class="keyword">;
        }
    } else {
        </span><span class="comment">/* timed out waiting; assume that all hosts associated
         * with $sockets are faulty */
        </span><span class="keyword">foreach (</span><span class="default">$sockets </span><span class="keyword">as </span><span class="default">$id </span><span class="keyword">=&gt; </span><span class="default">$s</span><span class="keyword">) {
            </span><span class="default">$status</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">] = </span><span class="string">"timed out " </span><span class="keyword">. </span><span class="default">$status</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">];
        }
        break;
    }
}
foreach (</span><span class="default">$hosts </span><span class="keyword">as </span><span class="default">$id </span><span class="keyword">=&gt; </span><span class="default">$host</span><span class="keyword">) {
    echo </span><span class="string">"Host: $host\\n"</span><span class="keyword">;
    echo </span><span class="string">"Status: " </span><span class="keyword">. </span><span class="default">$status</span><span class="keyword">[</span><span class="default">$id</span><span class="keyword">] . </span><span class="string">"\\n\\n"</span><span class="keyword">;
}
</span><span class="default">?&gt;
</span></pre><p>We are using stream_select() to wait for events on the sockets that we opened.  stream_select() calls the system select(2) function and it works like this: The first three parameters are arrays of streams that you want to work with;  you can wait for reading, writing and exceptional events (parameters one, two and three respectively).  stream_select() will wait up to $timeout seconds for an event to occur - when it does, it will modify the arrays you passed in to contain the sockets that have met your criteria.   </p>
<p>Now, using PHP 4.1.0 and later, if you have compiled in support for ext/sockets, you can use the same script as above, but you need to replace the regular streams/filesystem function calls with their equivalents from ext/sockets.  The major difference though is in how we open the connection; instead of stream_socket_client(), you need to use this function:   </p>
<pre class="phpcode"><span class="default">&lt;?php
</span><span class="comment">// This value is correct for Linux, other systems have other values
</span><span class="default">define</span><span class="keyword">(</span><span class="string">'EINPROGRESS'</span><span class="keyword">, </span><span class="default">115</span><span class="keyword">);
function </span><span class="default">non_blocking_connect</span><span class="keyword">(</span><span class="default">$host</span><span class="keyword">, </span><span class="default">$port</span><span class="keyword">, &#38;</span><span class="default">$errno</span><span class="keyword">, &#38;</span><span class="default">$errstr</span><span class="keyword">, </span><span class="default">$timeout</span><span class="keyword">) {
    </span><span class="default">$ip </span><span class="keyword">= </span><span class="default">gethostbyname</span><span class="keyword">(</span><span class="default">$host</span><span class="keyword">);
    </span><span class="default">$s </span><span class="keyword">= </span><span class="default">socket_create</span><span class="keyword">(</span><span class="default">AF_INET</span><span class="keyword">, </span><span class="default">SOCK_STREAM</span><span class="keyword">, </span><span class="default">0</span><span class="keyword">);
    if (</span><span class="default">socket_set_nonblock</span><span class="keyword">(</span><span class="default">$s</span><span class="keyword">)) {
        </span><span class="default">$r </span><span class="keyword">= @</span><span class="default">socket_connect</span><span class="keyword">(</span><span class="default">$s</span><span class="keyword">, </span><span class="default">$ip</span><span class="keyword">, </span><span class="default">$port</span><span class="keyword">);
        if (</span><span class="default">$r </span><span class="keyword">|| </span><span class="default">socket_last_error</span><span class="keyword">() == </span><span class="default">EINPROGRESS</span><span class="keyword">) {
            </span><span class="default">$errno </span><span class="keyword">= </span><span class="default">EINPROGRESS</span><span class="keyword">;
            return </span><span class="default">$s</span><span class="keyword">;
        }
    }
    </span><span class="default">$errno </span><span class="keyword">= </span><span class="default">socket_last_error</span><span class="keyword">(</span><span class="default">$s</span><span class="keyword">);
    </span><span class="default">$errstr </span><span class="keyword">= </span><span class="default">socket_strerror</span><span class="keyword">(</span><span class="default">$errno</span><span class="keyword">);
    </span><span class="default">socket_close</span><span class="keyword">(</span><span class="default">$s</span><span class="keyword">);
    return </span><span class="default">false</span><span class="keyword">;
}
</span><span class="default">?&gt;
</span></pre><p>Now, replace stream_select() with socket_select(), fread() with socket_read(), fwrite() with socket_write() and fclose() with socket_close() and you are ready to run the script.   </p>
<p>The advantage of the PHP 5 approach is that you can use stream_select() to wait on (almost!) any kind of stream - you can wait for keyboard input from the terminal by including STDIN in your read array for example, and you can also wait for data from pipes created by the proc_open() function.   </p>
<p>If you want PHP 4.3.x <b>and</b> want to use the native streams approach, I have prepared a patch that allows fsockopen to work asynchronously.  The patch is unsupported and won't be in an official PHP release, however, I've provided a wrapper that implements the stream_socket_client() function along with the patch, so that your code will be forwards compatible with PHP 5.   </p>
<p><b>Resources:</b>   </p>
<p><a href="http://www.php.net/stream_select">documentation for stream_select()</a><br /> <a href="http://www.php.net/socket_select">documentation for socket_select()</a><br /> <a href="http://www.php.net/~wez/guru-multiplexing.tgz">patch for PHP 4.3.2 and script to emulate stream_socket_client()</a>. (might work with later 4.3.x versions).   </p>
