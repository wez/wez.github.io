---
layout: post
title: "Undefined behaviour"
date: 2006-04-28 23:56:41

uniqid: 4452abb9-3abd-5432-6132-452abb9264e9
categories: 
 - PHP
---
<p>or, why I find the Solaris manual pages amusing.   </p>
<p>In <a href="http://news.php.net/php.internals/22994">this</a> <a href="http://news.php.net/php.internals/23000">thread</a> <a href="http://news.php.net/php.internals/23001">on the</a> <a href="http://news.php.net/php.internals/23002">php internals</a> list, Kevin is asking why the handling of whitespace in certain PDO DSNs is inconsistent. I go on to point out that the manual doesn't say anything about whitespace in DSNs, and that all the documented examples have no whitespace around the DSN parameters.   </p>
<p>His response was:   </p>
<pre>   but in this example we have a space and it works
   mysql: host = localhost; dbname=test
</pre><p>And another that I got via private email:   </p>
<pre>   But it doesn't tell me I should avoid spaces either.
</pre><p>This is an example of undefined behaviour.  The PHP manual doesn't define what happens when you put whitespace in there.  That doesn't tell you anything at all about whether you should or should not do that.  It might work now, and it might work next week.  In 6 months time, when you application is widely deployed and someone changes an apparently unrelated part of their system, it might NOT work and might result in someone getting paged at 3am trying to figure out what the mysterious problem is.   </p>
<p>If you take a look at the Solaris manual pages you'll see evidence that the folks at Sun have run into people reporting problems with undefined behaviour.  My favourite so far is in one of the pthread manual pages where it describes in one short paragraph what a particular function does, and then proceeds to describe a very large number of use cases that are undefined, along the lines of:   </p>
<pre>   If you do X, then the behaviour is undefined.
   If you do Y, then the behaviour is undefined.
   If you do Z, then the behaviour is undefined.
</pre><p>and so on for a couple of pages.  This is an example of really good documentation; it only really needs to tell you how to use it, and assuming that people read the manual and follow the examples, they shouldn't run into problems.  Explicitly stating what constitutes undefined behaviour is a sign that the documentor has good technical understanding of the routine and, almost certainly, too much past experience with tracking down problem reports from people that are misusing the API.   </p>
<p>While really good documentation for everything would be nice to have, it's not always available.  In circumstances where the documentation doesn't tell you about what happens when you do a particular thing, you should treat it as though the documentation says &quot;If you do FOO, then the behaviour is undefined&quot;.  If you need to find out a definitive answer, contact the author/vendor and ask them.  When they give you an answer, <b>listen to them</b>.  They've said what they've said for a reason.   </p>
