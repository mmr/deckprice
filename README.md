MTG Deck Price Calculator
=====

Magic The Gathering card prices vary quite a lot.
There are cards that are worth well over **US$3,000.00**, while most are priced below a handful cents.

Deck Price Calculator uses [Black Lotus Project](http://blacklotusproject.com/) API to get
the prices for a deck in a simple format.


Usage:
-----

DeckPrice was written in PHP.

If you have php-cli installed:

```bash
# To calculate the price of a deck in cod format (cockatrice format)
php price.php file.cod

# To caulcate the price of a deck in simple txt format
php price.php file.txt
```

If you do not want to install _php-cli_ and yadda-yadda, you can use
the container we have prepared for you.

You can get the container image from docker hub or build it locally.

```bash
# To get the image from docker hub
docker pull mribeiro/deckprice

# To build it locally (which is way cooler)
make build-image
```

Now that you have the image, you can use the shell script we have
provided that runs the container:

```bash
# To use the docker container to check a deck price
./calc.sh file.cod

# Txt format
./calc.sh file.txt
```

Or if have masochistic tendencies, you can run the container by hand:

```bash
# Using the container to check the price of the Treefolk deck
docker run -i --rm -v $PWD:/data mribeiro/deckprice examples/treefolk.cod
2  x Naturalize         = 2  x 0.12 =  0.24
2  x Hurricane          = 2  x 0.12 =  0.24
2  x Cloudcrown Oak     = 2  x 0.25 =  0.50
2  x Wickerbough Elder  = 2  x 0.25 =  0.50
4  x Giant Growth       = 4  x 0.15 =  0.60
2  x Lignify            = 2  x 0.34 =  0.68
4  x Battlewand Oak     = 4  x 0.25 =  1.00
4  x Wild Growth        = 4  x 0.25 =  1.00
4  x Bosk Banneret      = 4  x 0.25 =  1.00
2  x Vines Of Vastwood  = 2  x 0.77 =  1.54
20 x Forest             = 20 x 0.09 =  1.80
2  x Leaf-crowned Elder = 2  x 4.43 =  8.86
4  x Dauntless Dourbark = 4  x 2.66 = 10.64
2  x Timber Protector   = 2  x 6.22 = 12.44
4  x Treefolk Harbinger = 4  x 4.17 = 16.68
TOTAL: 60 cards : 57.72

# Using the container to check the price of the Myr deck
docker run -i --rm -v $PWD:/data mribeiro/deckprice examples/myr.txt
2 x Myr Battlesphere  = 2 x 0.47 = 0.94
4 x Silver Myr        = 4 x 0.25 = 1.00
2 x Voltaic Key       = 2 x 0.55 = 1.10
2 x Ancient Den       = 2 x 0.67 = 1.34
4 x Myr Enforcer      = 4 x 0.37 = 1.48
2 x Myr Turbine       = 2 x 0.83 = 1.66
4 x Tree Of Tales     = 4 x 0.56 = 2.24
4 x Lodestone Myr     = 4 x 0.60 = 2.40
2 x Mirrorworks       = 2 x 1.23 = 2.46
4 x Vault Of Whispers = 4 x 0.62 = 2.48
4 x Thoughtcast       = 4 x 0.68 = 2.72
4 x Great Furnace     = 4 x 0.74 = 2.96
4 x Myr Galvanizer    = 4 x 0.75 = 3.00
4 x Seat Of The Synod = 4 x 0.75 = 3.00
4 x Myr Reservoir     = 4 x 0.80 = 3.20
4 x Palladium Myr     = 4 x 1.01 = 4.04
4 x Darksteel Citadel = 4 x 1.24 = 4.96
2 x Banefire          = 2 x 2.75 = 5.50
TOTAL: 60 cards : 46.48
```
