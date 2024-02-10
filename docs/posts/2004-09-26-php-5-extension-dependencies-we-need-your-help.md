---
layout: post
title: "PHP 5 Extension Dependencies - we need your help"
date: 2004-09-26 20:41:31

uniqid: 3bb2f7f2-e4b9-413a-a6b0-d3f47b702d10
categories: 
 - PHP
---
<p><b>update</b> (in response to a recent comment): this patch is already in the 5.1 tree; please test that instead; thanks!   </p>
<p>[patch updated again: if you have a BSD-ish system that complained about &quot;making too many open files&quot;, please try it again]   </p>
<p>[patch updated: please try it again.  Note that if you only have &quot;mawk&quot; you should install GNU awk instead, as mawk is broken]   </p>
<p>I've created <a href="http://www.php.net/~wez/configure-deps.diff">this patch</a> to force extensions with dependencies on other extensions to be initialized in the correct order when they are compiled statically into the core of PHP.   </p>
<p>It's known to work on Linux (and Tru64--thanks Magnus), but we'd <b>really</b> like to know if it works on other systems too--we don't have access to them, and we <b>really</b> want to get this into PHP 5.0 (due in July!).   </p>
<p>So, please test it if you have SunOS, Solaris, BSD, HP UX, AIX, IRIX or any other &quot;weird&quot; unix systems.   </p>
<p>Instructions:   </p>
<p>Checkout PHP 5 (or download a snapshot from snaps.php.net)   </p>
<p>Apply the patch:   </p>
<pre>   cd php5
   curl http://www.php.net/~wez/configure-deps.diff | patch -p0
   ./configure
</pre><p>Now look in main/internal_functions.c and/or main/internal_functions_cli.c   </p>
<p>You should see something like this in the file.  Each line corresponds to the extensions you enabled through configure; your list may be longer or shorter depending on how much stuff you compile into PHP.   </p>
<pre>   zend_module_entry *php_builtin_extensions[] = {
	phpext_libxml_ptr,
	phpext_xml_ptr,
	phpext_tokenizer_ptr,
	phpext_sysvshm_ptr,
	phpext_sysvsem_ptr,
	phpext_sysvmsg_ptr,
	phpext_standard_ptr,
	phpext_sqlite_ptr,
	phpext_simplexml_ptr,
	phpext_spl_ptr,
	phpext_session_ptr,
	phpext_posix_ptr,
	phpext_pcre_ptr,
	phpext_pcntl_ptr,
	phpext_gd_ptr,
	phpext_ftp_ptr,
	phpext_exif_ptr,
	phpext_ctype_ptr,
	phpext_calendar_ptr,
	phpext_bz2_ptr,
	phpext_bcmath_ptr,
	phpext_zlib_ptr,
};
</pre><p>If you see libxml before xml, it's worked. If you see repeated entries, it broke.   </p>
<p>Please try building it now too.   </p>
<p>If it all works, please either comment here, or send an email to internals@lists.php.net indicating which platform you used.   </p>
<p>If it breaks while compiling main/internal_functions.c and/or main/internal_functions_cli.c, please let me know by email to <b>wez@php.net</b>.   </p>
<p>Prefix the subject with [DEPS-PATCH], and include the name of your platform and the internal_functions.c file.   </p>
<p>Thanks for helping to make PHP 5 better :-)   </p>
