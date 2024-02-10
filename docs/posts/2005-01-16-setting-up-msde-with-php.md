---
layout: post
title: "Setting up MSDE with PHP"
date: 2005-01-16 16:52:49

uniqid: 41ea9be1-75e4-7848-6701-1ea9be1cb993
categories: 
 - Blog
---
<p><ul>
<li>Download it and run it to extract it
 <li>Open up a command prompt
 <li>cd \\MSDERelA
 <li>setup.exe SQLSECURITY=1 SAPWD=yoursecretpassword
 <li>cd \\Program Files\\Microsoft SQL Server\\80\\Tools\\Binn
 <li>SVRNETCN.exe
 <ul>
<li> - enable TCP
 </ul>
<li>net start mssqlserver
   </ul>
