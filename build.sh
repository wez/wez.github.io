#!/bin/sh
docker run --rm -it --network=host -v ${PWD}:/docs squidfunk/mkdocs-material serve -a 0.0.0.0:8000

