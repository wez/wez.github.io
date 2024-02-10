---
layout: post
title: "Linux on Windows"
date: 2004-01-19 07:44:10
updated: 2011-05-22
uniqid: 35153889-63ce-41cc-bebd-0060abacba59
categories: 
 - Blog
---
<p>[Update: <a href="https://bitbucket.org/wez/pengwin">You can now find
  the source code for this on bitbucket</a>]</p>

<p>Call me crazy, but I've wanted to run a linux binary natively under
windows for a while now; kinda like <a
  href="http://www.winehq.com">wine</a>, but in reverse.</p>

<p>Well, the other day I was browsing through the MSDN docs (as you do)
and discovered that it is possible to install a "vectored" exception
handler.  A quick bit of test code later, and I discovered that I can
trap "int 0x80" instructions using this technique--those are used by
linux binaries to initiate syscalls.</p>

<p>A couple of days hacking later, and I have a very small linux kernel
emulation environment; it's split into two parts--low.dll (the kernel
code) and low.exe (the bootstrap).  The usage is quite simple; from your
command prompt, run the linux binary of choice by prefixing the command
line with "low.exe":</p><p>low.exe linuxsmallapp one two three
four</p><p><a href="/images/pengwin.png"><img
  src="/images/pengwin.thumb.png" width="150" height="85"
  border="1"></a></p>

<p>The code from the named linux binary is loaded (low.dll includes an
ELF loader) and bootstraps the process by faking an execve() syscall.
When the syscall returns, the code resumes execution inside the loaded
module.  The code is running natively on your processor; and syscalls
happen in userspace (just like the binary) although they run under a
separate stack inside the win32 exception handler code.  So, this is
almost like kernel space.</p>

<p>What I have now is good enough to run my simple hello world
application.  It doesn't yet handle dynamic elf executables--they need
to be statically linked.   Thanks to Tal Peer, I have a collection of
statically compiled coreutils from gentoo; they start up fine, but die
somewhere in the stdio part of libc. I'm trying to track down the
problem.</p>

<p>If anyone has any insight into why this might be (maybe windows is
doing stuff for software interrupt number 128? before it calls my
exception handler?), I'd like to hear it :-)</p>

<p>[Update] If you're interested in playing with it, please don't expect
to acheive much at this stage.  You can <a
  href="https://bitbucket.org/wez/pengwin/downloads/low-0.1.zip">download
  it here</a>.</p>
