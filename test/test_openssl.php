<?php
//Création de la séquence aléatoire à la base du mot de passe
//$octetsAleatoires = openssl_random_pseudo_bytes (12) ;
//Transformation de la séquence binaire en caractères alpha
//$motDePasse = sodium_bin2base64($octetsAleatoires, SODIUM_BASE64_VARIANT_ORIGINAL);
//echo $motDePasse;

function passgen1($nbChar)
{
    $chaine = "ABCDEFGHIJKLMONOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789&é\"'(-è_çà)=$^*ù!:;,~#{[|`\^@]}¤€";
    srand((double)microtime() * 1000000);
    $pass = '';
    for ($i = 0; $i < $nbChar; $i++) {
        $pass .= $chaine[random_int(0,strlen($chaine)-1)];
    }
    return $pass;
}

echo passgen1(10);