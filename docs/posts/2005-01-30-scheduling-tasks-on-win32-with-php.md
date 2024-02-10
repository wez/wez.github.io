---
layout: post
title: "Scheduling tasks on win32 with PHP"
date: 2005-01-30 22:05:01

uniqid: 41fd5a0d-c4b1-8016-3085-1fd5a0db22e8
categories: 
 - PHP
 - Windows
---
<p>The other product of last night was another win32 only extension, this time for manipulating scheduled tasks.  Why do you need an extension? The reason is that Microsoft chose to expose the &quot;mstask&quot; API as non-automation compatible COM APIs; that means that you can't simply use the PHP COM extension (which is really &quot;PHP-OLE&quot;) to work with it.   </p>
<p>The win32scheduler extension provides 5 functions:   </p>
<p><ul>
<li>win32_scheduler_enum_tasks() - returns an array containing the names of the scheduled tasks that are present on the system.
 <li>win32_scheduler_run(string taskname) - requests that the system run a given task immediately.  It does not wait for the task to complete.
 <li>win32_scheduler_get_task_info(string taskname) - returns information about a given task.
 <li>win32_scheduler_set_task_info(string taskname, array info [, bool new]) - updates or creates a new task.
 <li>win32_scheduler_delete_task(string taskname) - deletes a named task.
   </ul>
<p>It's fairly self explanatory, although the details for the task info are a little weird.  I'll document those for PHP at a later date, but if you're interested in working with it now, you'd do well to <a href="http://msdn.microsoft.com/library/default.asp?url=/library/en-us/taskschd/taskschd/task_scheduler_start_page.asp">read the MSDN docs on the Task Scheduler</a>; the <a href="http://msdn.microsoft.com/library/default.asp?url=/library/en-us/taskschd/taskschd/task_scheduler_structures_and_unions.asp">structures and unions</a> docs will help you to figure out the task info format.  If you want to create a task, you'll find it helpful to var_dump() the info returned from an existing task; the set_task_info() function uses the same data format.  Top tip: you need to supply a &quot;Password&quot; field and set it to the password for the user account you set in &quot;RunAs&quot;.   </p>
<p>This extension should also be showing up on <a href="http://snaps.php.net/win32/PECL_5_0/">the PHP 5 PECL snaps</a> page in the next couple of hours.   </p>
<p>Enjoy!  </p>
