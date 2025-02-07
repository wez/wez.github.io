#!/bin/sh
mkdir -p gh_pages
docker run --pull=always --rm -e CARDS=true -v ${PWD}:/docs squidfunk/mkdocs-material build
