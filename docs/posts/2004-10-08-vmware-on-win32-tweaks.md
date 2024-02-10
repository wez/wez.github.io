---
layout: post
title: "VMware on win32 tweaks"
date: 2004-10-08 15:03:34

uniqid: 4166ac46-c41c-9263-6947-166ac46cf827
categories: 
 - Windows
---
<p>My laptop has a dual boot xp/linux install.   </p>
<p>For a little while now, I've been running linux 98% of the time, but I've started to miss my music.  In the last couple of days I've tried a number of Ogg/MP3 players on linux and have been a bit annoyed by the memory and cpu usage.  I suspect a combination of bad audio and firewire drivers as being responsible for most of CPU usage.  Anyway, it was making the system unusable, and I need my music.   </p>
<p>So, I've switched back to the XP side of things for now, using vmware to get at the linux install (using a native persistent disk to provide access to the real linux partition).  One of the things that annoyed me about vmware was that NAT networking sucked; it would cause my wifi to drop out and not come back, sometimes, if I rebooted the virtual machine.  I also didn't like the fact that I had to run dhcpd and nat daemons.  In a flash of inspiration today, I made the following adjustments to my config:   </p>
<p><ul>
<li>remove (by unchecking the box) the vmware bridge protocol from all of my network connections, including the vmnet connections.
 <li>for vmnet8 (the default NAT net), disabled NAT and DHCP and set the subnet to 192.168.0.0 in the VMWare &quot;Virtual Network Settings&quot;
 <li>in the Service MMC console, stop the 3 VMWare services (auth, nat and dhcpd). Set auth and nat to manual startup and disable the dhcpd.
 <li>edit your virtual machine config: remove your NAT NIC, then add a new NIC; choose custom networking and set it to use vmnet8
 <li>right click on your internet facing NIC (under &quot;Network Connections&quot;) and turn on internet connection sharing; select vmnet8 as the private network to share.
   </ul>
<p>The net effect (pun not intended ;-) is to have windows perform the nat and dhcpd for vmnet8 natively.  By disconnecting the VMWare Bridge Protocol, vmware leaves your NICs well alone.  I've noticed better stability and less latency using this approach today.   </p>
<p>On the linux side, I run RHEL; for vmware I boot into run level 3 instead of the usual 5.  I've turned off most services in run level 3: acpid, bluetooth, autofs, sshd and all that stuff, leaving just syslog, gpm, vmware-guestd and a couple of mingetty and tweaked rc.local to stop hotplug and unload additional modules, all to reduce the memory footprint of the vm.   </p>
<p>Back on windows, I run the Cygwin X server and use that to serve xterms up from the virtual machine (don't forget to use xhost under cygwin to allow the vm access to the X server).  </p>
