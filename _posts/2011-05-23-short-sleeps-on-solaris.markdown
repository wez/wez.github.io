---
# vim:ts=4:sw=4:et:
layout: post
title: Short Sleeps on Solaris
categories:
 - Solaris
---

[Brendan Gregg](http://dtrace.org/blogs/brendan/) raised this question
on Twitter:
[Cruel interview question: write a program on Solaris that sleeps for <
1 ms](https://twitter.com/brendangregg/status/72822508327407616).

The first choice that springs to mind is nanosleep(3RT), but you'll be
frustrated because your requested sleep interval is implemented in terms
of the standard clock frequency, making you sleep for longer.

So how do you sleep (not busy wait!) for time periods shorter than the
regular clock?

<!--more-->

{% highlight c %}

#define _POSIX_PTHREAD_SEMANTICS
#include <stdlib.h>
#include <string.h>
#include <signal.h>
#include <time.h>
#include <stdio.h>

/* get fractional number of seconds difference between two times */
static double compute_time_diff(struct timeval start, struct timeval end)
{
  double diff = end.tv_sec - start.tv_sec;
  diff += (end.tv_usec - start.tv_usec) / 1000000.0;
  return diff;
}

int main(int argc, char *argv[])
{
  struct sigevent evp;
  timer_t timer;
  struct timeval start, end;
  sigset_t set;
  int sig;
  struct itimerspec spec;
  double diff;
  struct timespec ts;

  /* half a ms */
  ts.tv_sec = 0;
  ts.tv_nsec = 500000;

  /* arrange for SIGUSR1 to be delivered when the timer is ready */
  memset(&evp, 0, sizeof(evp));
  sigemptyset(&set);
  sigaddset(&set, SIGUSR1);
  evp.sigev_notify = SIGEV_SIGNAL;
  evp.sigev_signo = SIGUSR1;

  /* create a high resolution timer */
  if (timer_create(CLOCK_HIGHRES, &evp, &timer)) {
    perror("timer_create");
    exit(1);
  }

  /* set it up for a 0.5ms interval */
  spec.it_interval = ts;
  spec.it_value = ts;

  gettimeofday(&start, NULL);
  /* turn it on */
  timer_settime(timer, 0, &spec, NULL);
  /* go to sleep until our signal is delivered */
  sigwait(&set, &sig);
  gettimeofday(&end, NULL);
  timer_delete(timer);

  printf("timer: diff: %f\n", compute_time_diff(start, end));

  /* contrast with nanosleep */
  gettimeofday(&start, NULL);
  nanosleep(&ts, NULL);
  gettimeofday(&end, NULL);
  printf("nanosleep: diff: %f\n", compute_time_diff(start, end));

  exit(0);
}
{% endhighlight %}

When compiled and run, this program prints out something like:

    timer: diff: 0.000575
    nanosleep: diff: 0.009341

My time measurement is a bit coarse, but you can see that the timer
method is very close to 0.5ms (0.0005 seconds), but nanosleep is nowhere
close.

This technique should work on any system that supports the Timers option
of POSIX.

