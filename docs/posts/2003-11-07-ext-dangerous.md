---
layout: post
title: "ext/dangerous"
date: 2003-11-07 08:55:16

uniqid: 5643ad91-eeb7-42ac-bde2-75e71dcace49
categories: 
 - PHP
---
<p>Well, I'm finally back from my conference run.  Despite some shortcomings with hotel 'net connections, nothing went badly.</p><p>One thing I'm particularly pleased with is that I got to write some code (feels like a long time since I had the luxury to write pleasure code).  I'm about 50% of the way through a <a href="http://sources.redhat.com/libffi/">libffi</a> extension for PHP 5 (the ffi page is out of date, but still relevant).</p><p>FFI is an acronym for Foreign Function Interface and it provides a portable way to make calls from PHP into C code (and vice versa) at run-time, without having to write glue specifically for the function being called at compile-time.</p><p>PHP already has a w32api interface (unmaintained and broken) that does the same thing but for windows; my ffi extension replaces it and will also work on other platforms (Linux, Darwin, SunOS, Irix, Alpha, m68k, PowerPC and Arm).</p><p>Not only can you map functions from shared libraries (and DLLs), but you can also define structured types and access them using a natural syntax (taking advantage of the new OO internals in ZE2).</p><p>Even better is that you will also be able to pass PHP functions directly to mapped C functions that take callbacks as parameters (like the C library function qsort).</p><p>This all sounds really cool, but there is a price - allowing a PHP script to do this stuff is extremely dangerous (hence the title of this entry), so you won't find it on shared hosters.</p>