---
# vim:ts=4:sw=4:et:
date: 2011-12-20
layout: post
title: jQuery Tools Form Validator and Twitter Bootstrap
categories:
 - javascript
 - jquery
---

I've had a couple of folks ask me about the form validation I
[mentioned in my last
post](http://wezfurlong.org/blog/2011/dec/nodejs-first-impressions/), so
here's the code.

It's fairly basic IMO.  This is public domain code; use as you will and
at your own risk.  You'll be able to make a form that looks like this:

![Example Form](/images/bootstrap-form.png)

<!--more-->

Dependencies
------------

 * [jQuery Tools Validator](http://flowplayer.org/tools/validator/)
 * [Twitter Bootstrap 1.4 compatible
   form markup](http://twitter.github.com/bootstrap/index.html#forms)


Head
----

{% highlight html %}
    <!-- your preferred jquery -->
    <script src="jquery.js"></script>
    <!-- the jquery validator -->
    <script src="validator.js"></script>
    <!-- the twitter bootstrap -->
    <link rel="stylesheet" href="bootstrap.css">
    <!-- the javascript below -->
    <script src="forms.js"></script>
{% endhighlight %}


forms.js
--------

{% highlight javascript %}

    $(function () {
        function find_container(input) {
            return input.parent().parent();
        }
        function remove_validation_markup(input) {
            var cont = find_container(input);
            cont.removeClass('error success warning');
            $('.help-inline.error, .help-inline.success, .help-inline.warning',
                cont).remove();
        }
        function add_validation_markup(input, cls, caption) {
            var cont = find_container(input);
            cont.addClass(cls);
            input.addClass(cls);
                
            if (caption) {
                var msg = $('<span class="help-inline"/>');
                msg.addClass(cls);
                msg.text(caption);
                input.after(msg);
            }       
        }
        function remove_all_validation_markup(form) {
            $('.help-inline.error, .help-inline.success, .help-inline.warning',
                form).remove(); 
            $('.error, .success, .warning', form)
                .removeClass('error success warning');
        }               
        $('form').each(function () {
            var form = $(this);
                    
            form
                .validator({
                })
                .bind('reset.validator', function () {
                    remove_all_validation_markup(form);
                })
                .bind('onSuccess', function (e, ok) {
                    $.each(ok, function() {
                        var input = $(this);
                        remove_validation_markup(input);
                        // uncomment next line to highlight successfully
                        // validated fields in green
                        //add_validation_markup(input, 'success');
                    }); 
                })
                .bind('onFail', function (e, errors) {
                    $.each(errors, function() {
                        var err = this;
                        var input = $(err.input);
                        remove_validation_markup(input);
                        add_validation_markup(input, 'error',
                            err.messages.join(' '));
                    });
                    return false;
                });
        });
    });

{% endhighlight %}

User Registration Form
----------------------

This example uses the [Jade](https://github.com/visionmedia/jade)
templating engine, but you can certainly port the markup to anything you
like.

{% highlight jade %}
    .page-header
        h1 Sign Up!

    form(method='POST', id='register')
        fieldset
            legend Enter your details to create an account
            .clearfix
                label(for='login') Username
                .input
                    input.xlarge(id='login', name='login',
                        required, pattern="^[a-z][a-z0-9_]*$",
                        placeholder='Choose a username', size='30')
                    span.help-block Unix-style; no spaces or punctuation, only lowercase letters and numbers
            .clearfix
                label(for='email') Email
                .input
                    input.xlarge(id='email', name='email', type='email',
                        required,
                        placeholder='Enter your email address', size='30')
                    span.help-block We'll send you an email to confirm this!
            .clearfix
                label(for='password') Password
                .input
                    input.xlarge(id='password', name='password',
                        required,
                        placeholder='Choose a password', type='password', size='30')
            .clearfix
                label(for='password2') Confirm
                .input
                    input.xlarge(id='password2', name='password2',
                        required,
                        placeholder='Enter that password again',
                        type='password', size='30')
            .actions
                input.btn.primary(type='submit', value='Create Account')
                | 
                button.btn(type='reset') Cancel

    script.
        $(function () {
            $('#register').submit(function () {
                var form = $(this);
                if ($('#password').val() != $('#password2').val()) {
                    form.data('validator').invalidate({
                        password2: "Passwords don't match"
                    });
                    return false;
                }
            });
        });
{% endhighlight %}



How it Works
------------

The `forms.js` code overrides the default markup behavior of the validator library and implements its own logic for setting the form styling to match the twitter bootstrap guidelines.

The page template has its own little bit of event handling; when the form is submitted, it checks to see if the password fields match; if they don't, it tells the validator library to highlight the second field with a note to that effect.

That's all for now; enjoy!

