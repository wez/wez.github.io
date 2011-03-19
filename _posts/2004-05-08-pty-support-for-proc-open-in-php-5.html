---
layout: post
title: "pty support for proc_open() in PHP 5"
date: 2004-05-08 14:28:39

uniqid: 970a9a10-7a75-4200-a434-ad5dfb42f393
categories: 
 - PHP
---
<p>[Update: this patch is now in CVS and will be in PHP5RC2]   </p>
<p>Today started out good, then it got weird.  I felt quite privileged, since a newcomer to the PHP world (employed by Zend) took the time to ask me what the procedure was for publishing an extension in PECL.  I felt privileged because the last time a new Zend employee appeared no one said anything to anyone; no introductions; karma was granted and a big new extension just appeared in the repository.   </p>
<p>So, today was good.  I gave the new-comer a friendly welcoming email.  As it turned out, their extension duplicated and/or superceded functionality in the (work-in-progress) cvsclient extension, so I suggested merging it.  The newcomer had already contacted the author.   </p>
<p>&quot;This is great!&quot; I thought to myself.  It then became apparent that the new extension did its stuff by wrapping a CVS binary and parsing the output.  While this approach works, it's not really the best thing to put into PECL.  Here are two good reasons why:   </p>
<p><ul>
<li>Portability.  fork(), exec()ing and piping is painful to get right and keep portable.  I have some experience in this matter, having written <a href="http://docs.php.net/en/function.proc-open.html">this</a>, which works under unix and win32.
 <li>Security.  Doing string parsing and manipulation in C is painful. This pain is just one of the reasons why people use scripting languages instead of hand-coding C CGI.  Well, strings in C isn't that painful, but secure, non-exploitable strings in C is.  And it's easy to overlook something, or be off-by-one in your calculations for a buffer size.
   </ul>
<p>So we invited the author to join us on IRC and talk about what to do. The conversation, in essence, consisted of Derick and Ilia making their opinions known (they were against the extension, largely due to the reasons I've mentioned above).  I'll admit that this can be a bit intimidating (Derick and Ilia aren't known for beating around the bush ;-), but it wasn't a massacre.  We learned that:   </p>
<p><ul>
<li>The extension was written as an exercise in learning how the engine works
 <li>it didn't take all that much time to write
 <li>yes, it probably could and should go into <a href="http://pear.php.net">PEAR</a> instead
 <li>HOWEVER! there is no way to emulate a pty from a PHP script, and this is required in order to send a password to the cvs binary
   </ul>
<p>The conversation lulled, and then I got busy with work.   </p>
<p>When I returned and read through the backlog, I could see that things had started to get weird.  Zeev had arrived and not happy that we hadn't allowed the extension to be committed yet.  The reasons against were made clear, and the reasons for were made clear--it's a working extension that could be used by people right now, and the pty problem meant that it was not possible to implement fully in PEAR.  As is typical with discussions between the senior PHP people, it wasn't going anywhere ;-)   </p>
<p>Now, I'm quite liberal when it comes to OpenSource.  So long as something isn't total garbage it has merits.  We hadn't seen the code yet but, being an optimist, I decided that Zend wouldn't hire a total idiot to work on the Zend Engine--so the code probably wasn't garbage.  I suggested that we merge the code with the cvsclient extension, and look at implementing the features natively over time, as Sara planned to do with cvsclient anyway.  This was surely a definite win:   </p>
<p><ul>
<li>we retain a single CVS extension in PECL.  This is good for our users, who don't want a gazillion bajillion variations of the same thing.
 <li>our CVS extension gets a load of new features by merging with this new one.  Good for us, good for our users.
 <li>the purist element of PECL hackers would eventually be satisified once all features were implemented natively.
 <li>The effort spent on the new extension is not wasted.
   </ul>
<p>This was, I thought, warmly received as a good idea by all.  Things having returned to normality, I resumed work.  When I looked back at the channel a few minutes later, I saw that the discussion had continued and concluded with Zeev leaving in what I can only describe as a huff.   </p>
<p>What happened next?  A new module was added to CVS for &quot;Public Zend Extensions&quot;.  WTF? :)  Totally bewildered by the crazy turn of events, I posted <a href="http://news.php.net/article.php?group=php.cvs&amp;article=27061">this message</a> in response to the commit.  The module was subsequently renamed to non-pecl and karma granted to the PHP core people.   </p>
<p>So, what just happened?  I'm not entirely sure.  IMO, adding &quot;non-pecl&quot; is crazy.  What's the point?  PHP extensions are either in the main distro, in PECL, or not distributed by php.net (since we're not behind them).  How are we going to manage &quot;non-pecl&quot;?  How does it fit into our (PHP) procedures for QA, snapshots, distros and mirrors?   </p>
<p>It seems a bit hasty.   </p>
<p><b>Is there a point to all this??</b>   </p>
<p>Ah yes.  Zeev said something along the lines of &quot;if someone ports it to PEAR, you can delete it from CVS&quot;.  With that in mind, I've written <a href="http://www.php.net/~wez/pty-php5.diff">a patch for proc_open</a> that adds pty support.   </p>
<p>Using this patch, you can do something like this in your scripts:   </p>
<pre class="phpcode"><span class="default">&lt;?php
    $p </span><span class="keyword">= </span><span class="default">proc_open</span><span class="keyword">(</span><span class="string">"cvs -d:pserver:foobar@cvs.php.net:/repository login"</span><span class="keyword">,
    array(
        </span><span class="default">0 </span><span class="keyword">=&gt; array(</span><span class="string">"pty"</span><span class="keyword">),
        </span><span class="default">1 </span><span class="keyword">=&gt; array(</span><span class="string">"pty"</span><span class="keyword">),
        </span><span class="default">2 </span><span class="keyword">=&gt; array(</span><span class="string">"pty"</span><span class="keyword">),
    ),
    </span><span class="default">$pipes</span><span class="keyword">);
    ... </span><span class="default">read stuff from $pipes</span><span class="keyword">[</span><span class="default">1</span><span class="keyword">] and </span><span class="default">$pipes</span><span class="keyword">[</span><span class="default">2</span><span class="keyword">] (</span><span class="default">stdout</span><span class="keyword">, </span><span class="default">stderr</span><span class="keyword">) ...
    ... </span><span class="default">write stuff to $pipe</span><span class="keyword">[</span><span class="default">0</span><span class="keyword">] (</span><span class="default">stdin</span><span class="keyword">) ...
  </span><span class="default">?&gt;
</span></pre><p>What this does is similar to creating a pipe to the process, but instead creates master (for your script) and slave (for the process you're running) pty handles using the /dev/ptmx interface of your OS. This allows you to send to, and capture data from, applications that open /dev/tty explicitly--and this is generally done when interactively prompting for a password.   </p>
<p>What you can't do is any terminal specific ioctl's from PHP land, so you can't make an xterm from PHP ... yet ;)   </p>
<p>Another limitation (although not the fault of PHP), is that the pty stuff isn't portable to win32 (where console applications open CON$).  As far as I can tell, there is no way to hook or emulate consoles under windows.  Likewise, if your flavour of UNIX doesn't support the Unix98 pts interface, you can't use this feature either.  The configure script detects the bits required, and everything is protected by #ifdefs in the code, so if you don't have the syscalls required, things should still build and work just as they did before.   </p>
<p>Since we're in feature freeze for RC2, I haven't committed this yet.  The patch is against HEAD, but should apply cleanly to most PHP 5 snaps or RC's you have around.   </p>
<p>Credit where credit is due: the pty support is based on the equivalent part of the code from Shie's new &quot;non-pecl&quot; cvs extension.  Thanks Shie for starting the day nicely, and for writing this code!  I have a hunch that it was Shie's first day working for Zend and that this was just as weird a turn of events for Shie as for us.   </p>
