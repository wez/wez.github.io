---
layout: post
title: "Background/batch/workflow processing with PDO::PGSQL"
date: 2006-10-26 17:19:34

uniqid: 4540ee26-341a-4915-5120-540ee264841f
categories: 
 - PHP
---
<p>One of the other things I've been looking it as ways to implement background processing in PHP.  In my recent talk on <a href="http://images.omniti.net/omniti.com/talks/furlong-best-mailing-practices.pdf">sending mail from php</a> I mention that you want to avoid sending mail directly from a web page.  A couple of people have asked me how to implement that, and one of the suggestions I have is to queue your mail in a database table and have some other process act on that table.   </p>
<p>The idea is that you have a PHP CLI script that, in an infinite loop, sleeps for a short time then polls the database to see if it needs to do some work.  While that will work just fine, wouldn't it be great if the database woke you up only when you needed to do some work?   </p>
<p>I've been working on a patch originally contributed by David Begley that adds support for LISTEN/NOTIFY processing to the Postgres PDO driver. With the patch you can write a CLI script that looks a bit like this:   </p>
<pre class="phpcode"><span class="default">&lt;?php
   $db </span><span class="keyword">= new </span><span class="default">PDO</span><span class="keyword">(</span><span class="string">'pgsql:'</span><span class="keyword">);
   </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">exec</span><span class="keyword">(</span><span class="string">'LISTEN work'</span><span class="keyword">);
   </span><span class="default">dispatch_work</span><span class="keyword">();
   while (</span><span class="default">true</span><span class="keyword">) {
      if (</span><span class="default">is_array</span><span class="keyword">(</span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">pgsqlGetNotify</span><span class="keyword">(</span><span class="default">PDO</span><span class="keyword">::</span><span class="default">FETCH_NUM</span><span class="keyword">, </span><span class="default">360</span><span class="keyword">))) {
          </span><span class="default">dispatch_work</span><span class="keyword">();
      }
   }
</span><span class="default">?&gt;
</span></pre><p>This script will effectively sleep for 360 seconds, or until someone else issues a 'NOTIFY work' query against the database, like this:   </p>
<pre class="phpcode"><span class="default">&lt;?php
   $db</span><span class="keyword">-&gt;</span><span class="default">beginTransaction</span><span class="keyword">();
   </span><span class="default">$q </span><span class="keyword">= </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">prepare</span><span class="keyword">(</span><span class="string">'insert into work(...) values (...)'</span><span class="keyword">);
   </span><span class="default">$q</span><span class="keyword">-&gt;</span><span class="default">execute</span><span class="keyword">(</span><span class="default">$params</span><span class="keyword">);
   </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">exec</span><span class="keyword">(</span><span class="string">'NOTIFY work'</span><span class="keyword">);
   </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">commit</span><span class="keyword">();
</span><span class="default">?&gt;
</span></pre><p>When the transaction commits, the CLI script will wake up and return an array containing 'work' and a process id; the script will then call dispatch_work() which is some function that queries the database to find out exactly what it needs to do, and then does it.   </p>
<p>This technique allows you to save CPU resources on the database server by avoiding repeated queries against the server.  The classic polling overhead trade-off is to increase the time interval between polls at the cost of increased latency.  The LISTEN/NOTIFY approach is vastly superior; you do zero work until the database wakes you up to do it--and it wakes you up almost immediately after the NOTIFY statement is committed.  The transactional tie-in is nice; if something causes your insert to be rolled back, your NOTIFY will roll-back too.   </p>
<p>Once PHP 5.2.0 is out the door (it's too late to sneak it into the release candidate), you can expect to see a PECL release of PDO::PGSQL with this feature.   </p>
