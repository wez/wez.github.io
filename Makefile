.PHONY: build push

build:
	jekyll

push:
	rsync -avz -e ssh --delete-after --exclude=".hg" --exclude=".*.swp*" _site/ joy2.wezfurlong.org:/data/sites/wezfurlong.org

