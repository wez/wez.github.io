---
layout: post
title: "blog spam - a solution"
date: 2005-02-02 04:40:10

uniqid: 420059aa-4265-7346-8402-20059aae7d9d
categories: 
 - Evil Blog
 - PHP
---
<p>Today, this blog got its first ever spam, via the trackback interface. How annoying.  Here's how I've stopped it (yes, the regexes could be better, and the parse_url() call eliminated, but its late and this is a quick hack):   </p>
<pre class="phpcode"><span class="default">&lt;?php
</span><span class="keyword">function </span><span class="default">ne_rbl_check</span><span class="keyword">(</span><span class="default">$ip</span><span class="keyword">) {
   static </span><span class="default">$lists </span><span class="keyword">= array(</span><span class="string">'.sbl-xbl.spamhaus.org'</span><span class="keyword">);
   </span><span class="default">$ip </span><span class="keyword">= </span><span class="default">gethostbyname</span><span class="keyword">(</span><span class="default">$ip</span><span class="keyword">);
   foreach (</span><span class="default">$lists </span><span class="keyword">as </span><span class="default">$bl</span><span class="keyword">) {
      </span><span class="default">$octets </span><span class="keyword">= </span><span class="default">explode</span><span class="keyword">(</span><span class="string">'.'</span><span class="keyword">, </span><span class="default">$ip</span><span class="keyword">);
      </span><span class="default">$octets </span><span class="keyword">= </span><span class="default">array_reverse</span><span class="keyword">(</span><span class="default">$octets</span><span class="keyword">);
      </span><span class="default">$h </span><span class="keyword">= </span><span class="default">implode</span><span class="keyword">(</span><span class="string">'.'</span><span class="keyword">, </span><span class="default">$octets</span><span class="keyword">) . </span><span class="default">$bl</span><span class="keyword">;
      </span><span class="default">$x </span><span class="keyword">= </span><span class="default">gethostbyname</span><span class="keyword">(</span><span class="default">$h</span><span class="keyword">);
      if (</span><span class="default">$h </span><span class="keyword">!= </span><span class="default">$x</span><span class="keyword">) {
         return </span><span class="default">false</span><span class="keyword">;
      }
   }
   return </span><span class="default">true</span><span class="keyword">;
}
function </span><span class="default">ne_surbl_checks</span><span class="keyword">()
{
   </span><span class="default">$things </span><span class="keyword">= </span><span class="default">func_get_args</span><span class="keyword">();
   foreach (</span><span class="default">$things </span><span class="keyword">as </span><span class="default">$thing</span><span class="keyword">) {
      if (</span><span class="default">preg_match</span><span class="keyword">(</span><span class="string">'/^\\d+\\.\\d+\\.\\d+\\.\\d+$/'</span><span class="keyword">, </span><span class="default">$thing</span><span class="keyword">)) {
         if (!</span><span class="default">ne_rbl_check</span><span class="keyword">(</span><span class="default">$thing</span><span class="keyword">)) return </span><span class="default">false</span><span class="keyword">;
      }
      if (</span><span class="default">preg_match_all</span><span class="keyword">(</span><span class="string">'~(http|https|ftp|news|gopher)://([^ ]+)~si'</span><span class="keyword">,
            </span><span class="default">$thing</span><span class="keyword">, </span><span class="default">$m </span><span class="keyword">= array(), </span><span class="default">PREG_SET_ORDER</span><span class="keyword">)) {
         foreach (</span><span class="default">$m </span><span class="keyword">as </span><span class="default">$match</span><span class="keyword">) {
            </span><span class="default">$url </span><span class="keyword">= </span><span class="default">parse_url</span><span class="keyword">(</span><span class="default">$match</span><span class="keyword">[</span><span class="default">0</span><span class="keyword">]);
            if (!</span><span class="default">ne_rbl_check</span><span class="keyword">(</span><span class="default">$url</span><span class="keyword">[</span><span class="string">'host'</span><span class="keyword">])) return </span><span class="default">false</span><span class="keyword">;
         }
      }
   }
   return </span><span class="default">true</span><span class="keyword">;
}
</span><span class="default">?&gt;
</span></pre><p>These two functions implement RBL and SURBL checks.  RBLs, as you probably already know, are real-time block lists; you can look up an IP address in a block list using DNS, and if you get a record back, that address is in the block list.  The first of the two functions implements this, in a bit of a lame hackish way.   </p>
<p>The second function implements content-based checks, commonly known as SURBL; the text is scanned for things that look like IP addresses or URLs; those IP addresses or host names are extracted from the content and then looked up in the RBL using the first function.   </p>
<p>Why is this good?  A comment spammer will typically want to inject a link to their site onto your blog, and you can be fairly sure that their site is listed in a good RBL.  The RBL used in my sample above is an aggregation of the SBL and XBL lists which contain known spammers and known zombie/exploited machines, so it should do the job perfectly.   </p>
<p>Now to hook it up to the blog; this snippet is taken from my trackback interface:   </p>
<pre class="phpcode"><span class="default">&lt;?php
</span><span class="keyword">if (!</span><span class="default">ne_surbl_checks</span><span class="keyword">(</span><span class="default">get_ip</span><span class="keyword">(), </span><span class="default">$_REQUEST</span><span class="keyword">[</span><span class="string">'excerpt'</span><span class="keyword">], </span><span class="default">$_REQUEST</span><span class="keyword">[</span><span class="string">'url'</span><span class="keyword">], </span><span class="default">$_REQUEST</span><span class="keyword">[</span><span class="string">'blog_name'</span><span class="keyword">])) {
   </span><span class="default">respond</span><span class="keyword">(</span><span class="string">'you appear to be on SBL/XBL, or referring to content that is'</span><span class="keyword">, </span><span class="default">1</span><span class="keyword">);
}
</span><span class="default">?&gt;
</span></pre><p>get_ip() is a function to determine the IP address of the person submitting the page; I haven't included it here for the sake of brevity; it's fairly simple to code one, but keep in mind that it needs to be aware of http proxies.  respond() returns an appropriate error message to the person making the trackback and exits the script.   </p>
<p>And that's all there is to it; you can do similar things with your comments submission and pingback interfaces.   </p>
<p>Enjoy.  </p>
