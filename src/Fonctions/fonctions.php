<?php
namespace App\Fonctions;
    function Redirect_Self_URL():void{
        unset($_REQUEST);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

function GenereMDP($nbChar) :string{

    return "secret";
}

    function CalculComplexiteMdp($mdp) :int {
        $L = strlen($mdp); // Longueur du mot de passe

        $B = 0;
        if (preg_match('/[a-z]/', $mdp)) $B += 26; // Lettres minuscules
        if (preg_match('/[A-Z]/', $mdp)) $B += 26; // Lettres majuscules
        if (preg_match('/[0-9]/', $mdp)) $B += 10; // Chiffres
        if (preg_match('/[^a-zA-Z0-9]/', $mdp)) $B += 10; // Caractères spéciaux

        $E = $L * log($B, 2); // Entropie en bits
        return ($E) ;
    }