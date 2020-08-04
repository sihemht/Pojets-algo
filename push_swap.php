#!/bin/bash
<?php

$la = [];
$lb = [];
for ($i = 1; $i < count($argv); $i++) {
    array_push($la, intval($argv[$i]));
}

//échange les positions des deux premiers éléments de la
function sa()
{
    global $la;
    $tempo = $la[0];
    $la[0] = $la[1];
    $la[1] = $tempo;
}

//échange les positions des deux premiers éléments de lb (
function sb()
{
    global $lb;
    $tempo = $lb[0];
    $lb[0] = $lb[1];
    $lb[1] = $tempo;
}

//sa et sb en même temps.
function sc()
{
    global $la, $lb;
    sa($la);
    sb($lb);
}

// prend le premier élément de lb et le place à la première position de la
function pa()
{
    global $la, $lb;
    $firstLb = array_shift($lb);
    array_unshift($la, $firstLb);
}

//prend le premier élément de la et le place à la première position de lb
function pb()
{
    global $la, $lb;
    $firsLa = array_shift($la);
    array_unshift($lb, $firsLa);
}

//fait une rotation de la vers le début
function ra()
{
    global $la;
    for ($i = 0; $i <= count($la); $i++) {
        $elm = array_shift($la);
        array_push($la, $elm);
    }
}

// fait une rotation de lb vers le début 
function rb()
{
    global $lb;
    for ($i = 0; $i <= count($lb); $i++) {
        $elm = array_shift($lb);
        array_push($lb, $elm);
    }
}

//ra et rb en même temps
function rr()
{
    global $la, $lb;
    ra($la);
    rb($lb);
}

//fait une rotation de la vers la fin
function rra()
{
    global $la;
    $lastElement = array_pop($la);
    array_unshift($la, $lastElement);
}

//fait une rotation de lb vers la fin
function rrb()
{
    global $lb;
    $lastElement = array_pop($lb);
    array_unshift($lb, $lastElement);
}
// rra et rrb en même temps
function rrr()
{
    global $la, $lb;
    rra($la);
    rrb($lb);
}

function printLa()
{
    global $la;
    echo PHP_EOL;
    foreach ($la as $value) {

        echo "\033[31m | " . $value . "\033[0m";
    }
    echo PHP_EOL;
    echo PHP_EOL;
}

function printLb()
{
    global $lb;
    foreach ($lb as $value) {
        echo " | " . $value;
    }
    echo PHP_EOL;
}
function trie()
{
    global $la, $lb;
    echo "Liste à trier:";
    printLa();

    while (!empty($la)) {
        pb();
        echo  "\033[33m pb \033[0m";
    }

    while (!empty($lb)) {
        $tmp = $lb[0];
        $index = 0;
        for ($i = 0; $i < count($lb); $i++) {
            if ($lb[$i] > $tmp) {
                $tmp = $lb[$i];
                $index = $i;
            }
        }
        if ($index < count($lb) / 2) {
            while ($lb[0] != $tmp) {
                rb();
                echo "\033[33m rb \033[0m";
            }
        } else {
            while ($lb[0] != $tmp) {
                rrb();
                echo "\033[33m rrb \033[0m";
            }
        }

        pa();
        echo "\033[33m pa \033[0m";
    }
    echo PHP_EOL;
    echo PHP_EOL;
    echo "Liste trié :";
    printLa();
}

trie();


/* var_dump($la);*/
