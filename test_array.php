<?php

class Teste {

    private $label = null;

    public function __construct($label) {
        $this->label = $label;
    }

    public function __toString() {
        return "Label: $this->label";
    }

}

$arr = array();

for ($i = 0; $i < 100000; $i++)
    $arr[] = new Teste("teste $i");

unset($arr[2]);

$arr = array_values($arr);

echo array_search(new Teste(''), $arr) . "\n";
echo array_search($arr[99899], $arr) . "\n";
echo $arr[array_search($arr[99899], $arr)] . "\n";
?>