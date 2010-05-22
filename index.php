<?php # vim:ts=2:sw=2:et:
include 'inc/layout.php';

wfo_head('');

wfo_box('atwork', array(
  <<<HTML
<p>
  <b>What I do at work:</b>
  I'm responsible for ensuring that the 
  architecture of Message Systems' software continues to meet the
  performance, scalability and flexibility demands placed upon it by clientele
  such as
  <a href="http://facebook.com">Facebook</a>,
  <a href="http://salesforce.com">Salesforce</a>,
  <a href="http://match.com">Match.com</a>
  and <a href="http://messagelabs.com">MessageLabs</a>.
</p>
HTML
  , <<<HTML
<a href='http://messagesystems.com' alt='Message Systems, Inc.'><img
  src="images/msys.png"></a>
HTML
  ));


wfo_box('atplay', array(
  <<<HTML
<p>
  <b>When not at work:</b>
  I'm fascinated by software and obsess about
  building better tools and applications.  A good deal of this energy
  goes towards <a href="http://opensource.org">OpenSource</a> software
  (I'm known for my work on the <a href="http://php.net">PHP</a> core, among
  other projects), but like to unwind with a mix of good movies, terrible movies, XBox-360 and a single-malt.
</p>
HTML
  ));

wfo_box('elsewhere', array(
  <<<HTML
<p>
  <a href="{$WEBROOT}blog/"><b>Read my Blog &hellip;</b></a>
</p>
<p>
  <a href="{$WEBROOT}bio.php"><b>Read more about me &hellip;</b></a>
</p>


HTML
  ));

wfo_foot();

