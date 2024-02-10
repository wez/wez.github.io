---
layout: post
title: "LOB support added to PDO_OCI in PHP 5.1 CVS (finally)"
date: 2005-10-31 18:37:23

uniqid: 4365876a-cee9-3009-7726-365876a51802
categories: 
 - PHP
---
<p>[update: corrected information about STRINGIFY_FETCHES]   </p>
<p>It's been a looong time coming, but it's finally here. Here's how to insert a BLOB via PDO_OCI:   </p>
<pre class="phpcode"><span class="default">&lt;?php
   $db </span><span class="keyword">= new </span><span class="default">PDO</span><span class="keyword">(</span><span class="string">"oci:"</span><span class="keyword">, </span><span class="string">"scott"</span><span class="keyword">, </span><span class="string">"tiger"</span><span class="keyword">);
   </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">beginTransaction</span><span class="keyword">(); </span><span class="comment">// Essential!
   </span><span class="default">$stmt </span><span class="keyword">= </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">prepare</span><span class="keyword">(
       </span><span class="string">"INSERT INTO blobtest (id, contenttype, blob) "</span><span class="keyword">.
       </span><span class="string">"VALUES (:id, :type, EMPTY_BLOB()) "</span><span class="keyword">.
       </span><span class="string">"RETURNING blob INTO :blob"</span><span class="keyword">);
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">bindParam</span><span class="keyword">(</span><span class="string">':id'</span><span class="keyword">, </span><span class="default">$id</span><span class="keyword">);
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">bindParam</span><span class="keyword">(</span><span class="string">':type'</span><span class="keyword">, </span><span class="default">$type</span><span class="keyword">);
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">bindParam</span><span class="keyword">(</span><span class="string">':blob'</span><span class="keyword">, </span><span class="default">$blob</span><span class="keyword">, </span><span class="default">PDO</span><span class="keyword">::</span><span class="default">PARAM_LOB</span><span class="keyword">);
   </span><span class="default">$type </span><span class="keyword">= </span><span class="string">'image/gif'</span><span class="keyword">;
   </span><span class="default">$id </span><span class="keyword">= </span><span class="default">1</span><span class="keyword">; </span><span class="comment">// generate your own unique id here
   </span><span class="default">$blob </span><span class="keyword">= </span><span class="default">fopen</span><span class="keyword">(</span><span class="string">'/path/to/a/graphic.gif'</span><span class="keyword">, </span><span class="string">'rb'</span><span class="keyword">);
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">execute</span><span class="keyword">();
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">commit</span><span class="keyword">();
   </span><span class="default">?&gt;
</span></pre><p>This will suck the contents of the graphic.gif up and store it into the newly inserted row.  This syntax most closely matches the generic blob insert syntax that I talk about in my PDO presentation, there are two differences that are peculiar to Oracle.  The first is the <b>RETURNING blob INTO :blob</b> that's tacked onto the end of the INSERT query.  The reason for this is that Oracle stores &quot;LOB Locators&quot; rather than LOB contents in its table rows.   </p>
<p>A LOB Locator tells Oracle where it stashed the actual LOB contents without making the table rows overly large, and allows some clever optimizations when manipulating LOBs.  You can't just conjure up a LOB Locator though, so you need to insert a brand new empty LOB into a table and then fetch it's locator back out before you can start modifying it. (If you're coming from a mysql background, you can think of the locator as being something like a mysql auto-increment field; you need to insert a row before you find out what the value of the field is.)   </p>
<p>Rather than issuing 2 queries just to make an insert, Oracle provides the <b>RETURNING ... INTO</b> syntax as a shortcut; it's equivalent to SELECTing the columns back out again, but it bundled up into a single query, saving the effort of parsing multiple queries and the overhead of multiple network round-trips to get everything where it needs to be.   </p>
<p>This means that the <b>:blob</b> parameter is actually an output parameter, even though it smells like an input parameter.  There's some intuitive magic at work here; if you bind a <b>stream</b> or a <b>string</b> to a <b>PDO::PARAM_LOB</b> parameter, the PDO_OCI driver will assume that you want to store the contents of that stream-or-string into the LOB that gets returned after the execute.  So, that's what it does.  Post-execute, all the LOB parameters are checked to see what PHP-space variables were bound, and data is written to the LOBs.   This has an important implication; if you're doing this, you'd better have a transaction open, otherwise your new LOBs will be committed as part of the execute--before the PDO_OCI driver can write the data into the LOBs.   </p>
<p>So, we can insert just fine.  What about binding a LOB for output?  Here's how:   </p>
<pre class="phpcode"><span class="default">&lt;?php
   $db </span><span class="keyword">= new </span><span class="default">PDO</span><span class="keyword">(</span><span class="string">"oci:"</span><span class="keyword">, </span><span class="string">"scott"</span><span class="keyword">, </span><span class="string">"tiger"</span><span class="keyword">);
   </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">beginTransaction</span><span class="keyword">(); </span><span class="comment">// Essential!
   </span><span class="default">$stmt </span><span class="keyword">= </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">prepare</span><span class="keyword">(
       </span><span class="string">"INSERT INTO blobtest (id, contenttype, blob) "</span><span class="keyword">.
       </span><span class="string">"VALUES (:id, :type, EMPTY_BLOB()) "</span><span class="keyword">.
       </span><span class="string">"RETURNING blob INTO :blob"</span><span class="keyword">);
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">bindParam</span><span class="keyword">(</span><span class="string">':id'</span><span class="keyword">, </span><span class="default">$id</span><span class="keyword">);
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">bindParam</span><span class="keyword">(</span><span class="string">':type'</span><span class="keyword">, </span><span class="default">$type</span><span class="keyword">);
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">bindParam</span><span class="keyword">(</span><span class="string">':blob'</span><span class="keyword">, </span><span class="default">$blob</span><span class="keyword">, </span><span class="default">PDO</span><span class="keyword">::</span><span class="default">PARAM_LOB</span><span class="keyword">);
   </span><span class="default">$type </span><span class="keyword">= </span><span class="string">'image/gif'</span><span class="keyword">;
   </span><span class="default">$id </span><span class="keyword">= </span><span class="default">1</span><span class="keyword">; </span><span class="comment">// generate your own unique id here
   </span><span class="default">$blob </span><span class="keyword">= </span><span class="default">null</span><span class="keyword">;
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">execute</span><span class="keyword">();
   </span><span class="comment">// now $blob is a stream
   </span><span class="default">fwrite</span><span class="keyword">(</span><span class="default">$blob</span><span class="keyword">, </span><span class="string">"GIF89a"</span><span class="keyword">);
   ...
   </span><span class="default">fclose</span><span class="keyword">(</span><span class="default">$blob</span><span class="keyword">);
   </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">commit</span><span class="keyword">();
   </span><span class="default">?&gt;
</span></pre><p>OK, this sample is still inserting data into the LOB, but it's doing it by binding the LOB for output, and giving you access to the LOB stream so that you can manually do things with it.  The same approach will work if you issue a query that returns an existing read-only LOB.  This syntax is closer to the traditional oci8 extension LOB support, except that the LOB is mapped as a PHP stream, so that you can use all the usual PHP streams functions to work with it.   </p>
<p>LOBs are also handled for simple SELECTs that return rows with LOB columns.  The PDO_OCI driver returns each LOB column as a stream; no data is transferred from that column until you start to read from it:   </p>
<pre class="phpcode"><span class="default">&lt;?php
    $stmt </span><span class="keyword">= </span><span class="default">$db</span><span class="keyword">-&gt;</span><span class="default">prepare</span><span class="keyword">(</span><span class="string">'select blob from blobtest where id = ?'</span><span class="keyword">);
    </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">execute</span><span class="keyword">(array(</span><span class="default">$id</span><span class="keyword">));
    </span><span class="default">$row </span><span class="keyword">= </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">fetch</span><span class="keyword">();
    </span><span class="default">var_dump</span><span class="keyword">(</span><span class="default">$row</span><span class="keyword">);
    </span><span class="default">var_dump</span><span class="keyword">(</span><span class="default">stream_get_contents</span><span class="keyword">(</span><span class="default">$row</span><span class="keyword">[</span><span class="default">0</span><span class="keyword">]));
    </span><span class="default">?&gt;
</span></pre><p>this will output something like:   </p>
<pre>    array(2) {
      [&quot;BLOB&quot;]=&gt;
      resource(7) of type (stream)
      [0]=&gt;
      resource(7) of type (stream)
    }
    string(886) &quot;.....BLOBDATAHERE...&quot;
</pre><p>Notice that I'm using stream_get_contents() to transform the LOB stream into a string.  If you're writing a portable application (good luck!) you need to be prepared to handle columns coming back as a stream, even if you didn't explicitly bindColumn and ask for it to be delivered as a LOB.  If you're not looking forward to handling that dynamically, you might be interested in setting the <b>STRINGIFY_FETCHES</b> database attribute:   </p>
<pre class="phpcode"><span class="default">&lt;?php
    $db</span><span class="keyword">-&gt;</span><span class="default">setAttribute</span><span class="keyword">(</span><span class="default">PDO</span><span class="keyword">::</span><span class="default">ATTR_STRINGIFY_FETCHES</span><span class="keyword">, </span><span class="default">true</span><span class="keyword">);
    </span><span class="comment">// now ALL non-NULL columns will be converted to strings when fetched
    </span><span class="default">?&gt;
</span></pre><p>This will convert all columns to strings, regardless of their original type, when fetches.  This does not include NULL columns.  This does NOT translate data being inserted.  Use your brain before deploying this setting!  </p>
