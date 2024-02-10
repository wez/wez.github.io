---
layout: post
title: "Dead laptop disk == more linux hacking"
date: 2004-06-21 11:16:53
updated: 2009-06-07
uniqid: 15e161bc-fa69-40fc-8a6b-c18ac1583ff3
categories: 
 - Blog
---
<p><strong>Update2: </strong>moved code to <a title="http://bitbucket.org/wez/toshkey/overview/" href="http://bitbucket.org/wez/toshkey/overview/">http://bitbucket.org/wez/toshkey/overview/</a></p> <p><b>Update</b> acpid now handles the brightness controls, displays the battery status in the ps list and emits power warnings once you're down to 15 minutes of power. I've also added a little non-root acpid client that will allow you to run your own stuff in response to hotkey events. </p> <p>I suffered a dead (nearly; it's on its way out) laptop disk almost a week ago, and have been clawing my way back to normality. </p> <p>As a side effect, I now own a Toshiba Satellite M30, which apparently has slightly more linux friendly hardware than my other Satellite (the one that's having issues). </p> <p>One of the cool things is the toshiba_acpi module; it works in this model and allows access to the hotkeys so you can map them exactly as you like. Since running a standalone daemon for this sucks (you can choose either a python script or a slightly-overweight fnfxd), and since there was a feature request on the <a href="http://memebeam.org/toys/ToshibaAcpiDriver">ToshibaAcpiDriver</a> page for it, I've written <a href="http://bitbucket.org/wez/toshkey/overview/">this patch</a> that adds toshiba key support to acpid (1.0.3). </p> <p>Toshiba keys are exposed as button/toshiba events, followed by the 16-bit hex code for the key that was triggered, so stick some scripting magic into /etc/acpi/events and you're happily-a-mapping those keys. </p>