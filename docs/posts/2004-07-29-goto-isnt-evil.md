---
layout: post
title: "GOTO isn't evil"
date: 2004-07-29 21:30:52

uniqid: b95881a6-0fda-4c25-877e-e761c8fa4dc6
categories: 
 - PHP
---
<p>In reference to  <a href="http://pecl.org">Sara's patch to implement GOTO in PHP</a>:   </p>
<p>It saddens me that people are so easily brainwashed by their teachers/peers.  Sure, from a high level perspective you don't <b>need</b> a GOTO statement, and the argument that it, when misued, can lead to unreadable code <b>is</b> a good one.   </p>
<p>However, I feel that the inverse argument can also be true: complex code with lots of nested if statements and control loops can be also be hard to follow when you need to &quot;get out of jail&quot; fast.  Without goto you often need to resort to tricks (in C) such as &quot;do { ... break; ...  while (0)&quot;, or if you have nested switch's inside loops, create a flag variable and take several steps out of each nesting level.  There is no way in hell you can convince anyone of sound mind that those tricks make the control flow easier to understand than a GOTO.   </p>
<p>No doubt the anti-goto brigade will campaign that the code could be split up into different functions etc. etc. etc., but it is quite often the case that real programmers <b>need</b> to have this stuff in one function; just take a look at kernel code written in C, for example.   </p>
<p>Arguments against GOTO on the following grounds are, IMO, just a bit short-sighted:   </p>
<p><ul>
<li>I think GOTO is bad because some guy wrote a paper on it
 <li>I was taught not to use it.  (But don't know why; it's supposed to be bad, or something).
 <li>I was taught not to use it because you can use something else.
 <li>You can use exceptions instead; who cares about performance
   </ul>
<p>Regardless of who wrote the paper, or what you were taught, or your disregard for performance (which <b>does</b> have a big impact in the real world; Exceptions are expensive), GOTO is sometimes needed to get the most out of your code.  I'll even go so far as to say that most of the people making these anti-goto comments have no practical experience either of using goto, or with dealing with someone elses misuse of goto; if you don't have experience, please shut the hell up :-)   </p>
<p>GOTO is one of those tools that you don't always need, and one where it is hard to define precisely when you should use it (&lt;insert anecdote about professor who determined only one extremely rare case you can't handle without goto&gt;).  It boils down to what is appropriate for the task (and language) at hand.   </p>
<p><b>Use your brain</b> and evaluate the project--don't just blindly follow what others have said in the past; make up your own mind.  If you truly believe that a goto makes a certain section of code more readable, go ahead and use it.  If you deduce from benchmarks that exceptions, nested loops with flag variables or whatever are too slow or otherwise too expensive, you have have a perfectly valid reason to use a goto instead.   </p>
<p>I'll sum up with a &quot;GOTO doesn't kill code, bad programmers do&quot;. Just because misuse of GOTO can be harmful, it doesn't mean that it shouldn't be part of your toolbox, and for that reason I would welcome a GOTO statement in PHP.   </p>
