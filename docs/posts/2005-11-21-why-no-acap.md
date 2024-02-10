---
layout: post
title: "Why no ACAP?"
date: 2005-11-21 05:06:36

uniqid: 438155dc-e579-9029-1236-38155dc914f3
categories: 
 - Blog
---
<p>So, I'm sitting here swearing at my &quot;big three&quot; applications; <a href="http://gaim.sf.net">Gaim</a>, <a href="http://www.mozilla.org/products/firefox/">Firefox</a> and <a href="http://www.mozilla.org/products/thunderbird">Thunderbird</a>, because they work great if you only ever use a single machine, but are a real pain in the ass if you move between machines.   </p>
<p>The biggest pain is Gaim; it stores its buddy list configuration in a local file, and when it starts up, it merges the buddy lists with the server side data.  If you have two or more machines, only one of them will have the most up-to-date settings.  If you've done a lot of reorganization to your buddy list, and switch to one of the other machines, you'll have all your buddies re-added to the server-side buddy list in their old locations, and also have the new locations from the server-side added to the local copy on disk.  You need to re-organize the buddy list again.  Repeat for each additional machine that you use.   </p>
<p>Thunderbird and firefox are in a similar boat; even using IMAP, it's &quot;impossible&quot; (as in, too damned painful) to keep your thunderbird message filtering rules and junk mail training data synchronized.  Firefox; it would be nice to sync passwords and <a href="http://sage.mozdev.org/">Sage</a> feed lists between machines.   </p>
<p>So, why isn't there some magical way to look after this?  Surprisingly, there <b>is</b> actually a standard protocol for exactly this purpose; <a href="http://asg.web.cmu.edu/acap/">it's called ACAP</a>:   </p>
<p><i>ACAP is the Application Configuration Access Protocol, an internet protocol for accessing client program options, configurations, and preference information remotely. ACAP is a solution for the problem of client mobility on the Internet. Almost all Internet applications currently store user preferences, options, server locations, and other personal data in local disk files. These leads to the unpleasant problems of users having to recreate configuration set-ups, subscription lists, addressbooks, bookmark files, folder storage locations, and so forth every time they change physical locations.</i>   </p>
<p>This sounds perfect, but why don't &quot;the big three&quot; apps support it?  It might have something to do with the lack of <a href="http://asg.web.cmu.edu/acap/acap-implementations.html">ACAP implementations</a>.  Where are they?  Is anybody working on them?  Is anybody selling them?  What sounds like a great idea from <b>1997</b> still isn't here with any real presence in 2005.  What went wrong?   </p>
<p>I've had a quick look at the <a href="http://www.ietf.org/rfc/rfc2244.txt">ACAP RFC</a> and it seems overly complicated (it's based on IMAP--urgh!) so perhaps this has something to do with it.  Maybe there just wasn't enough demand for ACAP, so it got shelved.   </p>
<p>Whatever the reason, I wonder why we don't have even a simplified ACAP-style preferences server or service today.  Am I just looking in the wrong places?  </p>
