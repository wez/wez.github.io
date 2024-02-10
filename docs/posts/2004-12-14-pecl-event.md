---
layout: post
title: "PECL::event"
date: 2004-12-14 02:50:38

uniqid: 41be54fe-32ca-4159-5119-1be54fe97597
categories: 
 - PHP
---
<p>Yesterday I added the product of my weekend-of-hackery to PECL: the <a href="http://pecl.php.net/package-info.php?package=event">event</a> extension.   </p>
<p>PECL::event implements an event based scheduling engine that will execute a callback when a particular event is triggered.  The various different triggers allowed are:   </p>
<p><ul>
<li>EV_READ - a stream becomes ready to read
 <li>EV_WRITE - a stream becomes ready to write
 <li>EV_EXCEP - a stream has out-of-band data available to read
 <li>EV_TIMEOUT - a certain amount of time has elapsed
 <li>EV_SIGNAL - a signal was raised
   </ul>
<p>As you might have already guessed, PECL::event is probably most useful for longer-lived scripts that need to perform more than network &quot;operation&quot; without either taking too long (why do one after the other if you can do both at the same time?) or without one blocking the other and making it time out.   </p>
<p>I've previously given a talk (or was it a magazine article?) on multiplexing with streams in PHP; this extension takes things a step further by taking the complex scheduling logic out of your script.  As a bonus, it can also take advantage of the more scalable IO scheduling interfaces (epoll(4), kqueue, /dev/poll, poll(2) and select(2)) available on various different operating systems.  Scalable is one of those phrases that can easily be misinterpreted, so in this context <i>more scalable</i> means <i>lower overhead per file descriptor</i> , which should translate to faster execution time in your script.   </p>
<p>In practice, you probably won't notice much difference between the different engines in PECL::event, but you should notice the difference between a userspace implementation using stream_select() and PECL::event.   </p>
<p><b>How do I use it?</b>   </p>
<pre class="phpcode"><span class="default">&lt;?php
  </span><span class="comment"># our callback
  </span><span class="keyword">function </span><span class="default">readable</span><span class="keyword">(</span><span class="default">$stream</span><span class="keyword">, </span><span class="default">$mask</span><span class="keyword">, </span><span class="default">$arg</span><span class="keyword">) {
     if (</span><span class="default">$mask </span><span class="keyword">&#38; </span><span class="default">EV_READ</span><span class="keyword">) {
       echo </span><span class="string">"$arg is readable:\\n"</span><span class="keyword">;
       echo </span><span class="default">fread</span><span class="keyword">(</span><span class="default">$stream</span><span class="keyword">, </span><span class="default">8192</span><span class="keyword">);
       </span><span class="default">fwrite</span><span class="keyword">(</span><span class="default">$stream</span><span class="keyword">, </span><span class="string">"QUIT\\r\\n"</span><span class="keyword">);
       </span><span class="default">fclose</span><span class="keyword">(</span><span class="default">$stream</span><span class="keyword">);
     }
  }
  </span><span class="comment"># detect and activate the best available scheduling engine  
  </span><span class="default">event_init</span><span class="keyword">();
  </span><span class="comment"># create a new event
  </span><span class="default">$e </span><span class="keyword">= </span><span class="default">event_new</span><span class="keyword">(
       </span><span class="comment"># associate it with an async connection to a gmail MX
       </span><span class="default">stream_socket_client</span><span class="keyword">(</span><span class="string">"tcp://gsmtp171.google.com:25"</span><span class="keyword">,
          </span><span class="default">$errno</span><span class="keyword">, </span><span class="default">$errstr</span><span class="keyword">, </span><span class="default">0</span><span class="keyword">,
          </span><span class="default">STREAM_CLIENT_CONNECT</span><span class="keyword">|</span><span class="default">STREAM_CLIENT_ASYNC_CONNECT</span><span class="keyword">),
       </span><span class="comment"># ask the engine to tell us when it is ready to read
       </span><span class="default">EV_READ</span><span class="keyword">,
       </span><span class="comment"># tell it to call the 'readable' function when triggered
       </span><span class="string">'readable'</span><span class="keyword">,
       </span><span class="comment"># and pass in the following string as an argument
       </span><span class="string">'gmail mx'</span><span class="keyword">);
  </span><span class="comment"># put the event into the scheduling engine   
  </span><span class="default">event_schedule</span><span class="keyword">(</span><span class="default">$e</span><span class="keyword">);
  </span><span class="comment"># similarly, create another event, this time connecting
  # to the PHP MX 
  </span><span class="default">$e </span><span class="keyword">= </span><span class="default">event_new</span><span class="keyword">(
       </span><span class="default">stream_socket_client</span><span class="keyword">(</span><span class="string">"tcp://osu1.php.net:25"</span><span class="keyword">,
          </span><span class="default">$errno</span><span class="keyword">, </span><span class="default">$errstr</span><span class="keyword">, </span><span class="default">0</span><span class="keyword">,
          </span><span class="default">STREAM_CLIENT_CONNECT</span><span class="keyword">|</span><span class="default">STREAM_CLIENT_ASYNC_CONNECT</span><span class="keyword">),
       </span><span class="default">EV_READ</span><span class="keyword">,
       </span><span class="string">'readable'</span><span class="keyword">,
       </span><span class="string">'php mx'</span><span class="keyword">);
  </span><span class="default">event_schedule</span><span class="keyword">(</span><span class="default">$e</span><span class="keyword">);
  </span><span class="comment"># now service all registered events
  </span><span class="default">event_dispatch</span><span class="keyword">();
  </span><span class="comment"># we get here when both events have been run
</span><span class="default">?&gt;
</span></pre><p>If you run this script, you should see it output something like this:   </p>
<pre>  gmail mx is readable:
  220 mx.gmail.com ESMTP 71si267099rna
  php mx is readable:
  220-PHP  is a widely-used general-purpose scripting language
  220-that is especially suited for Web development and can be
  220-embedded into HTML. If you are new to PHP and want to get
  220-some idea of how it works, try the introductory tutorial.
  220-After that, check out the online manual, and the example
  220-archive sites and some of the other resources available in
  220-the links section.
  220-
  220-Do not send UBE to this server.
  220-
  220 osu1.php.net ESMTP Ecelerity HEAD (r3928) Mon, 13 Dec 2004 17:59:20 -0800
</pre><p>You'll see the osu1 banner a couple of seconds after running the script, because that server deliberately pauses.   </p>
<p><b>What's the point?</b>   </p>
<p>The script above connects to two SMTP servers, reads their banners and then gracefully closes the connection.  So what?  Big deal.  I can write a much shorter script that does the same thing using just a couple of lines for each host:   </p>
<pre class="phpcode"><span class="default">&lt;?php
   $fp </span><span class="keyword">= </span><span class="default">fsockopen</span><span class="keyword">(</span><span class="string">'gsmtp171.google.com'</span><span class="keyword">, </span><span class="default">25</span><span class="keyword">);
   echo </span><span class="default">fgets</span><span class="keyword">(</span><span class="default">$fp</span><span class="keyword">);
   </span><span class="default">fwrite</span><span class="keyword">(</span><span class="default">$fp</span><span class="keyword">, </span><span class="string">"QUIT\\r\\n"</span><span class="keyword">);
   </span><span class="default">fclose</span><span class="keyword">(</span><span class="default">$fp</span><span class="keyword">);
   </span><span class="default">$fp </span><span class="keyword">= </span><span class="default">fsockopen</span><span class="keyword">(</span><span class="string">'osu1.php.net'</span><span class="keyword">, </span><span class="default">25</span><span class="keyword">);
   echo </span><span class="default">fgets</span><span class="keyword">(</span><span class="default">$fp</span><span class="keyword">);
   </span><span class="default">fwrite</span><span class="keyword">(</span><span class="default">$fp</span><span class="keyword">, </span><span class="string">"QUIT\\r\\n"</span><span class="keyword">);
   </span><span class="default">fclose</span><span class="keyword">(</span><span class="default">$fp</span><span class="keyword">);
</span><span class="default">?&gt;
</span></pre><p>That may be the case, but with the PECL::event approach, you're not blocking on each connection (remember, osu1 makes you wait a couple of seconds) and you're not blocking on each read.  In fact, you could happily add 10s or perhaps even 100s of machines to monitor and have them all happen concurrently.  Neat stuff, huh?   </p>
<p>That's all I'm going to write for now, but before I go I'll leave you with a couple of interesting snippets if you're interested in playing with the extension.   </p>
<p><ul>
<li>Events are automatically descheduled after they have run once.  To get them to run again, you need to either call event_schedule() again or set the EV_PERSIST flag in your event mask when you call event_new().  You can manually event_deschedule() a persistent event to take it out of the scheduling engine (effectively pausing the event).
   </ul>
<p><ul>
<li>You can specify a deadline (well, a timeout) for an event when you schedule it; the 2nd and 3rd parameters are seconds and microseconds for the timeout.
   </ul>
<p><ul>
<li>You can call event_new() with a null stream parameter to create an event that is not associated with a stream.  event_schedule() it using a timeout to have that function called after a certain amount of elapsed time.
   </ul>
<p><ul>
<li>The extension is still beta; docs are pending and bug reports are welcome ;)
   </ul>
