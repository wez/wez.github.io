---
layout: post
title: "First steps with PDO"
date: 2004-05-22 20:51:36

uniqid: bf0a58d7-68ab-49d0-9a29-f5ec97c5dfde
categories: 
 - PHP
---
<p>Developing PDO and releasing an alpha has sparked a lot of interest already (probably helped along by <a href="http://www.schlossnagle.org/~george/blog/archives/260_PDO.html">George</a> ;-)) and we got our first &quot;how does it work&quot; e-mail today.  As it happens, I've already written a intro to PDO for the <a href="http://otn.oracle.com">OTN</a> but George informs me that they can take a while to publish.   </p>
<p>Meanwhile, to avoid being swamped by mail as the word gets out, here are a couple of sample PDO scripts to get you started.  Please keep in mind that it is alpha software (although still works pretty well) and requires PHP 5 from CVS (RC3 will work too, but that isn't released until next week).   </p>
<p>The API is &quot;grown-up&quot; by default; you get so called &quot;unbuffered&quot; result sets as standard and the prepare/bind/execute API is preferred, although there are some short-cuts already (and some more planned).  Note that you don't need to do any quoting manually using bound parameters; it is handled for you.  You do need to be careful with magic_quotes though (as always).   </p>
<pre class="phpcode"><span class="default">&lt;?php
$dbh </span><span class="keyword">= new </span><span class="default">PDO</span><span class="keyword">(</span><span class="string">'mysql:dbname=test;host=localhost'</span><span class="keyword">, </span><span class="default">$username</span><span class="keyword">, </span><span class="default">$password</span><span class="keyword">);
</span><span class="comment">// let's have exceptions instead of silence.
// other modes: PDO_ERRMODE_SILENT (default - check $stmt-&gt;errorCode() and $stmt-&gt;errorInfo())
//              PDO_ERRMODE_WARNING (php warnings)
</span><span class="default">$dbh</span><span class="keyword">-&gt;</span><span class="default">setAttribute</span><span class="keyword">(</span><span class="default">PDO_ATTR_ERRMODE</span><span class="keyword">, </span><span class="default">PDO_ERRMODE_EXCEPTION</span><span class="keyword">);
</span><span class="comment">// one-shot query
</span><span class="default">$dbh</span><span class="keyword">-&gt;</span><span class="default">exec</span><span class="keyword">(</span><span class="string">"create table test(name varchar(255) not null primary key, value varchar(255));"</span><span class="keyword">);
</span><span class="default">?&gt;
</span></pre><p><br />   </p>
<pre class="phpcode"><span class="default">&lt;?php
</span><span class="comment">// insert some data using a prepared statement
</span><span class="default">$stmt </span><span class="keyword">= </span><span class="default">$dbh</span><span class="keyword">-&gt;</span><span class="default">prepare</span><span class="keyword">(</span><span class="string">"insert into test (name, value) values (:name, :value)"</span><span class="keyword">);
</span><span class="comment">// bind php variables to the named placeholders in the query
// they are both strings that will not be more than 64 chars long
</span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">bindParam</span><span class="keyword">(</span><span class="string">':name'</span><span class="keyword">, </span><span class="default">$name</span><span class="keyword">, </span><span class="default">PDO_PARAM_STR</span><span class="keyword">, </span><span class="default">64</span><span class="keyword">);
</span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">bindParam</span><span class="keyword">(</span><span class="string">':value'</span><span class="keyword">, </span><span class="default">$value</span><span class="keyword">, </span><span class="default">PDO_PARAM_STR</span><span class="keyword">, </span><span class="default">64</span><span class="keyword">);
</span><span class="comment">// insert a record
</span><span class="default">$name </span><span class="keyword">= </span><span class="string">'Foo'</span><span class="keyword">;
</span><span class="default">$value </span><span class="keyword">= </span><span class="string">'Bar'</span><span class="keyword">;
</span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">execute</span><span class="keyword">();
</span><span class="comment">// and another
</span><span class="default">$name </span><span class="keyword">= </span><span class="string">'Fu'</span><span class="keyword">;
</span><span class="default">$value </span><span class="keyword">= </span><span class="string">'Ba'</span><span class="keyword">;
</span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">execute</span><span class="keyword">();
</span><span class="comment">// more if you like, but we're done
</span><span class="default">$stmt </span><span class="keyword">= </span><span class="default">null</span><span class="keyword">;
</span><span class="default">?&gt;
</span></pre><p><br />   </p>
<pre class="phpcode"><span class="default">&lt;?php
</span><span class="comment">// get some data out based on user input
</span><span class="default">$what </span><span class="keyword">= </span><span class="default">$_GET</span><span class="keyword">[</span><span class="string">'what'</span><span class="keyword">];
</span><span class="default">$stmt </span><span class="keyword">= </span><span class="default">$dbh</span><span class="keyword">-&gt;</span><span class="default">prepare</span><span class="keyword">(</span><span class="string">'select name, value from test where name=:what'</span><span class="keyword">);
</span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">bindParam</span><span class="keyword">(</span><span class="string">'what'</span><span class="keyword">, </span><span class="default">$what</span><span class="keyword">);
</span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">execute</span><span class="keyword">();
</span><span class="comment">// get the row using PDO_FETCH_BOTH (default if not specified as parameter)
// other modes: PDO_FETCH_NUM, PDO_FETCH_ASSOC, PDO_FETCH_OBJ, PDO_FETCH_LAZY, PDO_FETCH_BOUND
</span><span class="default">$row </span><span class="keyword">= </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">fetch</span><span class="keyword">();
</span><span class="default">print_r</span><span class="keyword">(</span><span class="default">$row</span><span class="keyword">);
</span><span class="default">$stmt </span><span class="keyword">= </span><span class="default">null</span><span class="keyword">;
</span><span class="default">?&gt;
</span></pre><p><br />   </p>
<pre class="phpcode"><span class="default">&lt;?php
</span><span class="comment">// get all data row by row
</span><span class="default">$stmt </span><span class="keyword">= </span><span class="default">$dbh</span><span class="keyword">-&gt;</span><span class="default">prepare</span><span class="keyword">(</span><span class="string">'select name, value from test'</span><span class="keyword">);
</span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">execute</span><span class="keyword">();
while (</span><span class="default">$row </span><span class="keyword">= </span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">fetch</span><span class="keyword">(</span><span class="default">PDO_FETCH_ASSOC</span><span class="keyword">)) {
    </span><span class="default">print_r</span><span class="keyword">(</span><span class="default">$row</span><span class="keyword">);
}
</span><span class="default">$stmt </span><span class="keyword">= </span><span class="default">null</span><span class="keyword">;
</span><span class="default">?&gt;
</span></pre><p><br />   </p>
<pre class="phpcode"><span class="default">&lt;?php
</span><span class="comment">// get data row by row using bound ouput columns
</span><span class="default">$stmt </span><span class="keyword">= </span><span class="default">$dbh</span><span class="keyword">-&gt;</span><span class="default">prepare</span><span class="keyword">(</span><span class="string">'select name, value from test'</span><span class="keyword">);
</span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">execute</span><span class="keyword">();
</span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">bindColumn</span><span class="keyword">(</span><span class="string">'name'</span><span class="keyword">, </span><span class="default">$name</span><span class="keyword">);
</span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">bindColumn</span><span class="keyword">(</span><span class="string">'value'</span><span class="keyword">, </span><span class="default">$value</span><span class="keyword">)
while (</span><span class="default">$stmt</span><span class="keyword">-&gt;</span><span class="default">fetch</span><span class="keyword">(</span><span class="default">PDO_FETCH_BOUND</span><span class="keyword">)) {
    echo </span><span class="string">"name=$name, value=$value\\n"</span><span class="keyword">;
}
</span><span class="default">?&gt;
</span></pre><p>Oh, how do you get and install it?   </p>
<p>Grab a PHP 5 snapshot from http://snaps.php.net (or HEAD from CVS).   </p>
<pre>   ./configure --prefix=/usr/local/php5 --with-zlib ....
   make
   make install
   export PATH=&quot;/usr/local/php5/bin:$PATH&quot;
   /usr/local/php5/bin/pear install -f PDO
   [ now add extension=pdo.so to php.ini ]
   /usr/local/php5/bin/pear install -f PDO_MYSQL
   [ now add extension=pdo_mysql.so to php.ini ]
   /usr/local/php5/bin/php -m
</pre><p>There are other drivers; <a href="http://pecl.php.net/package-search.php?pkg_name=pdo&amp;bool=AND&amp;submit=Search">Search PECL</a> for more. If you're running windows, just grab the win32 snap and the PDO dlls from <a href="http://snaps.php.net/win32/PECL_5_0">PECL binaries for PHP 5</a>.   </p>
<p>Credits: thanks to Marcus, George, Ilia and Edin.   </p>
<p>Please try to avoid asking too many questions about it; documentation will follow as soon as it is ready.  </p>
