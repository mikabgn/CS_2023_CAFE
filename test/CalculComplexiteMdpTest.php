<?php

require_once '../src/Fonctions/fonctions.php';

$mdp = 'aubry';
$comlplexiter = App\Fonctions\CalculComplexiteMdp($mdp);
echo $comlplexiter;
echo PHP_EOL;

$mdp = 'super@ubry';
$comlplexiter = App\Fonctions\CalculComplexiteMdp($mdp);
echo $comlplexiter;
echo PHP_EOL;

$mdp = 'Super@ubry2022';
$comlplexiter = App\Fonctions\CalculComplexiteMdp($mdp);
echo $comlplexiter;
echo PHP_EOL;

$mdp = 'Giroud-Président||2027';
$comlplexiter = App\Fonctions\CalculComplexiteMdp($mdp);
echo $comlplexiter;
echo PHP_EOL;