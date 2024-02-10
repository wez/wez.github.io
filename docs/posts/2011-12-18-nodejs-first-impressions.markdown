---
# vim:ts=4:sw=4:et:
date: 2011-12-18
layout: post
title: Node.js - First Impressions
categories:
 - nodejs
---

I've spent some of my personal time over this past week looking into
[Node.js](http://nodejs.org).  I'll be up-front in stating that my
efforts have not been particularly broad, but I have gone reasonably
deep into the parts that I looked at.

I've been building evented systems for more than a decade, mostly in the
"C" language, so Node is particularly interesting to me; it blends an
evented I/O model with the Javascript language in such a way that it
doesn't feel like a continual struggle.

To get a feel for Node, I decided to look at what it would take to get
some kind of [mtrack](http://mtrack.wezfurlong.org) implementation
running on Node; don't get too excited, I didn't finish anything worthy
of public scrutiny.

For the purposes of the experiment I selected
[Riak](http://basho.com/products/riak-overview/) for the data storage
solution; it has a compelling mixture of document storage, full-text
searching, secondary indices and map-reduce facilities.

<!-- more -->

Serving web pages
-----------------

There's a nice hello-world example on the Node homepage.  I wanted to
find and use something that would give me some flexibility and structure
in setting up the various "pages" in my project.

Google and [NPM](http://npmjs.org) led me to
[Express](http://expressjs.com/), a web development package built on top
of a *middleware* package called
[Connect](http://github.com/senchalabs/connect).  Connect focuses on
allowing the application developer to plug, stack and otherwise compose
processing modules (such as form parsing, authentication, sessions and
so forth) together.  Express extends this with some additional
conveniences that allow for terse but expressive configuration of how
the application should behave.

It's a little weird, coming from a PHP web dev background, to have to
explicitly enable things like session, form and cookie parsing but at
the same time it makes a lot of sense.  If you're not using those, you
simply leave them out.  If you need to change the default behavior, you
simply load an alternative implementation, or even build it yourself.

Authentication
--------------

mtrack relies on knowing who is accessing the system; there's very
little functionality that makes sense for anonymous users in its primary
deployment audience; professional developers.

Aside from Basic HTTP authentication, most web authentication systems
require some kind of session storage to record who they're talking to,
so the first step is to enable session storage.

As I mentioned above, I wanted to use Riak as my data store; it has
integrated replication that would allow the web service to be load
balanced across any nodes in one's cloud deployment and still see the
same session information.

A quick `npm search` revealed that someone had already produced
`connect-riak`, a session storage component for the framework that I'd
selected.  This was very easy to configure and enable and it wasn't long
before I had a very simple web page that incremented a counter on each
visit and showed you the new value.

This is the point where I started to home in on one of the rough edges
of Node and its community.  `connect-riak` has an optional feature to
reap stale session data from your riak store.  I had turned this on and
noticed some errors showing up on the console when it was invoked.

This feature was using a list-keys operation which is a no-no for a
product Riak deployment; it can take a long time to return results if
you have a lot of keys in your Riak instance.  This is not something
you'll see unless you really try to stress test it, so I can forgive the
author for using it (we made the same mistake on a project at work, but
caught it in QA!).

(In the spirit of not blogging bugs, I
[submitted a pull request](https://github.com/frank06/connect-riak/pull/1/files) to implement this feature on top of the secondary indices feature that is present since Riak 1.0)

The rough edge that I mentioned is that there doesn't appear to be much
of a gate for someone to create a package and push it to the NPM package
server.  If you list the available packages, you'll see a fair number of
duplicates where someone has obviously typo'd something in their
`package.json` file and not known that they should do something to pull
the broken version.  Since anyone can publish packages, the quality of
those packages is something of a mixed bag.

This isn't a tremendous downside; there's something nice about the
community being proactive about building things and sharing them, and
having a governing body for the npm packages would certainly slow things
down.  It's just something to be aware of; you'll need to do your
homework before banking on any of the modules you find there.

Everyauth
---------

Back to authentication then; I discovered a package called `everyauth`
that touts an impressive array of authentication options.  Everything
from local passwords in your data-store of choice through OpenID and
OAuth.

<img class='right' src="http://s3.amazonaws.com/kym-assets/photos/images/newsfeed/000/153/020/1311053562212.png" align="right">

Pretty much everything is configured declaratively or via callbacks.
Callbacks are pervasive in Node because they play a key role in handling
completion of asynchronous events.  [One does not
simply](http://knowyourmeme.com/memes/one-does-not-simply-walk-into-mordor) query the
database and wait for the result; instead, the query is submitted to the
database and we carry on with other things until we got notified that
the query completed.

Models
------

Now, I could have torn through this exercise and tried to rapidly throw
an app together but I wanted to be a bit more deliberate.  I've recently
been converting mtrack to use
[Backbone](http://documentcloud.github.com/backbone/) to produce some
nice UI elements.  Backbone is a fairly lightweight framework (in terms
of imposition on your code) that has just enough structure to keep you
on the right path, but not so much that you have to join a cult to
finish an application.

Since Express has some nice parameter validation facilities and
Everyauth wants to access your user information, I definitely needed a
model for the user object, and since Backbone is implemented in
javascript, I looked at re-using the same model code on the server and
client side.

Backbone allows
[Backbone.sync](http://documentcloud.github.com/backbone/#Sync) to be
replaced for a given application, which makes it pretty simple to
globally replace the the routines that handle the loading and storing of
information to the backend.  We ship the models to the client side and
the defaults are set up such that the client can use an Ajax
implementation to talk to the server side.  The server side loads the
same model code but overrides the sync layer to target the Riak data
store.

Models on Server and Client
---------------------------

I mentioned that I'd gone fairly deep into the pieces I'd worked with;
to a certain extent it feels like I've been boiling the ocean.  I wanted
to use exactly the same code on the client and the server for the models
for various efficiencies (for example, validating model attributes) and
reduced maintenance overheads.

The model sources look a bit like this:

{% highlight javascript %}

     var Backbone = require("backbone");
     exports = Backbone.model.extend({ ... });

{% endhighlight %}

`require` is a node function that imports a module; it is not present on
the client side, so we'd either need to write conditional code to handle
the differences or do something to handle those differences in some
other way.

I opted for the latter, because conditional code is visually noisy and I
dislike copy-n-paste boilerplate.  I looked around; there are a number
of possibly solutions for managing this sort of thing, each with their
own pros and cons.  The most promising was
[Stitch](https://github.com/sstephenson/stitch), which would read my
directory structure and send down whole NPM packages from the server
side and assemble them, complete with a browser-side `require` function
that would work on the client side.

One of the drawbacks to this particular module was that it would send
everything that looked like a module in the package directory, which
meant that it sent down all the Backbone examples and not just the bare
minimum bits needed for my app.

In the end, I worked up my own asset solution instead; I give it a list
of javascript files and it will decorate them so that my minimal require
function (shown below) will function:

{% highlight javascript %}

    var require; 
    
    (function () {
        var modules = {};
        
        function do_require(name) {
            return modules[name];
        }   
            
        require = do_require;
        require.define = function(name, inst) {
            modules[name] = inst;
        };
    }());

{% endhighlight %}

The asset compiler stitches the modules together like this:

{% highlight javascript %}

    (function() {
        var exports = {};

        /* --- these are the contents of my model file */
        var Backbone = require("backbone");
        exports = Backbone.model.extend({ ... });
        /* --- end of model file */

        require.define("modelname", exports);
    }());

{% endhighlight %}

This is a very simple composition; it ignores any kind of dependency analysis and relies on you to specify things in the correct order.  You use it with Express like this:

{% highlight javascript %}

    app.get("/j/app.js", asset.createServer([
        __dirname + "/../node_modules/underscore/underscore.js",
        __dirname + "/../node_modules/backbone/backbone.js",
        __dirname + "/models/user.js"
        ], {
            compiler: "require" // use the require.define wrapper
        }));

{% endhighlight %}

Asset Compilation
-----------------

Libraries such as jQuery, underscore and Backbone weigh in at a relatively large code size.  It is desirable, especially on cellular network links, to reduce the size of these assets by compiling them down.

You may be familar with the Google and Yahoo compiler and compressor utilities.  There's a native javascript equivalent known as [UglifyJS](https://github.com/mishoo/UglifyJS).  It's a pretty logical step to fold uglify into the asset compilation process, and that's what I did.

The asset compiler does not simply read the files and stick them together on each request; instead, it maintains a cached copy of the assembled version and intelligently examines the filesystem to see if it needs to recompile the result.

Filesystem operations are blocking operations, which means that your Node performance will go through the floor if you're not using them in an asynchronous fashion.  The Node filesystem API makes this very easy to do; you provide a callback and Node will call it when the results come back from the filesystem.

The asset compiler is made somewhat more convoluted than the equivalent
sequential code would appear in the more commonly used languages and
runtimes, but if I'm honest with myself, it isn't that much worse than
some caching code I've written and used in PHP anyway (it's all to do
with the edge cases).

Adding Uglify to this process is quite simple; once we have all the
data, we pass it to the compilation routine and it returns us the
result.

Unfortunately we hit another snag here; since Uglify is all native
javascript, its processing is all synchronous.  If you have a big chunk
of javascript to compile, it can take a couple of seconds.

{% highlight javascript %}

    function uglify_code(data)
    {
        var ug = require("uglify-js");
        var jsp = ug.parser;
        var pro = ug.uglify;

        var ast = jsp.parse(data);
        ast = pro.ast_mangle(ast);
        /* This next line is the expensive part; it can take a couple of
        * seconds to process jquery */
        ast = pro.ast_squeeze(ast);

        return pro.gen_code(ast);
    }

{% endhighlight %}

What this means is that we block our Node process for those two seconds;
it can't process any other requests in that time, which sucks, but is
mitigated by the usage pattern in this case: we only need to compile it
once per run, so one of the very first few requests will trigger this
hit and then the rest will serve up the results from memory and incur
very little overhead.

It would be nice if we could run the uglify bits on a different thread
so that we don't have this hit...

Cluster and Workers
-------------------

Node doesn't have threads and is unlikely to get them any time soon as most javascript runtimes (V8 included) are explicitly built without any kind of threading support.  Adding thread support to a language runtime like this is no small undertaking; it needs to be carried out extremely carefully to avoid introducing serious performance penalties, and this in turn results in rather complicated implementations.

Instead of threads, Node let's you work very easily with processes.  It
also has a `cluster` facility that allows you to start up a number of
processes and have them all service the same listening socket.  To use
the cluster facilities, you write some code like this:

{% highlight javascript %}

    if (cluster.isMaster) {
        var cpus = os.cpus().length;
        for (var i = 0; i < cpus; i++) {
            cluster.fork();
        }
        cluster.on("death", function (worker) {
            console.log("worker " + worker.pid + " died, respawning");
            cluster.fork();
        });
    } else {
        everyauth.helpExpress(app);
        app.listen(1337); // listens on http://127.0.0.1:1337
    }

{% endhighlight %}

The listen() call does some magic under the covers; it communicates with the
master node process over a pipe and asks it to set up the listening socket.  If
it hasn't already set on up, it starts listening and then uses a low level OS
function to send the underlying socket to the child process.  Multiple child
processes can make the same request of the master process; they each get their
own copy of the file descriptor.  The OS will wake up which ever one is best
prepared to service a given client connection at the time that it comes in.

Using the cluster facility in this way allows your node deployment to
process multiple blocking requests concurrently, and can help to
mitigate the blocking impact of something like the uglify use case
above, but it doesn't completely eliminate the bottleneck.

Say we have 8 CPU cores and thus 8 Node processes, and we've just
restarted our node instance.  We have 10 clients ready to make requests
of the server and all but 1 of them are requesting our uglified
javascript resource.  The 1 that isn't is making a request to some other
resource served out of memory.  If the odd one out is slightly later
than the other clients, he's still blocked while the 8 node processes
are busy compiling the javascript.

As mentioned above, this is mitigated by our particular use-case, but
there may be other cases where we have blocking behavior that we can't
avoid.  It would be nice to have a way to provision our workers so that
we can better control the proportion of blocking work.

One possible approach for this is to use the cluster facility to spawn
off listeners that consume work from a Unix domain socket and use that
to build a dedicated worker pool that is independent from the HTTP
listeners.

It might look something like this:

{% highlight javascript %}

    if (cluster.isMaster) {
        var cpus = os.cpus().length;
        var worker;

        /* start up the HTTP servers */
        process.env.HTTP_WORKER = "1";
        for (var i = 0; i < cpus; i++) {
            worker = cluster.fork();
            worker.isHTTP = true;
        }
        delete process.env.HTTP_WORKER;
        process.env.CPU_WORKER = "1";
        /* start up the workers */
        for (var i = 0; i < cpus; i++) {
            cluster.fork();
        }
        cluster.on("death", function (worker) {
            console.log("worker " + worker.pid + " died, respawning");
            if (worker.isHTTP) {
                process.env.HTTP_WORKER = "1";
                delete process.env.CPU_WORKER;
            } else {
                process.env.CPU_WORKER = "1";
                delete process.env.HTTP_WORKER;
            }
            var newguy = cluster.fork();
            if (worker.isHTTP) {
                newguy.isHTTP = true;
            }
        });
    } else if (process.env.HTTP_WORKER) {
        everyauth.helpExpress(app);
        app.listen(1337); // listens on http://127.0.0.1:1337
    } else if (process.env.CPU_WORKER) {
        /* code omitted because I haven't written it */
        var srv = net.createServer(...);
        srv.listen("/tmp/worker");
    }

{% endhighlight %}

What the above illustrates is that we can create a set of processes
dedicated to the HTTP listeners and another set that listen on a unix
domain socket.  The latter set won't do anything until something
connects and gives it some work.  The idea is that you would encapsulate
the blocking work in a wrapper that connects to the unix socket and
serializes the parameters across the socket.  One of the available
workers would receive this payload and run the blocking function, then
send the result back.  Since the socket IO can be handled
asynchronously, the client side of this can run asynchronously and
continue to serve other requests.

While this is really just moving the blocking problem to another
process, it does so in a controlled fashion; we allowed for a limited
number of processes to become blocked by this work.  While they are
blocked, the HTTP listeners can continue serving non-blocking requests.

Forms, Templates, Validation, Bootstrap
---------------------------------------

I spent a good bit of time thinking about the client side stuff, which
isn't really Node specific, but was different from the usual PHP way.
I'm not going to go into too much detail on that here, but will give
some highlights.

 * I opted to use [Twitter
   Bootstrap](http://twitter.github.com/bootstrap/) to avoid spending
   time thinking about styling in this experiment, which worked, but
   backfired slightly because I ended up folding the [Less CSS
   Compiler](http://lesscss.org/) into my asset compiler step.
 * Everyauth wants a user registration form.  I spent some time looking
   at HTML 5 form validation but eventually settled on using the [jQuery
   tools validator library](http://flowplayer.org/tools/validator/) for
   consistency and ease of integrating with the Twitter Bootstrap
   styling for form errors. ([See my other post on this](http://wezfurlong.org/blog/2011/dec/jquery-tools-form-validator-and-twitter-bootstrap/))
 * Express really wants you to use a templating layer for expanding
   views, and everyauth uses those views too, so I had to learn how to
   use the [Jade](https://github.com/visionmedia/jade) templating
   library.  This can be used on the client side too, which is in
   alignment with the idea that I can use the same code on the server
   and client sides.

Summing up
----------

Node is still rather young and this has resulted in something of a primeval
soup of packages and utilities.  Some are better than others.  It's
interesting to see the collision of front-end and traditional backend
systems code that results in the Node environment, and this is
partially responsible for the mixed bag of node packages that are out
there.

It's entirely possible that I've missed huge swaths of Node best
practices and such, but I don't mind: I've enjoyed poking around the
Node stack at the various different levels.

I don't see us using Node at Message Systems any time soon, at least,
not in the products that we ship; while it has similar facilities to
those in our code technology platform, it has a way to go to catch up.

That doesn't mean that Node is not useful in other applications.  I'm
going to continue looking into Node; there's a module that can implement
SSH service in the node process and this would work well with the mtrack
repo hosting facility.  I think it would be really quite cool to deploy
mtrack as a self contained node service.

