<?php

use App\Modele\Modele_Entreprise;
use App\Modele\Modele_Salarie;
use App\Modele\Modele_Utilisateur;
use App\Vue\Vue_Connexion_Formulaire_client;
use App\Vue\Vue_Mail_Confirme;
use App\Vue\Vue_Mail_ReinitMdp;
use App\Modele\Modele_tokens;
use App\Vue\Vue_Menu_Administration;
use App\Vue\Vue_Structure_BasDePage;
use App\Vue\Vue_Structure_Entete;
use PHPMailer\PHPMailer\PHPMailer;
use function App\Fonctions\envoieMailTokens;
use function App\Fonctions\envoieMail;

//Ce contrôleur gère le formulaire de connexion pour les visiteurs
$Vue->setEntete(new Vue_Structure_Entete());

switch ($action) {
    case "choixmdp":
        if ($_POST["mdp1"] == $_POST["mdp2"]) {
            var_dump($_SESSION);
            Modele_Utilisateur::Utilisateur_Modifier_motDePasse((Modele_Utilisateur::Utilisateur_Select_ParLogin($_SESSION["email"])["idUtilisateur"]),$_POST["mdp1"]);
        } else {
            $Vue->addToCorps(new \App\Vue\Vue_Mail_ChoisirNouveauMdp($_SESSION["token"]));
        }
        break;
    case "token":
        $_SESSION["token"] = $_GET["token"];
        $Vue->addToCorps(new \App\Vue\Vue_Mail_ChoisirNouveauMdp($_SESSION["token"]));
        break;
    case "reinitmdpconfirm":
          //comme un qqc qui manque... je dis ça ! je dis rien !
        if (isset($_POST["email"])){
            $_SESSION["email"] = $_POST["email"];

            $nouveauMDP = \App\Fonctions\tokenMotDePasse(30);
            envoieMail($nouveauMDP);
            Modele_Utilisateur::Utilisateur_Modifier_motDePasse(Modele_Utilisateur::Utilisateur_Select_ParLogin($_SESSION["email"])["idUtilisateur"],$nouveauMDP);
        }
        $_SESSION["reinitmdp"] = true;
        $Vue->addToCorps(new Vue_Mail_Confirme());

        break;
    case "reinitmdpconfirmTokens":
        $_SESSION["email"] = $_POST["email"];

        $valeurToken = \App\Fonctions\tokenMotDePasse(30);
        $id_utilisateur = \App\Modele\Modele_Utilisateur::Utilisateur_Select_ParLogin($_POST["email"] )["idUtilisateur"];
        if (!empty($id_utilisateur)){
            $date = new \DateTime();
            $date=$date->modify('+1 hour')->format('Y-m-d H:i:s');
            (new App\Modele\Modele_tokens)->Tokens_Creer("519", $id_utilisateur,$date);
            envoieMailTokens($valeurToken);
        } else {
            header("Location:index.php");
            $_SESSION["msgErreurMail"]="<div class='text-center '>Mail non trouvé</div>";
        }
        break;
    case "reinitmdp":
        $Vue->addToCorps(new Vue_Mail_ReinitMdp());
        break;
    case "Se connecter" :
        if (isset($_REQUEST["compte"]) and isset($_REQUEST["password"])) {
            //Si tous les paramètres du formulaire sont bons

            $utilisateur = Modele_Utilisateur::Utilisateur_Select_ParLogin($_REQUEST["compte"]);
            if ($utilisateur != null) {
                //error_log("utilisateur : " . $utilisateur["idUtilisateur"]);
                if ($utilisateur["desactiver"] == 0) {
                    if ($_REQUEST["password"] == $utilisateur["motDePasse"]) {
                        if ( isset($_SESSION["reinitmdp"]) && $_SESSION["reinitmdp"]) {
                            header("Location:index.php?case=Gerer_monCompte&action=changerMDP");
                        }
                        $_SESSION["idUtilisateur"] = $utilisateur["idUtilisateur"];
                        //error_log("idUtilisateur : " . $_SESSION["idUtilisateur"]);
                        $_SESSION["idCategorie_utilisateur"] = $utilisateur["idCategorie_utilisateur"];
                        //error_log("idCategorie_utilisateur : " . $_SESSION["idCategorie_utilisateur"]);
                        switch ($utilisateur["idCategorie_utilisateur"]) {
                            case 1:
                                $_SESSION["typeConnexionBack"] = "administrateurLogiciel"; //Champ inutile, mais bien pour voir ce qu'il se passe avec des étudiants !
                                if ($utilisateur["aAccepterRGPD"]==0){
                                    include "./Controleur/Controleur_AccepterRGPD.php";
                                }
                                else
                                         $Vue->setMenu(new Vue_Menu_Administration($_SESSION["typeConnexionBack"]));
                                break;
                            case 2:
                                $_SESSION["typeConnexionBack"] = "gestionnaireCatalogue";
                                if ($utilisateur["aAccepterRGPD"]==0){
                                    include "./Controleur/Controleur_AccepterRGPD.php";
                                }
                                $Vue->setMenu(new Vue_Menu_Administration($_SESSION["typeConnexionBack"]));
                                $Vue->addToCorps(new \App\Vue\Vue_AfficherMessage("Bienvenue " . $_REQUEST["compte"]));
                                break;
                            case 3:
                                $_SESSION["typeConnexionBack"] = "entrepriseCliente";
                                if ($utilisateur["aAccepterRGPD"]==0){
                                    include "./Controleur/Controleur_AccepterRGPD.php";
                                }
                                //error_log("idUtilisateur : " . $_SESSION["idUtilisateur"]);
                                $_SESSION["idEntreprise"] = Modele_Entreprise::Entreprise_Select_Par_IdUtilisateur($_SESSION["idUtilisateur"])["idEntreprise"];
                                include "./Controleur/Controleur_Gerer_Entreprise.php";
                                break;
                            case 4:
                                $_SESSION["typeConnexionBack"] = "salarieEntrepriseCliente";
                                if ($utilisateur["aAccepterRGPD"]==0){
                                    include "./Controleur/Controleur_AccepterRGPD.php";
                                }
                                $_SESSION["idSalarie"] = $utilisateur["idUtilisateur"];
                                $_SESSION["idEntreprise"] = Modele_Salarie::Salarie_Select_byId($_SESSION["idUtilisateur"])["idEntreprise"];
                                include "./Controleur/Controleur_Catalogue_client.php";
                                break;
                            case 5:
                                $_SESSION["typeConnexionBack"] = "commercialCafe";
                                $Vue->setMenu(new Vue_Menu_Administration($_SESSION["typeConnexionBack"]));
                                break;
                        }

                    } else {//mot de passe pas bon
                        $msgError = "Mot de passe erroné";

                        $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));

                    }
                } else {
                    $msgError = "Compte désactivé";

                    $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));

                }
            } else {
                $msgError = "Identification invalide";

                $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));
            }
        } else {
            $msgError = "Identification incomplete";

            $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));
        }
    break;
    default:

        $Vue->addToCorps(new Vue_Connexion_Formulaire_client());

        break;
}


$Vue->setBasDePage(new Vue_Structure_BasDePage());