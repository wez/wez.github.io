.PHONY: build push deps

build:
	/usr/local/Cellar/ruby/1.9.3-p327/bin/jekyll

push:
	rsync -avz -e ssh --delete-after --exclude=".hg" --exclude=".*.swp*" _site/ joy2.wezfurlong.org:/data/sites/wezfurlong.org

deps:
	brew install ruby
	( cd ~/src/jekyll ; gem build jekyll.spec ; gem install ./jekyll-0.11.2.gem )
	gem install nokogiri

