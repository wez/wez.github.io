$(document).ready(function(){

if (window.disqus_title) {
	// on pages that have comments, only load disqus when it scrolls into
	// view; this approach is borrowed from the jQuery docs page; it makes
	// the site feel faster
	var comments_top = $('#disqus_thread').offset().top;
	var comments_loaded = false;
	function check_comment_scroll() {
		if (comments_loaded) return;
		if ($(window).scrollTop() + $(window).height() > comments_top) {
			comments_loaded = true;
			$.getScript('http://evilasindr.disqus.com/embed.js');
		}
	}
	$(window).scroll(check_comment_scroll);
	check_comment_scroll();
}

function add_repo(o) {
	if (repos_by_name[o.name]) {
		// have a dupe (probably bb and github)
		repos_by_name[o.name].followers += o.followers;
		return;
	}
	repos_by_name[o.name] = o;

	var rlist = $('#repolist');
	var a = $("<a/>");
	a.attr("href", o.source);
	a.text(o.name);

	var d = $("<div/>");
	d.text(o.desc);

	var li = $("<li/>");
	li.append(a);
	li.append(d);
	rlist.append(li);
	li.data('o', o);

	$('li', rlist).sortElements(function (a, b) {
		a = $(a).data('o');
		b = $(b).data('o');
		return a.name.toLowerCase() > b.name.toLowerCase() ? 1 : -1;
	});
}

if (window.repos_by_name) {

	add_repo({
		name: "jlog",
		desc: 'JLog is short for "journaled log" and this package is really an API and implementation that is libjlog. What is libjlog? libjlog is a pure C, very simple durable message queue with multiple subscribers and publishers (both thread and multi-process safe). The basic concept is that publishers can open a log and write messages to it while subscribers open the log and consume messages from it. "That sounds easy." libjlog abstracts away the need to perform log rotation or maintenance by publishing into fixed size log buffers and eliminating old log buffers when there are no more consumers pending.',
		source: "https://labs.omniti.com/jlog/trunk",
		web: "https://labs.omniti.com/trac/jlog"
	});

	add_repo({
		name: "PHP",
		desc: "PHP is a widely-used general-purpose scripting language that is especially suited for Web development and can be embedded into HTML.",
		source: "https://svn.php.net",
		web: "http://php.net"
	});

  add_repo({
    name: "Watchman",
    desc: "Watches files and takes action when they change",
    source: "https://github.com/facebook/watchman",
    web: "https://github.com/facebook/watchman"
  });

  if (0) {
    // CORS prevents this from loading
	$.ajax({
		url: "http://sourceforge.net/api/user/username/wez/json",
		success: function (data, status, xhr) {
			var i;
			var p = data.User.projects;
			for (i = 0; i < p.length; i++) {
				if (p[i].name == 'libumem') continue;
				$.ajax({
					url: "http://sourceforge.net/api/project/id/" +
							p[i].id + "/json",
					success: function (data, status, xhr) {
						var o = {};
						var p = data.Project;
						o.name = p.name;
						o.desc = p.description;
						o.source = p["summary-page"];
						o.followers = 0;
						o.web = p.homepage;
						if (o.web.substr(0, 4) != "http") {
							o.web = "http://" + o.web;
						}
						add_repo(o);
					}
				});
			}
		}
	});
  } // end CORS

	$.ajax({
		url: "https://api.github.com/users/wez/repos",
		dataType: 'jsonp',
		success: function (data, status, xhr) {
			var i;
			for (i = 0; i < data.data.length; i++) {
				var r = data.data[i];
				if (r.is_private) continue;
				var o = {};
				o.name = r.name;
				o.desc = r.description;
				o.source = r.url;
				o.followers = r.watchers;
				o.web = r.homepage;

				add_repo(o);
			}
		}
	});
	$.ajax({
		url: "https://api.github.com/users/wez/orgs",
		dataType: 'jsonp',
		success: function (data, status, xhr) {
			var i;
			for (i = 0; i < data.data.length; i++) {
				var org = data.data[i];
				$.ajax({
					url: "https://api.github.com/orgs/" + org.login + "/repos",
					dataType: 'jsonp',
					success: function (data, status, xhr) {
						var j;
						for (j = 0; j < data.data.length; j++) {
							var r = data.data[j];

							if (r.is_private) continue;
							var o = {};
							o.name = r.name;
							o.desc = r.description;
							o.source = r.url;
							o.followers = r.watchers;
							o.web = r.homepage;

							// Am I a collaborator?
							$.ajax({
								url:
									"https://api.github.com/repos/"
									+ org.login + "/" + r.name +
									"/collaborators",
								dataType: 'jsonp',
								context: o,
								success: function (data, status, xhr) {
									var k;
									for (k = 0; k < data.data.length;
											k++) {
										if (data.data[k] == 'wez') {
											add_repo(this);
											break;
										}
									}
								}
							});
						}
					}
				});
			}
		}
	});
	$.ajax({
		url: "https://api.bitbucket.org/1.0/users/wez/",
		dataType: 'jsonp',
		success: function (data, status, xhr) {
			var i;
			for (i = 0; i < data.repositories.length; i++) {
				var r = data.repositories[i];
				if (r.is_private) continue;
				var o = {};
				o.name = r.name;
				o.desc = r.description;
				o.source = "https://bitbucket.org/wez/" + r.slug;
				o.followers = r.followers_count;
				o.web = r.website;

				add_repo(o);

			}
		}
	});

}


});

