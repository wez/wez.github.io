---
layout: post
title: "Jumpstarting PDO"
date: 2009-10-25 13:32:00

uniqid: cae48b90-f5dc-4298-a734-13be0808261f
categories: 
 - PHP
 - PDO
---
<p><a href="http://pooteeweet.org/blog/1565/p/1">Lukas is making another attempt at jumpstarting PDO development</a>.&nbsp; I welcome this effort, and will do what I can to help fill in details and make suggestions.&nbsp; Unfortunately, I'm just way too busy with work to be able to commit to more than that.</p> <p>I also wanted to share some of my thoughts on why PDO has been in a holding pattern for a while, so that more people are aware of it and can work to avoid repeating the same mistakes.</p> <p>The first thing to note is that the guts of PDO were hard to develop.&nbsp; The PHP script facing API sounds simple enough, but the underlying libraries for each different databases work in different ways, and it was and is a challenge to build PDO in such a way that it can work in the most efficient way.</p> <p>The second thing, which is really a follow-on from the first, is that the database libraries are complex and nuanced.&nbsp; Some are relatively simple (especially SQLite and MySQL) and others are complex in divergent ways (ODBC and Oracle).&nbsp; Making a great PDO necessitates having experts in each of those APIs and databases around as contributors, both for the core implementation and for unit tests.</p> <p>Thirdly, there are a lot of databases out there. That requires a lot of resources for the PDO developers to do a good job; not just different database products, but also different versions of those products, need to be tested against.&nbsp; This is also very time consuming.</p> <p>This boils down to a lot of effort.</p> <p>Personally, I enjoy working hard.&nbsp; Tricky problems are the most satisfying kind to solve!&nbsp; However, working hard when there is no payoff is the least enjoyable kind of work.</p> <p>For a successful PDO, there needs to be "payoff" for the contributors to keep them happy and working at it.&nbsp; In a commercial context, where someone sponsors development, the payoff is typically in the form of a paycheck to help cover the bills.&nbsp; In a FOSS context, the payoff may be satisfaction from working on hobby coding, may be vanity in wanting recognition and appreciation from peers or end-users or may be seen as effort to help build out a resume for future career opportunities.&nbsp; There may be other motivating factors too.</p> <p>I've talked in the past about FOSS being "Itch Driven Development".&nbsp; So long as the contributors feel the need to scratch their respective itches, they'll keep on doing it.&nbsp; They'll stop if the itch goes away, or if the scratching leads to bleeding.</p> <p>Bleeding is stuff that detracts from or otherwise lessens the payoff.&nbsp; In a commercial context this could manifest as something that wastes time (and time is money).&nbsp; In a FOSS context, this may also be the result of vocal and/or abusive or otherwise negative sentiments from peers or others in the community.</p> <p>Bleeding is bad for the whole group because it can take a long time for those that were bleeding to want to try their hands at scratching again.&nbsp; Whatever form the renewed PDO development takes, it would do well to try to avoid bleeding.&nbsp; One way to do this is to follow the advice of <a href="http://en.wikipedia.org/wiki/Bill_&amp;_Ted's_Excellent_Adventure">Bill and Ted</a> and "Be Excellent To Each Other".</p> <p>At the end of the day, PDO (and PHP) is largely a volunteer effort, which means that it is something that will be worked on in the spare time of the contributors.&nbsp; Because it is hard work, the payoff needs to be enough for them to individually opt to work on it after a hard day at work instead of choosing alternatives, like family, beer or Xbox.</p> <p>If you're willing and able to help improve PDO by contributing development effort and/or unit tests, please make yourselves known on the <a href="http://news.php.net/php.pdo">PDO mailing list</a>. We need a critical mass to reduce the effort that any one person needs to make, and once we have momentum on our side, I think that PDO can be improved rather rapidly.</p>