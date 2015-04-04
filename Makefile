#
# Marcio Ribeiro <binary (a) b1n.org>
#

# Needed external commands
DOCKER ?= /usr/bin/docker

# Constants
IMAGE = mribeiro/deckprice

build-image:
	$(DOCKER) build -t $(IMAGE) image
