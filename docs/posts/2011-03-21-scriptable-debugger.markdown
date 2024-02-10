---
# vim:ts=4:sw=4:et:
date: 2011-03-21
layout: post
title: Scriptable Debugger
categories:
 - Gimli
---

[Adam Leventhal](http://dtrace.org/blogs/ahl/) mentioned on twitter that he'd
found my [umem slab allocator](http://messagesystems.github.com/umem/) port on
macports.  I commented that he might miss some of the umem debugging features
that are available on its native Solaris platform in mdb, and we dreamed
briefly of a portable version of mdb.

This weekend I took a peek at the mdb sources (via the [Illumos
Gate](http://hg.illumos.org/illumos-gate)) to see how hard this might be.  As
it happens, this isn't the first time I've looked at porting mdb, so I was bit
better prepared to go snooping around in there.

<!--more-->

It didn't look much easier to port this time around than last time... there are
a number of fairly Solaris specific bits in there that would make it a
challenge, and add in that my hobby-hacking platform these days is Darwin
(OS/X) with MACH-O binaries rather than ELF meant that it would take a few
hours just to get something to partially build.

I decided that what I really wanted wasn't actually mdb, but a debugging tool
that made things easier for me to actually debug with.

Some of my earlier angst in this area resulted in
[Gimli](https://bitbucket.org/wez/gimli), which includes "glider", a
pstack-on-steroids that uses DWARF debugging information to provide a deep
stack trace and has a C API for building tracer modules to extract more details
from a crashing (or even a running) process.  The primary purpose of Gimli is
to give insight into faulting software on production systems where it may not
be possible to run a full interactive debugger for various reasons.

So, what if I took the debugger core from Gimli and wrapped it up in a
scripting language that exposed its DWARF assisted introspection capabilities?

I gave it a whirl; I added in [Lua](http://lua.org) as the scripting language
because it has a wonderful mixture of ease of embedding, dynamic object binding
via metatables and a nice collection of powerful language features.

Here's an example script that demonstrates some of the debugger capabilities:

{% highlight lua %}

function spawn(...)
    local pid = posix.fork();
    if pid == 0 then
        posix.execp(...)
        os.exit(1)
    end
    return pid
end

-- wedgie is a faulting program in the gimli distro
pid = spawn("./wedgie");

print("Running wedgie, waiting for it to wedge");

posix.sleep(4);

print("Attaching to pid", pid);
ldb.attach(pid);

-- ldb.threads is an object that can be indexed to directly
-- address a numbered thread, or when called, acts as a generator
-- that iterates over the threads
for thr in ldb.threads do
    -- the tostring handler emits details, including the LWP id
    print(thr)
    -- thr.frames is a generator similar to ldb.threads, but
    -- accesses the stack frames from the corresponding thread
    for f in thr.frames do
        -- thr.is_signal is a boolean property that indicates if
        -- gimli knows that this frame is a signal trampoline
        if f.is_signal then
            print(" ** signal handler **")
        else
            -- label prints a fully qualified path to the object
            -- file containing the PC address from this frame,
            -- the closest symbol (typically the function name)
            -- and offset from that function
            print(f.label)
            -- f.vars is a generator similar to ldb.threads,
            -- returning information about the parameters and
            -- variables visible in the stack frame
            for varname, isparam, var in f.vars do
                -- isparam indicates if this is a formal
                -- parameter (true) or a local variable
                -- ldb.type_c() returns the C-style type string
                print(varname, isparam, ldb.type_c(var))
                -- this bit shows how to drill into a structured
                -- type; the signal handler in the wedgie code
                -- takes a siginfo_t parameter called "si"; we'll
                -- drill into it here
                if varname == "si" then
                    print("looking in struct siginfo")
                    -- this dereferences the pointer to the
                    -- siginfo, then iterates the members of
                    -- the structure and prints them out
                    for k, v in ldb.deref(var) do
                        print("", k, v)
                    end
                end
            end
        end
    end
end

posix.kill(pid, 9);

{% endhighlight %}

Running the script gives output like this on a Cento 5.5 x86_64
system (just a snippet is shown):

{% highlight text %}
% ./ldb t.lua
...
Attaching to pid    23997
thread:tid=0:LWP=23997:frames=10
/lib64/libc-2.5.so`nanosleep+10
/lib64/libc-2.5.so`sleep+93
/Users/wez/msys/gimli/.libs/lt-wedgie`handler+9f
signo   true    int
si  true    siginfo_t *
looking in struct siginfo
    si_signo    11
    si_errno    0
    si_code 1
    _sifields

{% endhighlight %}


The output and facilities are still very early stage, but they
hint at the power available under the covers.  One thing the main
script above doesn't show is how you can drill into vars; we could
do something like this:

{% highlight lua %}
si = ldb.deref(f.vars.si)
print(si.si_signo);
{% endhighlight %}


This shows how we can index into the structure fields by name;
this is all made possible via the DWARF debugging information
baked into the object file.

Because this is all wrapped up in a scripting language it's possible
to harness this to automate a debugging session (as demonstrated above),

It is also possible to make this into an interactive debugger--in fact,
it already is; you can run ldb with no arguments and it will enter a
[REPL](http://en.wikipedia.org/wiki/Read-eval-print_loop) and let you
type in lua expressions.

Future
======

I think this is really quite a cool concept, and will be following through
to flesh this out more fully.  What I'm also interested in doing is adding
in some more functionality to make it a halfway decent interactive debugger.

My personal debugger needs tend to be focused on inspecting a target
process and peeking around in it, stopping and stepping.  I don't tend
to use destructive debugger commands (pokes).

I asked Adam what his top features were in mdb, if only because my mdb-fu
pales in comparison to his, and he gave me this list:

 * \:\:print - *print out the argument*
 * \:\:list - *walk a linked list structure*
 * \:\:findleaks - *walks heap looking for leaks*
 * \:\:typegraph - *infer the type for an address*
 * being able to program your own dmods

What I think is achievable in fairly short order is a reasonably flexible
interactive shell and the ability to write your own extension functionality in
lua.

On top of this framework it would be possible (and potentially very natural
and easy) to write extensions that do things like reach into the VM of
embedded languages and display augmented stack traces with both the C and the
VM trace alongside each other, to replicate the mdb dcmds above,
as well as others that work with umem to help diagnose memory issues.

*What would you like to see in a debugger?*

[Bryan Cantrill](http://dtrace.org/blogs/bmc/) adds:

> * \:\:stacks
> * \:\:walk
> * \:\:whatis
> * \:\:whattype
> * \:\:findfalse


[@alexr](http://twitter.com/alexr) adds:
> [@bcantrill](http://twitter.com/bcantrill) [@ahl](http://twitter.com/ahl) [@wezfurlong](http://twitter.com/wezfurlong) I'd rather you do all that to LLDB. :-)

Now [lldb](http://lldb.llvm.org/) sounds interesting, but for me it is rather
appealing to be able to script this stuff and not *have* to reach for a C (or
in this case, C++) compiler to write a module to get at some guts.

#### Quick note about Gimli

You can find [Gimli on BitBucket](https://bitbucket.org/wez/gimli), complete
with the work-in-progress lua branch.

Gimli is BSD licensed.

Comments and suggestions are welcome!

