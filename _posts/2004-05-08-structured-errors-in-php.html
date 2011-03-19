---
layout: post
title: "Structured Errors in PHP"
date: 2004-05-08 14:26:38

uniqid: a44b743b-ae74-465d-a026-f2e4e3e2295f
categories: 
 - PHP
---
<p>We don't have them, yet, but we might in PHP 5.1. Here's a possible vision that will make both procedural and OO programmers happy.  Before the vision, I'll summarize the main problems that need to be solved.   </p>
<p><b>Problems</b>   </p>
<p><ol>
<li>Our error reporting mechanism consists only of severity level and textual error message.  This makes it hard to handle specific errors from within our code.
 <li>Accessing the last error from outside of an error handler is not possible without writing some glue code for yourself.  You can turn on the track_errors setting to store the error message (not severity) in a local variable, but this doesn't buy you much.
 <li>Since our regular mechanism is deficient, people want to use exceptions for everything.  This is a problem, since exceptions are quite expensive and are not suitable to use for everything (such as E_NOTICE severity) for all extensions.  It might make sense to do this for a particular extension in a particular piece of code in a particular application, but not globally.  In addition, for people using code libraries, the library needs things to work one way, whereas the application wants things to work in another.
   </ol>
<p><b>Solution</b>   </p>
<p><ol>
<li>Introduce error code identifiers.  These identifiers will be strings prefixed with the name of the extension and a colon.  So, errors from the standard extension would have identifiers such as &quot;standard:&lt;errorcode&gt;&quot;.  These identifiers can be examined in the PHP code more easily than parsing error message text, and since they are prefixed by the extension name, it side-steps the event where two different libraries use the same error number for different error conditions--we won't suffer from those collisions.
 <li>The error identifiers would be raised along with the severity and textual message when the extension calls one of the php_error_docref style functions.
 <li>The error handling mechanism would populate an $_ERROR super-global with the severity, identifier and textual message.  This allows the PHP script to suppress the usual error handler and make decisions based on the info it finds in $_ERROR, if they wish.
   </ol>
<p><b>For OOP Programmers</b>   </p>
<p><ol>
<li>Since all errors/warnings/notices are now structured, it would be really easy to map them to exceptions.  This mapping needs to controlled within the script using a kind of stacking state.  When the engine starts running, the mapping state is set so that no errors are mapped to exceptions.
 <li>An application or library could then change this so that errors from a particular extension are mapped to exceptions, or so that all errors are mapped to exceptions by using a simple pattern matching rule.  This state needs to be applied to a block of code, so that setting is contained and doesn't mess with that of the calling code.  The <i>declare</i> statement is ideal for this.
   </ol>
<p><b>Sample for procedural programmers</b>   </p>
<pre class="phpcode"><span class="default">&lt;?php
</span><span class="keyword">function </span><span class="default">do_something_with_a_file</span><span class="keyword">(</span><span class="default">$filename</span><span class="keyword">)
{
   </span><span class="comment">// ensure that streams functions aren't mapped to exceptions
   // everything else retains its current exception mapping
   </span><span class="keyword">declare(</span><span class="default">exception_map</span><span class="keyword">=</span><span class="string">'-standard:streams:*'</span><span class="keyword">) {
      </span><span class="default">$fp </span><span class="keyword">= @</span><span class="default">fopen</span><span class="keyword">(</span><span class="default">$filename</span><span class="keyword">, </span><span class="string">'r'</span><span class="keyword">);
      if (!</span><span class="default">$fp</span><span class="keyword">) {
           if (</span><span class="default">$_ERROR</span><span class="keyword">[</span><span class="string">'code'</span><span class="keyword">] == </span><span class="string">'standard:streams:E_NOENT'</span><span class="keyword">) {
               </span><span class="comment">// handle the case where the file doesn't exist
           </span><span class="keyword">}
      }
   }
   </span><span class="comment">// now the declare block is finished, pop back to original
   // exception mapping state
</span><span class="keyword">}
</span><span class="default">?&gt;
</span></pre><p><b>Sample for OO prgrammers</b>   </p>
<pre class="phpcode"><span class="default">&lt;?php
</span><span class="keyword">function </span><span class="default">do_something_with_a_file</span><span class="keyword">(</span><span class="default">$filename</span><span class="keyword">)
{
   </span><span class="comment">// ensure that streams functions are mapped to exceptions
   // everything else retains its current exception mapping
   </span><span class="keyword">declare(</span><span class="default">exception_map</span><span class="keyword">=</span><span class="string">'+standard:streams:*'</span><span class="keyword">) {
      try {
           </span><span class="default">$fp </span><span class="keyword">= @</span><span class="default">fopen</span><span class="keyword">(</span><span class="default">$filename</span><span class="keyword">, </span><span class="string">'r'</span><span class="keyword">);
      } catch (</span><span class="default">Exception $e</span><span class="keyword">) {
           if (</span><span class="default">$e</span><span class="keyword">-&gt;</span><span class="default">getCode</span><span class="keyword">() == </span><span class="string">'standard:streams:E_NOENT'</span><span class="keyword">) {
               </span><span class="comment">// handle the case where the file doesn't exist
           </span><span class="keyword">}
      }
   }
   </span><span class="comment">// now the declare block is finished, pop back to original
   // exception mapping state
</span><span class="keyword">}
</span><span class="default">?&gt;
</span></pre><p>As I hope you can see, this allows some flexibility in your code.  You can code OO-style if you like.  You can mix code snippets written using conflicting a style into your application, since well written libraries will localize their error handling preferences.   </p>
<p>The exception mapping syntax used in the declare block should be quite simple to grasp; a plus or a minus character indicates if the pattern should be added to mapping list, or excluded from it.  The rest of the string is a simple glob-style string where an asterisk acts as a wildcard.  To make multiple changes, without using multiple nested declare blocks, simply separate each one by commas in the string:   </p>
<pre class="phpcode"><span class="default">&lt;?php
  </span><span class="comment">// don't map any errors from ext/standard, except
  // for streams errors
  </span><span class="keyword">declare(</span><span class="default">exception_map</span><span class="keyword">=</span><span class="string">'-standard:*,+standard:streams:*'</span><span class="keyword">) { ... }
</span><span class="default">?&gt;
</span></pre><p>I'm fairly happy with this idea; the only thing is that the syntax in the declare block is a bit weird; it might make sense to come up with an alternative language level keyword.  The important point is that any changes made to the mapping stack are popped when control leaves that section of code.  If we had the <i>finally</i> statement, then we could do something like this:   </p>
<pre class="phpcode"><span class="default">&lt;?php
</span><span class="keyword">function </span><span class="default">do_something_with_a_file</span><span class="keyword">(</span><span class="default">$filename</span><span class="keyword">)
{
   </span><span class="comment">// ensure that streams functions aren't mapped to exceptions
   // everything else retains its current exception mapping
   </span><span class="default">push_exception_map</span><span class="keyword">(</span><span class="string">'-standard:streams:*'</span><span class="keyword">);
   try {
      </span><span class="default">$fp </span><span class="keyword">= @</span><span class="default">fopen</span><span class="keyword">(</span><span class="default">$filename</span><span class="keyword">, </span><span class="string">'r'</span><span class="keyword">);
      if (!</span><span class="default">$fp</span><span class="keyword">) {
           if (</span><span class="default">$_ERROR</span><span class="keyword">[</span><span class="string">'code'</span><span class="keyword">] == </span><span class="string">'standard:streams:E_NOENT'</span><span class="keyword">) {
               </span><span class="comment">// handle the case where the file doesn't exist
           </span><span class="keyword">}
      }
   } </span><span class="default">finally </span><span class="keyword">{
      </span><span class="default">pop_exception_map</span><span class="keyword">();
   }
}
</span><span class="default">?&gt;
</span></pre><p>I like this better; it's less stuff to hack into the engine.   </p>
<p>In conclusion then, I think this possible solution will please pretty much all the people using PHP, whether they are fans of OO or procedural code--you can write your &quot;Enterprise&quot; level code regardless of your preference, and drop-in well written third-party components without worrying so much about how they handle errors.   </p>
<p>I welcome your comments!  </p>
