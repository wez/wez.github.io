---
# vim:ts=4:sw=4:et:
layout: post
date: 2011-05-24
title: I'll be at Surge
categories:
 - Conferences
 - Surge
---

I'm pleased to announce that my proposal for the OmniTI Surge 2011
Conference has been accepted!

[TL;DR: read the session description at the Surge site][sess],
otherwise, read on for some background and additional commentary.

<!-- more -->

When I attended the conference last year I wasn't sure quite what to
expect; most of my conferences have been developer focused events, but
Surge was something a bit different.  I would almost go so far as to say
that is was more of a user conference, at least from my perspective.

Most conferences I've been to have been talking about "here's how you
code X to do Y".  At Surge it was "here's a whole lot of pain I had with
product X, and here's how I made it work".  Often, this involved
throwing other components (load balancers, memcache) around the product
to compensate or otherwise workaround a tricky issue.

I've spent a lot of my time over the past few years building products
that have to scale to meet some pretty tough requirements.  I don't
really deal with any specific server or services; I build the software
that other people use as infrastructure to build their own systems and
services.

I went to Surge wondering if I might learn some magic chants that could
affect how we build our bits at Message Systems, but came away slightly
disappointed--there wasn't much being shared about the development side,
but there was a whole lot of shared pain around the Operations side.

Hearing the Ops pain was useful; it gave me more insight into the
types of problems that our users face across their stack and will be
influencing how we continue to build our products.  We started off with
a good dose of observability and operability in our products, and while
we keep identifying areas where we want more, [we've done a good enough
job so far that we earned this shout-out][ywahusty].

This year, then, I'd like you to join me in a session entitled
[Practical Lessons Learned in Scaling at Message Systems][sess].  In
this session I'll be reflecting on some of the more uncomfortable
moments we've experienced in the Message Systems core Engineering group
and how our architecture and philosophy helped, hindered and was
adjusted as a result.

[sess]: http://omniti.com/surge/2011/speakers/wez-furlong
[ywahusty]: http://lethargy.org/~jesus/writes/ywahusty

