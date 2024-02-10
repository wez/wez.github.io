---
layout: post
title: "toshiba hotkeys on solaris"
date: 2005-08-22 00:52:36
updated: 2009-06-07
uniqid: 430921d4-bf41-1804-9770-30921d450467
categories: 
 - Solaris
---
<p><strong>Update</strong>: moved code to <a title="http://bitbucket.org/wez/toshutils/" href="http://bitbucket.org/wez/toshutils/">http://bitbucket.org/wez/toshutils/</a></p> <p>Friday evening I sat down and wrote my first piece of solaris kernel code and an associated user-space application that activates the brightness up/down hotkeys for the LCD on my Toshiba Satellite M30. </p> <p>I implemented a tosh_hci driver that can perform <a href="http://www.buzzard.me.uk/toshiba/downloads/hci.pdf">Toshiba Hardware Configuration Interface</a> traps via an ioctl(2). This driver is really simple; the hardest part being the intel assembly needed to perform the trap (technically an inb instruction, not a trap). </p> <p>The userspace code is a really slimmed down version of <a href="/blog/2005/aug/toshiba-hotkeys-on-solaris">the code that I previously made available in my patch to the linux acpid</a>. It currently only handles the LCD brightness keys because none of the features that the other hotkeys are supposed to invoke are currently supported by solaris/opensolaris. </p> <p>Anyhoo, I've made the source available, under the <a href="http://www.opensolaris.org/os/licensing">CDDL</a>, in a bundle <a href="http://bitbucket.org/wez/toshutils/">here</a> for any other toshibans that might like to get a bit more comfort factor back when running solaris. </p>