---
layout: post
title: "mtrack: burndown and postgres"
date: 2010-10-06T02:28:00

uniqid: a7b2be66-6b64-489b-8082-6af77322b385
categories: 
 - php
 - mtrack
---
<p>
	It's that time of the month... mtrack update time.  Not a huge number
	of items to report on this time around, but it's a decently significant
	stepping stone--the schema management code takes us one step closer
	to an upgrade/deployment process.
</p>
<ul>
	<li>If you don't explicitly include the burndown macro text in a milestone,
		a default burndown graph will now be displayed when the milestone
		summary is rendered in the roadmap section.</li>
	<li>Added schema management code to handle upgrades that change the schema</li>
	<li>Added preliminary postgres DB support on top of the schema management
		code.</li>
	<li>Revised attachment processing so that the database stores a
		canonical copy of each attachment.  This step helps to enable multiple
		front-end web servers to be used with the same backend pgsql DB.</li>
	<li>Created a stable branch that holds the changes up to just before
		the PostgreSQL DB changes</li>
</ul>

<!-- more -->

<h2>HOWTO: Use the schema manager to upgrade your instance</h2>

<p>
	After you have pulled the latest code, run the schema tool to bring
	your database instance up to date.  With any upgrade where you are
	modifying a database, you should make sure that you have taken a
	backup before the upgrade is performed.
</p>

<pre>
% php bin/schema-tool.php
</pre>

<h2>HOWTO: Install mtrack using PostgreSQL as the backend</h2>

<p>
	<em>mtrack does not currently have a way to migrate an existing
	SQLite installation to PostgreSQL.</em>
</p>

<p>
	<em>PostgreSQL support is preliminary and not fully tested. The basics
	work for me in my development copy--I'm not aware of any issues
	at this time.  Feedback is welcome!</em>
</p>

<p>
	First, create a postgres database for use with mtrack:
</p>
<pre>
% createdb -E=UTF-8 mtrack
</pre>

<p>
	Second, initialize your mtrack instance, specifying the DSN:
</p>

<pre>
% php bin/init.php --dsn "pgsql:dbname=mtrack;user=wez"
</pre>

<p>
	And you're all set.
</p>
<p>
	The DSN can be any valid PDO PostgreSQL connection string.
</p>

<h2>A Note on database support</h2>

<p>
	PostgreSQL support was possible to implement due to the similarities
	between SQLite and PostgreSQL SQL syntax.  I have no plans to personally
	work on other databases, and will be resistant to the idea of big patches
	to mtrack to make it work with databases with radically different SQL
	syntax on the grounds that I can't test those.
</p>
<p>
	I'd be looking for somone to commit to maintaining their database of
	preference with mtrack before I will allow non-trivial changes into the
	main code base.
</p>
<p>
	This doesn't mean I won't consider having mtrack supporting database X;
	I just won't personally support database X--there will need to be a
	champion that is willing to commit to supporting that system for it
	to be part of the codebase.
</p>
