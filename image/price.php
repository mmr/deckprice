<?php
/**
 * Calculates deck price using Black Lotus Project API.
 * Understands Cockatrice Deck Editor and simple txt file formats.
 *
 * @author Marcio Ribeiro (mmr)
 * @created Feb 28, 2011
 */
define('URL', 'http://blacklotusproject.com/json/?cards=');

/**
 * Deck.
 */
class Deck {
    private $cards = array();

    /**
     * Add card to deck.
     * @param $name card name.
     * @param $amount card amount.
     */
    public function addCard($name, $amount) {
        $n = strtolower($name);
        if (isset($cards[$n])) {
            die("Card $n already mentioned in deck. Aborting.\n");
        }
        $this->cards[$n] = new Card($n, $amount);
    }

    /**
     * Update card prices using Black Lotus Project API.
     */
    public function updatePrices() {
        $cs = '';
        foreach ($this->cards as $n => $c) {
            $cs .= urlencode($n) . '|';
        }

        $u = URL . $cs;

        $j = file_get_contents($u);
        $d = json_decode($j, true);

        foreach ($d['cards'] as $c) {
            $n = strtolower($c['name']);
            $this->cards[$n]->updatePrice($c['average']);
        }
    }

    /**
     * @return printable format of the deck card prices.
     */
    public function __toString() {
        $ord = array();

        // calculate paddings and sort by price (low -> high)
        $tpad = 0;
        $ppad = 0;
        $apad = 0;
        $npad = 0;
        foreach ($this->cards as $n => $c) {
            $p = $c->getPrice();
            $a = $c->getAmount();
            $t = $p * $a;

            $npad = max($npad, strlen($n));
            $ppad = max($ppad, strlen((int)$p));
            $tpad = max($tpad, strlen((int)$t));
            $apad = max($apad, strlen($a));

            $ord[$n] = $t;
        }
        asort($ord);

        $s = "";
        $tc = 0;
        $total = 0;
        foreach ($ord as $n => $v) {
            $c = $this->cards[$n]; 

            $a = $c->getAmount();
            $p = $c->getPrice();
            $t = $a * $p;

            $sn = str_pad(ucwords($n), $npad);
            $sa = str_pad($a, $apad);
            $sp = money_format("%#" . $ppad . ".2n", $p);
            $st = money_format("%#" . $tpad . ".2n", $t);

            $s .= "$sa x $sn = $sa x$sp =$st" . PHP_EOL;
            $total += $t;
            $tc += $a;
        }
        $s .= "TOTAL: $tc cards : $total" . PHP_EOL;
        return $s;
    }
}

/**
 * Card.
 */
class Card {
    private $name; 
    private $amount;
    private $price;

    /**
     * Constructor.
     * @param $name card name.
     * @param $amount number of copies of that card.
     */
    function __construct($name, $amount) {
        $this->name = $name;
        $this->amount = $amount;
    }

    /**
     * Update price variable.
     * Always keeps the lowest price.
     * @param $price price to be tested against the current price.
     */
    public function updatePrice($price) {
        if (is_null($this->price) || $this->price > $price) {
            $this->price = $price;
        }
    }

    /**
     * @return the amount.
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @return the price.
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @return the name.
     */
    public function getName() {
        return $this->name;
    }
}

/**
 * Deck reader interface.
 */
interface DeckReader {
    /**
     * Read the given file and creates a Deck object.
     * @param file name of the file with the deck description.
     * @return deck object created from the given file.
     */
    public function readDeck($f);
}

/**
 * Deck reader that understands the simple txt format.
 * 
 * The format expected is:
 * <amount> <card name>
 *
 * Example:
 * 2 Mountain Goat
 * 4 Giant Growth
 * 10 Mountain
 * 8 Forest
 */
class TxtDeckReader implements DeckReader {
    /**
     * Read deck from txt file.
     * @param $f name of the file with the deck description.
     * @return deck object.
     */
    public function readDeck($f) {
        $d = new Deck();
        $lines = file($f);
        foreach ($lines as $line) {
            if (preg_match("/^(\d+)\s*(.*)$/", $line, $matches)) {
                $d->addCard(trim($matches[2]), $matches[1]);
            } else {
                die("Incorret format at line: $line\n");
            }
        }
        return $d;
    }
}

/**
 * Deck reader that understands the Cockatrice deck format.
 * @param $f file with deck description in cockatrice format.
 * @return deck object.
 */
class CockatriceDeckReader implements DeckReader {
    public function readDeck($f) {
        $d = new Deck();
        $xml = simplexml_load_file($f);
        foreach ($xml->zone->card as $c) {
            $d->addCard($c['name'], $c['number']);
        }
        return $d;
    }
}

if (!isset($argv[1])) {
    die("Usage: " . $argv[0] . " file [txt]\n");
}

$f = $argv[1];
if (!is_readable($f)) {
    die("File $f not found\n");
}

$r = null;
if (isset($argv[2])) {
    $r = new TxtDeckReader();    
} else {
    $r = new CockatriceDeckReader();
}

$d = $r->readDeck($f);
$d->updatePrices();
print $d;
?>
