<?php
require_once "testGenerationMDP.php";

$dictionnaire =[];
for($i = 0; $i < 1000000; $i++){
    $dictionnaire[]=passgen1(10, $i);
}
var_dump($dictionnaire);
