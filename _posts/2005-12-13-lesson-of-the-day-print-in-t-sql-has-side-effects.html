---
layout: post
title: "Lesson of the day: PRINT in T-SQL has side effects"
date: 2005-12-13 02:03:01

uniqid: 439e2bd5-5622-8296-5468-39e2bd5e9ef8
categories: 
 - PHP
---
<p>Not strictly PHP this one, but worth mentioning to the people that use SQL Server from PHP.  This is an example based on a stored procedure that I spent too much time debugging today:   </p>
<pre>   SELECT * FROM FOO WHERE FOOID = @FOOID
   PRINT @FOOID
   IF @@ROWCOUNT = 0
       RAISERROR('BAD')
</pre><p>This always fails--why? The PRINT statement resets the @@ROWCOUNT variable back to 0.  Nasty eh?   </p>
<p>On a related note, PRINT generates 'SUCCESS_WITH_INFO' diagnostic records (one for each print call), so you can capture the output over ODBC.  A gotcha here is that, with SQL Server at least, once you start pulling diagnostic records, you must pull them all, otherwise you block your database connection, leading to invalid cursor state errors when you try to fetch.   </p>
<p>Double bad day for PRINT in SQL Server for me.   </p>
<p>  </p>
