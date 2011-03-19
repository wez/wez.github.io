# http://www.jacquesf.com/2011/03/06/jekyll-wordpress-style-more-fold.html
# Splits a post at the boundary denoted by an html comment <!--more--> and
# adds a link back to the main content.
# usage: {{ post.content | postmorefilter: post.url, "read more" }}
module PostMore
  def postmorefilter(input, url, text)
    if input.include? "<!--more-->"
      input.split("<!--more-->").first +
        "<p class='more'><a href='#{url}'>#{text}</a></p>"
    else
      input
    end
  end
end

Liquid::Template.register_filter(PostMore)

