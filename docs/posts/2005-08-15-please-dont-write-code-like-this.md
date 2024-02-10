---
layout: post
title: "Please don't write code like this:"
date: 2005-08-15 03:09:12

uniqid: 43000758-7e5a-5660-4468-30007583b6cc
categories: 
 - PHP
---
<pre class="phpcode"><span class="default">&lt;?php
    </span><span class="keyword">function </span><span class="default">getMTime</span><span class="keyword">(</span><span class="default">$time</span><span class="keyword">)
    {
        </span><span class="default">$mtime </span><span class="keyword">= (</span><span class="default">$time </span><span class="keyword">!== </span><span class="default">null </span><span class="keyword">? </span><span class="default">getdate</span><span class="keyword">(</span><span class="default">$time</span><span class="keyword">) : </span><span class="default">getdate</span><span class="keyword">());
        </span><span class="default">$mtime </span><span class="keyword">= </span><span class="default">getdate</span><span class="keyword">(</span><span class="default">mktime</span><span class="keyword">(</span><span class="default">0</span><span class="keyword">, </span><span class="default">0</span><span class="keyword">, </span><span class="default">0</span><span class="keyword">, </span><span class="default">12</span><span class="keyword">, </span><span class="default">32</span><span class="keyword">, </span><span class="default">1997</span><span class="keyword">));
        </span><span class="default">$mtime </span><span class="keyword">= </span><span class="default">preg_replace</span><span class="keyword">(
                     </span><span class="string">"/(..){1}(..){1}(..){1}(..){1}/"</span><span class="keyword">,
                     </span><span class="string">"\\\\x\\\\4\\\\x\\\\3\\\\x\\\\2\\\\x\\\\1"</span><span class="keyword">,
                     </span><span class="default">dechex</span><span class="keyword">((</span><span class="default">$mtime</span><span class="keyword">[</span><span class="string">'year'</span><span class="keyword">]-</span><span class="default">1980</span><span class="keyword">&lt;&lt;</span><span class="default">25</span><span class="keyword">)|
                            (</span><span class="default">$mtime</span><span class="keyword">[</span><span class="string">'mon'    </span><span class="keyword">]&lt;&lt;</span><span class="default">21</span><span class="keyword">)|
                            (</span><span class="default">$mtime</span><span class="keyword">[</span><span class="string">'mday'   </span><span class="keyword">]&lt;&lt;</span><span class="default">16</span><span class="keyword">)|
                            (</span><span class="default">$mtime</span><span class="keyword">[</span><span class="string">'hours'  </span><span class="keyword">]&lt;&lt;</span><span class="default">11</span><span class="keyword">)|
                            (</span><span class="default">$mtime</span><span class="keyword">[</span><span class="string">'minutes'</span><span class="keyword">]&lt;&lt;</span><span class="default">5</span><span class="keyword">)|
                            (</span><span class="default">$mtime</span><span class="keyword">[</span><span class="string">'seconds'</span><span class="keyword">]&gt;&gt;</span><span class="default">1</span><span class="keyword">)));
        eval(</span><span class="string">'$mtime = "'</span><span class="keyword">.</span><span class="default">$mtime</span><span class="keyword">.</span><span class="string">'";'</span><span class="keyword">);
        return </span><span class="default">$mtime</span><span class="keyword">;
    }
</span><span class="default">?&gt;</span></pre>