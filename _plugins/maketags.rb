# vim:ts=2:sw=2:et:

module Jekyll

  class CategoryPage < Page
    def initialize(site, cat_dir, catname, posts)
      @site = site
      @base = site.source
      @dir = cat_dir
      @name = 'index.html'
      self.process(@name)
      self.read_yaml(File.join(@base, '_layouts'), 'category.html')
      self.data['category'] = catname
      self.data['posts'] = posts
      self.data['title'] = "Posts tagged #{catname}"
    end
  end

  class MakeTags < Generator
    safe true

    def generate(site)
      # Collect posts by category
      cats = Hash.new
      site.posts.each do |post|
        post.categories.each do |cat|
          key = cat.downcase
          if not cats.has_key?(key) then
            cats[key] = Array.new
          end
          cats[key].push(post)
        end
      end
      # Where the tags will live
      tagbase = "#{site.dest}/blog/tag"
      # Now generate
      cats.each do |key, posts|
        tagdir = "#{tagbase}/#{key}"

        if not Dir.exists?(tagdir) then
          FileUtils.mkdir_p(tagdir)
        end

        # Add to the site list of pages, it will generate them later
        site.pages << CategoryPage.new(site, "blog/tag/#{key}", key, posts)
      end
    end
  end

end
