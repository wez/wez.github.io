---
layout: post
title: "Authentication services"
date: 2006-05-08 02:36:58

uniqid: 445eaeca-230f-2054-5625-45eaeca84f5e
categories: 
 - Evil Blog
 - PHP
---
<p>When I designed the back-end for this blog, I built in the capability for understanding multiple authentication sources.  It currently supports three different sources: one of which is local and the other two are CVS servers.  By supporting remote authentication sources, I spare myself from having to implement a lot of the user management gumph that is needed to support it (email address verification, password management and so on).  While this is good for me, if the authentication is perceived as happening on my site, people don't feel quite so comfortable entering their off-site credentials, because they don't really know what I do with their data.   </p>
<p>I'm currently going through one of those phases where I'm thinking about what I'd put into &quot;netevil 2.0&quot;, and one of those things is adopting support for authenticating against well-known external sites.  Ideally, I'd like people to be able to login to Yahoo or Google and then have some way for my blog to determine a subset of their profile data when they post a comment.   </p>
<p>This single-sign-on (SSO) concept is nothing new; Microsoft's passport has been around for quite some time now, and there are newer open specifications being designed by <a href="http://sxip.net">SXIP</a> and <a href="https://www.projectliberty.org/index.php">the Libery Alliance</a>.  Both of these projects are working on IETF draft standards for identity management and federation protocols to facilitate SSO.  SXIP is very open and has an implementation in PHP that you can download and use.  Liberty feels somewhat closed, and has no reference implementation in any scripting language, which immediately creates quite a high barrier to entry for a large portion of the web developer population.   </p>
<p>So, we have one established SSO provider (MS passport) and two entities developing SSO technology.  Why haven't I seen any sites, aside from passport enabled sites, using anything like this stuff?  I think part of the problem is that SXIP and Liberty are providing the technology but not providing the actual authentication services.  Taking SXIP as an example, if I want to SXIP enable my site I need to direct users to a SXIP homesite where they can create an identity, and which can then authenticate them with my blog.  The problem is that there aren't really any SXIP homesites out there, so I'd need to implement one myself, and we're back at square one.   </p>
<p>I think it would be a huge thing if the big guys (yahoo, google) could implement something like SXIP and allow third-party applications to authenticate users against them. Yahoo is almost there already--if you look at the <a href="http://flickr.com/services/api/">Flickr API</a> you'll see that you can have flickr authenticate users and provide your application with an authentication token (subject to approval from the user).  From that token you can obtain the name of the user, and use that to render the name of the person submitting comments to your site.   </p>
<p>It'll be interesting to see what, if any, developments are made in this area.   </p>
<p>  </p>
