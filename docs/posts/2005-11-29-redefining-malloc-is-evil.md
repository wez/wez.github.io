---
layout: post
title: "redefining malloc() is evil"
date: 2005-11-29 01:53:46

uniqid: 438bb4aa-aac4-4467-1347-38bb4aa0f09f
categories: 
 - Blog
---
<p>Especially when it's in an external library, like say, perl.  It's a recipe for heap corruption when pointers allocated by the real malloc are passed to the redefined free and vice versa.  Nasty.  Whats worse is when that library redefines malloc(), free(), calloc() and realloc(), but leaves strdup() alone.   </p>
<p>Guess how long it took to track that one down on windows, where <a href="http://valgrind.org/">valgrind</a> is sorely missed?  Too long, that's how long.   </p>
<p>How did we live without valgrind!?  </p>
