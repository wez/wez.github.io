# vim:ts=2:sw=2:et:
require 'nokogiri'

# Finds site-relative links and forces a URL into them.
# usage: {{ post.content | fixlinksfilter: "http://wezfurlong.org" }}

module FixLinks
  def fixlinksfilter(input, url)
    doc = Nokogiri::HTML::fragment(input)
    doc.search('img').each do |node|
      if node['src'] and node['src'].match(/^\//)
        node['src'] = url + node['src']
      end
    end
    doc.search('a').each do |node|
      if node['href'] and node['href'].match(/^\//)
        node['href'] = url + node['href']
      end
    end
    return doc.to_s
  end
end
Liquid::Template.register_filter(FixLinks)
