---
layout: post
title: "Benchmarking (in general)"
date: 2005-09-23 03:25:11

uniqid: 433372a3-ee47-8213-9058-33372a3f2171
categories: 
 - PHP
---
<p>I just wanted to follow up on <a href="http://pixelated-dreams.com/index.php?url=archives/177-Extending-PDO-a-waste....html">Davey's post about extending PDO</a>, because I think he isn't being very clear about what he's doing (no offence, Davey!)   </p>
<p>Davey's Cortex framework allows you to pass in either a DSN or an already-instantiated-PDO object to its data object class, and Davey's post claims, quite rightly, that it is faster to not create brand new connections each time you create an instance of his framework objects.   </p>
<p>Let's see if we can come up with a slightly more scientific and perhaps more fair set of tests.   </p>
<p>When benchmarking it's important to get some decent numbers.  If your test is over in less than 1 second, your readings are probably wildly inaccurate because your system may not have had a chance to properly utilize hardware or OS level caches or otherwise adjust in the same way that it would be doing under a consistent level of load.   </p>
<p>If you're running a quick test, try to make it last more than 10 seconds.   If you want to get a better numbers, run the test for longer; 5 or 10 minutes should give pretty decent results for a given code fragment, and an hour is probably as good as you could ever hope for.   </p>
<p>Here's a simple test harness that runs for approximately 5 minutes:   </p>
<pre class="phpcode"><span class="default">&lt;?php
    $start </span><span class="keyword">= </span><span class="default">time</span><span class="keyword">();
    </span><span class="default">$deadline </span><span class="keyword">= </span><span class="default">$start </span><span class="keyword">+ (</span><span class="default">5 </span><span class="keyword">* </span><span class="default">60</span><span class="keyword">); </span><span class="comment">// run for 5 minutes
    </span><span class="default">$iters </span><span class="keyword">= </span><span class="default">0</span><span class="keyword">;
    do {    
      </span><span class="default">something</span><span class="keyword">();
      ++</span><span class="default">$iters</span><span class="keyword">;
    } while (</span><span class="default">time</span><span class="keyword">() &lt;= </span><span class="default">$deadline</span><span class="keyword">);
    </span><span class="default">$end </span><span class="keyword">= </span><span class="default">time</span><span class="keyword">();
    </span><span class="default">$diff </span><span class="keyword">= </span><span class="default">$end </span><span class="keyword">- </span><span class="default">$start</span><span class="keyword">;
    </span><span class="default">printf</span><span class="keyword">(</span><span class="string">"Ran %.2f iterations per minute (%d/%d)\\n"</span><span class="keyword">,
        (</span><span class="default">60.0 </span><span class="keyword">* </span><span class="default">$iters</span><span class="keyword">) / </span><span class="default">$diff</span><span class="keyword">, </span><span class="default">$iters</span><span class="keyword">, </span><span class="default">$diff</span><span class="keyword">);
    </span><span class="default">?&gt;
</span></pre><p>This harness simply repeats a task until the time limit is more or less up, and then summarizes how many times it managed to run within the specified time, normalizing it to iterations per minute.   </p>
<p>Notice that I'm not particular bothered about sub-second time intervals here, because they don't really have much impact when compared to a 5 minute time duration--5 minutes plus or minus half a second is still near as damn it 5 minutes.   </p>
<p>Our first test creates some kind of object that does some kind of work on a database connection.  We'll make this one re-connect to the database each time; this is equivalent to Davey's extending PDO case:   </p>
<pre class="phpcode"><span class="default">&lt;?php
   </span><span class="comment">// represents some object in your framework
   </span><span class="keyword">class </span><span class="default">TestObject </span><span class="keyword">{
      var </span><span class="default">$db</span><span class="keyword">;
      function </span><span class="default">__construct</span><span class="keyword">(</span><span class="default">$db</span><span class="keyword">) {
         </span><span class="default">$this</span><span class="keyword">-&gt;</span><span class="default">db </span><span class="keyword">= </span><span class="default">$db</span><span class="keyword">;
      }
      function </span><span class="default">doWork</span><span class="keyword">() {
         </span><span class="comment"># Limited to 100 rows, because the connection cost
         # will be lost in the noise of the fetch otherwise
         </span><span class="default">array_reverse</span><span class="keyword">(</span><span class="default">$this</span><span class="keyword">-&gt;</span><span class="default">db</span><span class="keyword">-&gt;</span><span class="default">query</span><span class="keyword">(</span><span class="string">"select * from words LIMIT 100"</span><span class="keyword">)-&gt;</span><span class="default">fetchAll</span><span class="keyword">());
      }
   }
   function </span><span class="default">something</span><span class="keyword">() {
       </span><span class="default">$db </span><span class="keyword">= new </span><span class="default">PDO</span><span class="keyword">(</span><span class="default">$dsn</span><span class="keyword">, </span><span class="default">$user</span><span class="keyword">, </span><span class="default">$pass</span><span class="keyword">);
       </span><span class="default">$obj </span><span class="keyword">= new </span><span class="default">TestObject</span><span class="keyword">(</span><span class="default">$db</span><span class="keyword">);
       </span><span class="default">$obj</span><span class="keyword">-&gt;</span><span class="default">doWork</span><span class="keyword">();
   }
   </span><span class="default">?&gt;
</span></pre><p>The next test uses the same test object class, but caches the PDO instance.  This is equivalent to Davey's <i></i>call proxying case:   </p>
<pre class="phpcode"><span class="default">&lt;?php
   </span><span class="keyword">function </span><span class="default">something</span><span class="keyword">() {
       static </span><span class="default">$db </span><span class="keyword">= </span><span class="default">null</span><span class="keyword">;
       if (</span><span class="default">$db </span><span class="keyword">=== </span><span class="default">null</span><span class="keyword">) </span><span class="default">$db </span><span class="keyword">= new </span><span class="default">PDO</span><span class="keyword">(</span><span class="default">$dsn</span><span class="keyword">, </span><span class="default">$user</span><span class="keyword">, </span><span class="default">$pass</span><span class="keyword">);
       </span><span class="default">$obj </span><span class="keyword">= new </span><span class="default">TestObject</span><span class="keyword">(</span><span class="default">$db</span><span class="keyword">);
       </span><span class="default">$obj</span><span class="keyword">-&gt;</span><span class="default">doWork</span><span class="keyword">();
   }
   </span><span class="default">?&gt;
</span></pre><p>The third test uses persistent connections; this is equivalent to Davey's extending PDO case, but &quot;smarter&quot;; even though $db falls out of scope and is destroyed at the end of each call to the something() function, the underlying connection is cached so that subsequent calls don't need to re-connect.  This is transparent to the calling script, except for the extra parameter to the constructor, and is generally a very good thing to do with database connections:   </p>
<pre class="phpcode"><span class="default">&lt;?php
   </span><span class="keyword">function </span><span class="default">something</span><span class="keyword">() {
       </span><span class="default">$db </span><span class="keyword">= new </span><span class="default">PDO</span><span class="keyword">(</span><span class="default">$dsn</span><span class="keyword">, </span><span class="default">$user</span><span class="keyword">, </span><span class="default">$pass</span><span class="keyword">, array(</span><span class="default">PDO</span><span class="keyword">::</span><span class="default">ATTR_PERSISTENT </span><span class="keyword">=&gt; </span><span class="default">true</span><span class="keyword">));
       </span><span class="default">$obj </span><span class="keyword">= new </span><span class="default">TestObject</span><span class="keyword">(</span><span class="default">$db</span><span class="keyword">);
       </span><span class="default">$obj</span><span class="keyword">-&gt;</span><span class="default">doWork</span><span class="keyword">();
   }
   </span><span class="default">?&gt;
</span></pre><p>Here are the results I got; since I'm lazy I'm only running mine for about 30 seconds each.  I used a sqlite database with the contents of /usr/share/dict/words inserted into it (234937 words).   </p>
<pre>   one:   Ran 46734.19 iterations per minute (24146/31)
   two:   Ran 68504.52 iterations per minute (35394/31)
   three: Ran 64689.68 iterations per minute (33423/31)
</pre><p>The results speak for themselves; if you're initiating connections every time to you want to do some work, it's the slowest.  If you cache the connection in a PHP variable it's faster than making persistent connections to PDO, because it doesn't need to create a new object each time.  Persistent connections are &quot;almost&quot; as fast as caching in PHP variables; they need to create a new object but still reference the same connection internally.   </p>
<p>It's work mentioning that benchmarks are tricky things.  For instance, if you take out the &quot;LIMIT 100&quot; clause from the SELECT statement, the connection overhead time becomes so small in comparison to the time it takes to fetch the data, that all the tests wind up the same (about 18 iterations per minute with my test data).  Similarly, if you limit the fetch to 1 row, you'll see a more exaggerated difference in the numbers, because the benchmark script is exercising your system differently.   </p>
<p>If you're running against mysql, the differences between test one and test two will be greater because there is more overhead in establishing a connection over a socket than there is for sqlite to open up a file or two.  You'll see a bigger difference again when connection to Oracle, because it does a pretty hefty amount of work at connection time.   </p>
<p>The main lesson to be learned here is that benchmarking an artifical code fragment will give you artificial results; they can help you guage how fast something will run in general, but the answers you get back depend on the questions you're asking.  If you don't ask appropriate or even enough questions (eg: Davey's quick tests didn't include persistent connections), you're not going to get all the information you need to tune your application effectively.   </p>
<p>PS: There's a fourth test case that I didn't cover; it's important and probably yields the best results out of all the cases presented here.  Anyone care to suggest what that case might be?   </p>
<p>  </p>
