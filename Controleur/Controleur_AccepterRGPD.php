<?php

use App\Modele\Modele_Utilisateur;
use App\Vue\Vue_Connexion_Formulaire_client;
use App\Vue\Vue_ConsentementRGPD;
use App\Vue\Vue_Menu_Administration;
use App\Vue\Vue_Menu_Entreprise_Client;
use App\Vue\Vue_Structure_Entete;

switch ($action) {
    case "accepte_RGPD":
        Modele_Utilisateur::Utilisateur_Modifier_RGPD(1, date("Y-m-d"), $_SERVER['REMOTE_ADDR'], $_SESSION["idUtilisateur"]);
        //Appel à une nouvelle fonction du modèle  $_SESSION["idUtilisateur"]
        switch ($_SESSION["typeConnexionBack"]) {
            case "administrateurLogiciel":
                $Vue->setMenu(new Vue_Menu_Administration());
                break;
            case "utilisateurCafe":

                $Vue->setMenu(new Vue_Menu_Administration());
                break;
            case "entrepriseCliente":
                $Vue->SetMenu(new \App\Vue\Vue_Menu_Entreprise_Client());
                break;
            case "salarieEntrepriseCliente":
                $Vue->setMenu(new \App\Vue\Vue_Menu_Entreprise_Salarie());
                break;
        }
        break;
    case "refuser_RGPD":
        session_destroy();
        unset($_SESSION);
        $Vue->setEntete(new Vue_Structure_Entete());
        $Vue->addToCorps(new Vue_Connexion_Formulaire_client());
        break;
    default:
        $Vue->addToCorps(new Vue_ConsentementRGPD());
        break;


}