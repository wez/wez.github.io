#!/bin/sh
# Runs a local server for iterating on changes
docker run --rm -it --network=host -v ${PWD}:/docs squidfunk/mkdocs-material serve -a 0.0.0.0:8000

