---
layout: post
title: "WinXP Desktop Shell Replacement"
date: 2004-12-30 08:16:57

uniqid: 41d3b979-5cb1-5015-2222-1d3b97979242
categories: 
 - Windows
---
<p>A little while back I mentioned some <a href="http://wezfurlong.org/blog/2004/oct/nvidia-nview-virtual-desktop-manager">annoyances with virtual desktop software under windows</a>.  It occurred to me that the only sane way to implement virtual desktops is to do away with  the explorer shell and use something else.   </p>
<p>This is not a new idea: <a href="http://www.geoshell.com/">GeoShell</a>, <a href="http://www.litestep.net/">LiteStep</a> and <a href="http://www.bb4win.org/">BB4win</a> all try to do this kind of thing, with varying degrees of success.  I tried to use each of these and found that they all have their rough edges that just made them unusable for me.   </p>
<p>So I was frustrated.  Motivated by frustration and curiosity, I wrote my own shell replacement for WinXP.  It features low resource consumption and a low-clutter interface that I suppose is somewhat similar to the OSX dock, even though that wasn't my original intention.   </p>
<p><img src="/images/wezdesk-shot-1.jpg" alt="screenshot"/> <a href="/images/wezdesk-shot-1-big.jpg">full-size</a>.   </p>
<p>My shell, dubbed &quot;WezDesk&quot; for want of a better name (EvilDesk!?), implements the following features:   </p>
<p><ul>
<li>Quicklaunch icon bar
 <li>System Notification area (aka &quot;Tray&quot;), including tray bubbles
 <li>Clock with day/date
 <li>No classic taskbar, but &quot;flashing&quot; windows have their icons bounced to get your attention
 <li>Light-weight icon-free start menu
 <li>Kludge-free Workspace management; use up to 4 virtual desktops
 <li>icon-free desktop
   </ul>
<p>If you feel like a change from explorer will also do you some good, then feel free to download and test-drive my latest offering.   </p>
<p><b>Disclaimer</b> : the software is provided as-is in binary form only with no warranty.  I am not liable for any bad mojo, be it mental, physical or meta-physical, that arises from its use.  By downloading it, you assert that you will not decompile or reverse engineer the software in any way, and that you will not re-distribute it to any third party in any form without my express consent. All rights reserved; All liabilities disclaimed.   </p>
<p><b>Caveat Emptor</b> : when switching desktops, some naughty apps (notably gaim and the MSDN help browser) occasionally get &quot;warped&quot;.  Restarting the app is one solution, not switching desktops is another.  (Running miranda instead of gaim saved several MB of RAM at runtime, runs faster and has no weird interactions that I know of).   </p>
<p><b>Caveat Emptor 2</b> : tray &quot;bubbles&quot; sometimes miss updates to their text content.   </p>
<p>Despite these niggles, I've been happily using the current build for over a month, &gt;15 hours per day and it's been a pleasure to use.   </p>
<p><a href="http://evildesk.wezfurlong.org">Download wezdesk.msi</a>   </p>
<p><b>Installation</b>   </p>
<p>Double click the .msi file to install.  Note that installation is non-destructive; it simply installs the app into your start menu.  You can then go into:   </p>
<p>Start | Programs | Evil, as in Dr | Shell | Set Shell   </p>
<p>to register it as your shell.  You need to log out and log back in again to use it.   </p>
<p>If you want to go back to explorer.exe, call up the start menu (right click on the desktop):   </p>
<p>All Programs | Programs | Evil, as in Dr | Shell | Reset Shell   </p>
<p>and log out and log back in again.   </p>
<p>You'll also find the online docs available in the start menu; in there you'll find out about the various hotkeys available.   </p>
