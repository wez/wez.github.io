---
layout: post
title: "mtrack: facelift + other progress"
date: 2010-08-15T23:21:00

uniqid: 9889f2db-1aaa-4bb7-95e5-30c7c187b707
categories: 
 - mtrack
 - PHP
---
<p>
	I've been working on some changes to mtrack (a software development tracker
	implemented in PHP) this weekend, with a focus on
	improving the user experience for the mtrack administrator.  To be brutally
	honest, it was at best a meagre experience for the administrator, but now
	things are better.  They're still not perfect, but it should be a low
	enough bar to encourage more folks to play with mtrack.  I've also spent a
	little bit of effort to add some caching to improve performance for certain
	pages.
</p>
<p>
	For those that have tried it in the past, there's a brief summary of
	what's changed and a couple of screen shots below.  This effort is
	in alignment with the <a href="/blog/2010/jul/mtrack-roadmap">roadmap</a> that I published a little while ago.
</p>
<!--more-->
<h2>Changes</h2>
<ul>
	<li>Fleshed out the Administration section with some more explanatory
		text and made some (but not yet all) of the CRUD screens a little
		more pleasant to use</li>
	<li>Added an "Admin Party" mode for fresh installs so that it is easier
		to find and use the admin section if they're connecting from
		the localhost. Non-local users are told to connect locally
		to make configuration changes</li>
	<li>Added role assignment to the user management screen</li>
	<li>Added authentication configuration screen to allow HTTP or OpenID
		authentication to be enabled</li>
	<li>When using mtrack for HTTP auth, users may change their passwords.
		When using the web server for HTTP auth, mtrack will respect the
		authentication but will not allow the editing of passwords</li>
	<li>Fixed a bug that meant that svn diffs would not work on Mac OS/X</li>
	<li>Fixed a bug in the Jan Rain OpenID library that prevented it from
		working with PHP 5.3</li>
	<li>Added primitive import-tickets-from-CSV file facility; useful for
		people that are transferring requirements into mtrack tickets</li>
	<li>Changed visual styling to be a little less like trac</li>
</ul>

<h2>Where to get it</h2>

<p>
	<a href="http://bitbucket.org/wez/mtrack">You can find the code here</a>
</p>

<h2>The Wiki starting page</h2>

<img src="/images/mtrack-splash-aug-2010.png" style="border:solid 1px #ccc">

<h2>The Admin section</h2>

<img src="/images/mtrack-admin-aug-2010.png" style="border:solid 1px #ccc">
