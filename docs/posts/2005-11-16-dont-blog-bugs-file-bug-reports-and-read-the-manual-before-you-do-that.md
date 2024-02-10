---
layout: post
title: "Don't blog bugs, file bug reports (and read the manual before you do that)"
date: 2005-11-16 06:23:38

uniqid: 437ad06a-2e9a-3727-5160-37ad06a16aca
categories: 
 - PHP
---
<p>Well, I'm disappointed again by <a href="http://phplens.com/phpeverywhere/?q=node/view/209">John Lim's continual</a> <a href="http://phplens.com/phpeverywhere/?q=node/view/217">lack of a decent bug report</a>; claiming that you lack the time while having had time to post two blog entries about it is pretty poor.   </p>
<p>Let's see the code you're using to call into PDO, John; there's not much time before we release, and without your cooperation, the problems you're seeing won't get addressed.   </p>
<p>I'll speculate that John's ADOdb snippet:   </p>
<pre class="phpcode"><span class="default">&lt;?php
   $rs </span><span class="keyword">= </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">Execute</span><span class="keyword">(</span><span class="string">"select * from table where a=? and b=?"</span><span class="keyword">,array(</span><span class="string">'a'</span><span class="keyword">=&gt;</span><span class="default">1</span><span class="keyword">,</span><span class="string">'b'</span><span class="keyword">=&gt;</span><span class="default">2</span><span class="keyword">));
 </span><span class="default">?&gt;
</span></pre><p>is trying to bind 'a' and 'b' by parameter names, but the parameters in his query are identified only by their positions (using question marks).  I wonder how that is supposed to work?  Maybe he should try this:   </p>
<pre class="phpcode"><span class="default">&lt;?php
   $rs </span><span class="keyword">= </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">Execute</span><span class="keyword">(</span><span class="string">"select * from table where a=:a and b=:b"</span><span class="keyword">,array(</span><span class="string">'a'</span><span class="keyword">=&gt;</span><span class="default">1</span><span class="keyword">,</span><span class="string">'b'</span><span class="keyword">=&gt;</span><span class="default">2</span><span class="keyword">));
 </span><span class="default">?&gt;
</span></pre><p>It's also worth noting (again) that <a href="http://www.php.net/manual/en/function.pdostatement-getcolumnmeta.php">PDOStatement::getColumnMeta</a> is intentionally unimplemented on this first PDO release, hence its experimental status; in other words, don't use it.  Most people don't need this kind of feature anyway.   </p>
