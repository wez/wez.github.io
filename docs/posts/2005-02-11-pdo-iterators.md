---
layout: post
title: "PDO: Iterators"
date: 2005-02-11 18:13:36

uniqid: 420ccd50-adb9-5159-5209-20ccd507e106
categories: 
 - PHP
---
<p>[Updated to fix missing execute()]   </p>
<p>Louis-Philippe Huberdeau posted this comment to an earlier post, asking:   </p>
<p><i> I have been playing with PDO this morning, looks great! I just wonder if it could use a few more PHP5 features and SPL kind of thing. </i>  </p>
<p>The gist of what he was suggesting is already present.  His first request was to be able to access the columns as properties of the statement handle.  While you can't do that exactly, you can do something similar.  His second request was being able to iterate the rows using foreach().  Here are two ways to acheive that, using alternative syntax:   </p>
<pre class="phpcode"><span class="default">&lt;?php
   $stmt </span><span class="keyword">= </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">prepare</span><span class="keyword">(</span><span class="string">"select foo from bar"</span><span class="keyword">);
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">execute</span><span class="keyword">();
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">setFetchMode</span><span class="keyword">(</span><span class="default">PDO_FETCH_LAZY</span><span class="keyword">);
   foreach (</span><span class="default">$stmt </span><span class="keyword">as </span><span class="default">$row</span><span class="keyword">) {
       echo </span><span class="default">$row</span><span class="keyword">-&gt;</span><span class="default">foo</span><span class="keyword">;
   }
</span><span class="default">?&gt;
</span></pre><p>and the less verbose version:   </p>
<pre class="phpcode"><span class="default">&lt;?php
    </span><span class="keyword">foreach (</span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">query</span><span class="keyword">(</span><span class="string">"select foo from bar"</span><span class="keyword">, </span><span class="default">PDO_FETCH_LAZY</span><span class="keyword">) as </span><span class="default">$row</span><span class="keyword">) {
        echo </span><span class="default">$row</span><span class="keyword">-&gt;</span><span class="default">foo</span><span class="keyword">;
    }
</span><span class="default">?&gt;
</span></pre><p>PDO_FETCH_LAZY is almost exactly the same thing as having the properties show up on the statement handle; the &quot;lazy object&quot; you get on each iteration is internally aliased to the statement handle.  If you're familiar with the concept of interfaces from other languages, the lazy object is actually the same object instance, it's just a different interface.   </p>
<p>The implication of this is that you can't simply copy a $row into another variable and expect its contents to remain as they were at that point; $row still references the current active row of the statement, so if you fetch() another row (either direct or by advancing the iteration with foreach) $row will refer to the new row.   </p>
<p>PDO_FETCH_LAZY doesn't mean that you can be lazy, it means that PDO can be lazy. :-)   </p>
<p>If you want to be lazy, and still want $row-&gt;foo, use PDO_FETCH_OBJ instead; the sample above will work the same.   </p>
