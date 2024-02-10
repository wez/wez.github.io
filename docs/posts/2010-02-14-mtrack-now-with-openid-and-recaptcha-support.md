---
layout: post
title: "mtrack: now with OpenID and reCaptcha support"
date: 2010-02-14 15:01:37

uniqid: cb7856a1-7ca0-46a2-874e-43e30808261f
categories: 
 - PHP
 - mtrack
---
<p>Some of the feedback and questions that I've gotten about mtrack were around making it easier to deploy and use in an open or public facing environment.</p>
<p>To that end, I've added support of OpenID authentication and bot detection via reCaptcha.</p>
<p>To enable these features is quite simple; for OpenID, add the following lines to your config.ini file:</p>
<p><span style="font-family: 'Courier New';">[plugins]<br />MTrackAuth_OpenID =</span></p>
<p>You should also remove any other Auth plugins that you may have there, as how they interact with OpenID is not currently defined.</p>
<p>This will cause mtrack to keep users classes as anonymous until they either request to log-in via a link in the navigation bar, or attempt to access a page that requires privileges that the anonymous user lacks.</p>
<p>Users authenticating via OpenID can contribute to tickets and wiki (unless you change their permissions, either directly, or via their user class), and those changes will be attributed to them using their OpenID identity URL.</p>
<p>mtrack has a system for aliasing users from different repos and authentication schemes, so if you had a code contributor named "wez" in one of your repos, an administrator can add their OpenID URL as an alias via the user administration screens.&nbsp; The admin user would edit the "wez" user and add "http://netevil.org/" to the list of aliases.&nbsp; This will cause mtrack to see that OpenID as being equivalent to the "wez" user.</p>
<p>You can, if you wish, make an OpenID URL recognized as an admin user by adding the following lines to your config.ini:</p>
<p><span style="font-family: 'Courier New';">[user_classes]<br /></span><span style="font-family: 'Courier New';">http://netevil.org/ = admin</span></p>
<p>This will have the effect of giving me admin rights to your mtrack install.</p>
<p>What about captchas?</p>
<p>Captcha's allow you to require that the person submitting a request be a human and not an automated submission agent.&nbsp; In practical terms, this helps to avoid spam by limiting it to human spammers instead of spam bots.</p>
<p>To enable Captchas in mtrack using the reCaptcha service, <a href="http://recaptcha.net/api/getkey?app=mtrack">go and register your domain and get yourself a private and public key pair</a>.&nbsp; Then add the following lines to your config.ini:</p>
<p><span style="font-family: 'Courier New';">[plugins]<br />MTrackCaptcha_Recaptcha = pubkey, privkey</span></p>
<p>Where pubkey and privkey are your public and private keys respectively (you must not use double quotes).</p>
<p>This will cause a captcha to be displayed and checked in the wiki and ticket editing screens for anonymous and authenticated users; admin users will not see the captcha.</p>
<p>Enjoy!</p>
<p>mtrack home page: <a title="http://bitbucket.org/wez/mtrack/wiki/Home" href="http://bitbucket.org/wez/mtrack/wiki/Home">http://bitbucket.org/wez/mtrack/wiki/Home</a></p>
<p>mtrack mailing list: <a title="http://groups.google.com/group/mtrack" href="http://groups.google.com/group/mtrack">http://groups.google.com/group/mtrack</a></p>
<p>IRC: <a title="irc://irc.freenode.net/mtrack" href="irc://irc.freenode.net/mtrack">irc://irc.freenode.net/mtrack</a></p>