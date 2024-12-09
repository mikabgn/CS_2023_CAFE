<?php
require_once './vendor/autoload.php';

$modele = new \App\Modele\Modele_tokens();

$modele->Tokens_Creer(765,1,'2500-12-01 00:00:00');
print_r($modele->Tokens_Select_By_id(1));
$modele->Tokens_Update_date('1', '2152-12-01 00:00:00');
print_r( $modele->Tokens_Select_By_id('1'));
