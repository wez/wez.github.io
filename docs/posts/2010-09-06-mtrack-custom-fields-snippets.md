---
layout: post
title: "mtrack: custom fields, snippets"
date: 2010-09-06T23:32:00

uniqid: 87dc8280-4611-457d-9c6f-21e8ecc474e6
categories: 
 - mtrack
 - PHP
---
<p>
	It's time for another mtrack update; here's what's new:
</p>
<ul>
	<li>Add "Snippets" feature; works like pastebin, but allows for comments to be supplied in wiki syntax in addition to the code or text snippet that you're pasting</li>
	<li>Add Custom Field support. This is implemented by modifying the schema
		(custom fields always have an "x_" prefix).</li>
	<li>Improvements to the "custom ticket query" screens, including
		ability to select which columns (including custom fields)
		are included in the results</li>
	<li>Fix an issue with sorting the "Remaining" time column</li>
	<li>Fix some IE compatibility issues</li>
	<li>Improve presentation of tickets in read-only mode</li>
	<li>Refactor search engine code and allow the use of Apache Solr as
		an engine.  Indexer logs are now viewable in the Admin section</li>
	<li>Improve perceived search engine performance by forcing it to work
		in smaller batches</li>
	<li>You may now delete attachments from both wiki pages and tickets</li>
	<li>Add post commit hook for the wiki repo, so that changes made outside of
		the mtrack UI are noticed and indexed</li>
	<li>Improve diff visualization</li>
	<li>Add syntax highlighting facility</li>
	<li>File view now allows blame and line numbers to be turned on or off,
		and renders with syntax highlighting</li>
	<li>Allow optional "hour", "hours" and "hrs" unit after the spent time
		command in the commit hook (Thanks Andrei!)</li>
	<li>Display remaining time as 0 if the ticket is closed</li>
</ul>

<!--more-->

<p>
	If you have an existing installation, you will need to carry out the
	following steps to enable the snippets feature:
</p>
<ol>
	<li>Add the Snippets ACL root<br>
		<pre>php bin/add-acl-object.php --config-file /path/to/config.ini Snippets</pre>
	</li>
	<li>Add the Snippets table<br>
		<pre>sqlite3 /path/to/var/mtrac.db
CREATE TABLE snippets (
        -- snippet id
        snid text not null,
        -- ref. to changes table
        created INTEGER NOT NULL,
        updated INTEGER NOT NULL,
        -- summary/blurb in wiki markup
        description text not null,
        -- what language?
        lang text not null,
        -- and the snippet itself
        snippet text not null,
        primary key (snid)
);
</pre>
	</li>
	<li>Grant rights to Snippets; append the <tt>SnippetCreator</tt> to your <tt>admin</tt> and <tt>authenticated</tt> user class roles in your <tt>config.ini</tt>; I've included the new defaults below:
	<pre>
; Defines some basic, reasonable, permission sets for 3 classes of user.
; These are used in addition to whatever is selected by auth plugins
[user_class_roles]
anonymous = ReportViewer,BrowserViewer,WikiViewer,TimelineViewer,RoadmapViewer,TicketViewer
authenticated = ReportViewer,BrowserViewer,WikiCreator,TimelineViewer,RoadmapViewer,TicketCreator,UserViewer,SnippetCreator
admin = ReportCreator,BrowserCreator,WikiCreator,TimelineViewer,RoadmapCreator,TicketCreator,EnumerationCreator,ComponentCreator,ProjectCreator,UserCreator,SnippetCreator
</pre>
	</li>
</ol>

<h2>Where to get it</h2>

<p>
	<a href="http://bitbucket.org/wez/mtrack">You can find the code here</a>
</p>

<h2>Snippet Creation</h2>

<img src="/images/mtrack-snippet-create.png" style="border:solid 1px #ccc">

<h2>Snippet View</h2>

<img src="/images/mtrack-snippet-view.png" style="border:solid 1px #ccc">

<h2>Ticket View</h2>

<img src="/images/mtrack-ticket-view-sep-2010.png" style="border:solid 1px #ccc">
