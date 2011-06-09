---
# vim:ts=4:sw=4:et:
layout: post
title: Two Factor SSH on Joyent SmartMachines
categories:
 - Solaris
 - Joyent
---

After reading [this scary blog entry about domain hijacking](http://www.secretgeek.net/sg_hijack_1.asp)
I've been a bit
concerned about brute forcing of credentials and have been turning on
the two-factor authentication facilities that folks like Google provide
for my gmail and personal domains.

I've just found out about [Duo Security](http://www.duosecurity.com/), a
service that allows you to add two-factor authentication to your SSH
server, Juniper VPN and even Wordpress blogs.  Their service is free for
up to 10 users and they start charging when you pass that threshold.

Read on to find out how to set it up.

<!--more-->

I set it up this evening on my Joyent SmartMachine, but the procedure
should translate to pretty much any other unix system--Solaris is
slightly more involved because it doesn't ship with OpenSSH.

The first thing to do is to set up an integration; the Duo Security site
has the details on this.  An integration is basically an authentication
domain; you set one up for a service and then you can associate users
with it.

With that done (it's only a few clicks and a text message) you can set
things up on your server; there is a tarball to download from github
that contains the sources to a PAM module and an alternative "login"
program that call back to the two-factor service.

Copy the tarball up to your server and build it:

      % scp duo_unix-1.6.tar.gz myserver:
      % ssh myserver
      % tar xzf duo_unix-1.6.tar.gz
      % cd duo_unix-1.6
      % ./configure
      % make
      % sudo make install

This builds out-of-the-box.

The next thing you need is to provision OpenSSH.  This is not the
default for Solaris, so we need to obtain and install it via other
means.  On the Joyent SmartMachine system, we can simply install it from
the pkgsrc provided by Joyent:

       % sudo pkgin install openssh

Now the slightly tricky part; we want to configure this and make sure it
works before we replace the system ssh service with it.  We're going to
run it on an alternate port to test it.

Edit the config file:

       % sudo vi /opt/local/etc/ssh/sshd_config

and add the following lines:

       Port 8080
       ForceCommand /usr/local/sbin/login_duo

Now start it up:

      % sudo /opt/local/share/examples/rc.d/sshd start

Don't try and login yet; we need to configure the
/etc/duo/login_duo.conf file to match the settings for your integration.
Go to the dashboard on the Duo site and call up the details for your
integration; you need to transfer the ikey, skey and host settings to
your config file:

      % sudo vim /etc/duo/login_duo.conf

The default looks like this; fill in the blanks:

        [duo]
        ; Duo integration key
        ikey = 
        ; Duo secret key
        skey = 
        ; Duo API host
        host = api-eval.duosecurity.com
        ; Send command for Duo Push authentication
        ;pushinfo = yes

Now fix the ownership and permissions:

       % sudo chown sshd /etc/duo/login_duo.conf
       % sudo chmod 600 /etc/duo/login_duo.conf

And test that the authentication works:

       % login_duo echo YOU ROCK
       Please enroll at https://api-XYZ.duosecurity.com/portal?XYZ

Go to the URL and enroll yourself as a user.  This is a straight-forward
multi-step process; you register yourself and your cell phone and have
the option of completing this via text message or the smartphone apps
(iPhone, Android and others that I didn't care about).

When your enrollment is complete, try it again; you'll have to confirm
the login via your phone:

        % login_duo echo YOU ROCK
        Duo login for wez

        1. Duo Push to XXX-XXX-9390
        2. Phone call to XXX-XXX-9390
        3. SMS passcodes to XXX-XXX-9390

        Passcode or option (1-3): 1

        Pushed a login request to your phone...
        Success. Logging you in...
        YOU ROCK

Now back to SSH; test our OpenSSH server, again, you need to confirm the login via your phone:

        % ssh -p8080 myserver
        Duo login for wez

        1. Duo Push to XXX-XXX-9390
        2. Phone call to XXX-XXX-9390
        3. SMS passcodes to XXX-XXX-9390

        Passcode or option (1-3): 1

        Pushed a login request to your phone...
        Success. Logging you in...
        myserver %

You should land in a shell on the server.

If all is good, you can switch the system to use your OpenSSH server.
Make sure you have a couple of SSH sessions open to the system before
you start this bit; if you mess up, you may not have an SSH server
running, and you'll need to contact Joyent for help:

       % sudo ln -s /opt/local/share/examples/rc.d/sshd \
           /etc/rc.d/sshd
       % sudo svcadm disable ssh

At this point, you are no longer running the regular ssh server, but have
OpenSSH running on an alternate port.  We configured it to run on startup by creating that symlink, but we need to switch it to the regular port; fix that by editing the config file:

       % sudo vi /opt/local/etc/ssh/sshd_config

And change the Port line:

       Port 22

Now to restart our ssh server:

       % sudo /etc/rc.d/sshd stop
       % sudo /etc/rc.d/sshd start

And then verify the login; you will probably need to fix your
known_hosts file to reflect that OpenSSH generated a new server key for
you--advanced users can probably copy the system key.

       % ssh myserver
       Duo login for wez
       ....

And voila, we're all done; now no one can log in to the system without
confirming it through a secondary channel, making it that much more
difficult for your account credentials to be stolen or guessed and exploited.

Additional exercises for the reader are to reboot to make sure that ssh
comes up after a reboot, and to look at setting up an SMF manifest
rather than a legacy init script.


