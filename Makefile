.PHONY: build push

build:
	jekyll

push:
	rsync -avz -e ssh --delete-after --exclude=".hg" --exclude=".*.swp*" _site/ joy.wezfurlong.org:wezfurlong.org

