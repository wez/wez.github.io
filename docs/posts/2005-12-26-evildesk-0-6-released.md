---
layout: post
title: "EvilDesk 0.6 Released"
date: 2005-12-26 04:21:24

uniqid: 43af6e5c-802d-6507-5036-3af6e5ccb34b
categories: 
 - EvilDesk
 - Windows
---
<p>Having finally found some time to myself, I thought it was about time that I push out a release with the changes that I've been using for the last few months.  Some of these changes are based on feedback from users; keep it coming folks! :)   </p>
<p>Feature changes in the new release include:   </p>
<p><ul>
<li>Revised plugin loading system.  Slits are defined via the new SLIT directive and plugins are loaded via the new LOAD directive in the .evdm file.
 <li>Slit windows will hide themselves when an application goes fullscreen (and come back when it leaves fullscreen mode).  I've tested this with PowerPoint (when viewing the slideshow) and PuTTY (alt-enter) and it seems to work ok.
 <li>Altered the gravity of the tray plugin so that it sinks below the flasher.  Why? When double-clicking on the gaim tray icon, the first click would open the buddy list, causing it to flash.  The flasher would bump the tray up, causing the second click to land on a different tray icon--usually the wifi icon, popping up the wireless network selector.
 <li>Added more internet-facing apps to the SaferExec line in the default configuration.  No idea what that means?  <a href="http://msdn.microsoft.com/library/default.asp?url=/library/en-us/dncode/html/secure11152004.asp">Read more on &quot;Browsing the Web and Reading E-mail Safely as an Administrator&quot;</a>.
   </ul>
<p>Bug fixes include:   </p>
<p><ul>
<li>Fixed a race condition where windows could bleed across to the target workspace when switching workspaces.
 <li>Fall back to the system default icon in the task switcher and flasher if the window in question has no icon of its own.
   </ul>
<p><a href="http://evildesk.wezfurlong.org/">Go get EvilDesk!</a>   </p>
<p>Merry Christmas!   </p>
