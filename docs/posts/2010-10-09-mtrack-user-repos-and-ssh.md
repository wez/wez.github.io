---
layout: post
title: "mtrack user repos and ssh"
date: 2010-10-09T04:05:00

uniqid: f0128bac-e9b4-4407-96b6-b30cd704df83
categories: 
 - php
 - mtrack
---
<p>
	I've just pushed a somewhat experimental change to the default branch
	of mtrack that enables per-user repositories, repository forking,
	SSH key management, and SSH repository serving integration.
</p>
<p>
	It's important to stress that this is preliminary in as much as
	restrictive controls are missing.  There are some ACLs and rights
	around repo creation that are not yet implemented.  Additionally,
	if you are using OpenID, any authenticated user will be able to
	add an ssh key and access the mtrack created repos (but nothing
	outside of that).
</p>
<p>
	What you get in the current tip:
	<ul>
		<li>Users can create Mercurial, Git or Subversion
		repositories</li>
		<li>Delete your own repositories</li>
		<li>Fork Mercurial or Git repositories</li>
		<li>Associate your SSH keys with your mtrack login</li>
		<li>Integration with SSH and Mercurial/Git/Subversion repo server</li>
	</ul>
</p>
<p>
	What you don't get (yet):
	<ul>
		<li>ACLs to control forking or access within repos</li>
		<li>Pull requests, patch queue management</li>
		<li>Graphs of forks and non-merged changes from forks</li>
	</ul>
</p>
<p>
	If you'd like to try these features, I'd love to hear your feedback.
	To update your instance:
</p>
<pre>
$ hg pull
$ php bin/schema-tool.php
</pre>
<p>
	Then navigate to help.php/SSH (<a href="https://bitbucket.org/wez/mtrack/src/tip/defaults/help/SSH">source text</a>) to read about setting up SSH.
</p>
<p>
	Enjoy!
</p>

