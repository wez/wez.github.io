---
layout: post
title: "mtrack repo ACLs"
date: 2010-10-10T18:01:00
updated: 2010-10-11
uniqid: 70c03b0d-fc8d-4928-83a7-4b3301f7278f
categories: 
 - mtrack
 - php
---
<p>Updated: Added schema and UI for Projects to own repos and manage groups.</p>
<p>
	Following through on user repos and SSH, I revised the ACL support
	in mtrack and added a permissions editor for the repo editing screen.
	When you create a repo, it defaults to granting you (the creator) full
	access and denying everybody else access (equivalent to a private repo
	in services like Github and Bitbucket).
</p>
<img src="/images/mtrack-repo-perms-edit-oct-2010.png"
	style="border:solid 1px #ccc">
<!-- more -->
<p>
	These permissions are respected by the SSH plumbing, so you can prevent
	checking in or out on a repo-by-repo basis.
</p>
<p>
	Another important change in the tip is that the "admin" role now
	magically short-circuits permission checks in a fashion similar to the
	root user on unix systems.  This is important because the permission
	editor opens things up for you to remove your own rights on a repo,
	and can effectively make it vanish... so you'll need a super user role
	to get those rights back!
</p>
<p>
	To better manage permissions and collaboration, you may now create or
	fork a repo and mark it as belonging to a Project (you need to have
	created the project prior to creating the repo).  Additionally,
	the Project admin screen has been expanded to allow you to define
	groups and assign users to those groups.  Each Project has its own
	set of groups.  You can discover the groups to which a user belongs
	by clicking on the username and then the "Edit Details" button.
</p>
