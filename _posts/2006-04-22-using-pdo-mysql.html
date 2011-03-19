---
layout: post
title: "Using PDO::MYSQL ?"
date: 2006-04-22 16:55:51

uniqid: 444a6017-0548-2459-2943-44a601714d58
categories: 
 - PHP
---
<p>I've recently discovered a few things about how the mysql client library does things that seem a bit silly to me, so I'm going to share them with you.   </p>
<p><ul>
<li>native prepared statements cannot take advantage of the query cache, resulting in lower performance.
 <li>native prepared statements cannot execute certains types of queries, like &quot;SHOW TABLES&quot;
 <li>native prepared statements don't correctly communicate column lengths for certain other &quot;SHOW&quot; queries, resulting in garbled results.
 <li>calling stored procedures multiple times using native prepared statements causes the connection to drop.
   </ul>
<p>I recommend that you use the following attribute when working with PDO::MYSQL, available in the current PHP 5.1.3 release candidates and snapshots:   </p>
<pre class="phpcode">$db-&gt;setAttribute(PDO::ATTR_EMULATE_PREPARES, true);</pre><p>This causes the PDO native query parser to be used instead of the native prepared statements APIs in the mysql client, and effectively eliminates those problems.   </p>
<p>I'll admit that the last point could well be a bug in my code; since I'll be at the MySQL Users Conference next week, I should be able to sit down with the right people and fix it.   </p>
