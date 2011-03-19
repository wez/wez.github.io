---
layout: post
title: "dtracing PHP on Solaris"
date: 2005-08-15 01:48:02

uniqid: 42f82a8b-09b7-5013-1667-2f82a8b24ead
categories: 
 - PHP
 - Solaris
---
<p>[Updated: just wanted to point out that <a href="http://www.sun.com/software/solaris/get.jsp">Solaris 10 is free</a>]   </p>
<p>One of the things that <a href="http://www.lethargy.org/~jesus/">Theo</a> and myself have been salivating over at <a href="http://www.omniti.com">OmniTI</a> recently is this really cool tool on Solaris (and <a href="http://www.opensolaris.org">OpenSolaris</a>) called <b>DTrace</b>.   </p>
<p>DTrace is one of those tools that makes you wonder how you did anything without it before you'd heard of it.  What is it?  You can think of it as being something like strace that's been exposed to ultimate steroid mix during its conception.  Why is it better than strace and similar tools?  It's non-invasive, fast, scriptable and extensible.   </p>
<p>So, why am I posting about it here?  I had the great pleasure of sitting down with <a href="http://blogs.sun.com/roller/page/bmc">Bryan Cantrill</a>, (Solaris kernel developer and one of the guys behind dtrace--a very animated, funny, smart guy) at OSCON, and produced a dtrace provider for PHP.   </p>
<p>This is implemented as a PECL extension and can be installed with this simple invocation, if you've built your own php with pear support (recommended):   </p>
<pre>   # pear install dtrace
</pre><p>Once installed, you'll want to add a line to your php.ini file:   </p>
<pre>   extension=dtrace.so
</pre><p>Once it's loaded, restart your apache server, and you're ready to dtrace. If you run:   </p>
<pre>   # dtrace -l | grep php
</pre><p>You'll see a bunch of output like the following, for each apache child process:   </p>
<pre>   34412    php9915         dtrace.so                php_dtrace_execute function-return
   34413    php9915         dtrace.so       php_dtrace_execute_internal function-return
   34415    php9915         dtrace.so                php_dtrace_execute function-entry
   34416    php9915         dtrace.so       php_dtrace_execute_internal function-entry
</pre><p>What this shows is that process ID 9915 is running and offers up 4 possible probe points.  The probe points wrap around the Zend engine execution routines (php_dtrace_execute and php_dtrace_execute_internal) and provide two classes of probes; function-entry and function-return.  What this means is that we can monitor PHP whenever a function is about to be called (function-entry) and when a function has finished being called (function-return).   </p>
<p>These probes have 3 parameters; arg0 is the name of the function being called, arg1 is the name of the file from which the call is being made and arg2 is the line number of that file.   </p>
<p>Now, let's say that you want to get an idea of which functions are being called in your application; the following dtrace line counts each call; it won't print out any information immediately, as it is sitting there gathering information; run it for a while and then hit CTRL-C and it will spit out the summary information.   </p>
<pre>   # dtrace -n function-entry'{@[copyinstr(arg0)] = count()}'
</pre><p>I get this output if I try to set up media wiki:   </p>
<pre>    dl                                                                1
    extension_loaded                                                  1
    version_compare                                                   1
    phpversion                                                        1
    ob_implicit_flush                                                 1
    install_version_checks                                            1
    is_writable                                                       1
    error_reporting                                                   1
    is_array                                                          1
    header                                                            1
    strpos                                                            1
    php_sapi_name                                                     1
    function_exists                                                   2
    dirname                                                           2
    ini_set                                                           2
    defined                                                           3
    file_exists                                                       4
    main                                                              9
    define                                                           74
</pre><p>Pretty cool huh?  We can immediately see that media wiki is calling define() a LOT across the space of just 2 page loads.  If you were looking for things to optimize (and if this wasn't the rarely used setup page), then you've very easily gotten an idea of what's going on.  You can then refine your dtrace line to home in on the problem areas.   </p>
<p>You can also get an idea of code coverage with this one-liner, which will summarize the calls made, grouping the information by filename, and pretty printing a histogram showing the relative number of calls made from the various lines in your app:   </p>
<pre>   # dtrace -n function-entry'{@[copyinstr(arg1)] = lquantize(arg2, 0, 5000)}'
</pre><pre>   /export/home/wez/public_html/mediawiki-1.4.7/config/index.php
           value  ------------- Distribution ------------- count
             114 |                                         0
             115 |@@@@@@                                   2
             116 |                                         0
             117 |@@@                                      1
             118 |                                         0
             119 |@@@                                      1
             120 |@@@                                      1
             121 |                                         0
</pre><p>This (abbreviated) output shows you the number of times a particular line of code was visited in config/index.php of media wiki, rendering the relative incidences as ascii-art bars (the Solaris gang are big ascii-art fanatics).   </p>
<p>The really really cool thing about this is that it can aggregate the information across all the apache children running on your machine, transparently.  The way that dtrace is implemented means that you can even have this module loaded into php permanently on <i>production</i> machines; it has no overhead when you're not running the dtrace command, and very very tiny overhead when you are.   </p>
<p>DTrace is a powerful tool for sysadmins and developers alike; I'm looking forward to making heavy use of it in the near future and beyond.  I've barely even scratched the surface of the surface here; if you want to learn more, check out <a href="http://blogs.sun.com/roller/page/bmc/20050805">Bryan's more detailed &quot;dtrace and php&quot; blog entry</a>, where he shows how to view the complete call stack through php down into the kernel and back (neat!), and the <a href="http://opensolaris.org/os/community/dtrace/">DTrace Community</a>.   </p>
<p>DTrace is available on Solaris 10 and up (including OpenSolaris).  I recommend experimenting with it, as it will revolutionize the way that you think about debugging and profiling.   </p>
