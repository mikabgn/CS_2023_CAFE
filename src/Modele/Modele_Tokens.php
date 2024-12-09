<?php
namespace App\Modele;
use App\Utilitaire\Singleton_ConnexionPDO;
use DateTime;
use PDO;

class Modele_tokens
{
    function Tokens_Select_By_id($idTokens)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare('
        select *
        from token
        where id = :idTokens');
        $requetePreparee->bindValue('idTokens', $idTokens);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparee->fetchAll(PDO::FETCH_ASSOC);
        if (count($tableauReponse) == 1)
            return $tableauReponse[0];
        return false;
    }

    function Tokens_Select_By_value($Tokens)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare('
        select *
        from token
        where valeur = :value');
        $requetePreparee->bindValue('value', $Tokens);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparee->fetchAll(PDO::FETCH_ASSOC);
        if (count($tableauReponse) == 1)
            return $tableauReponse[0];
        return false;
    }

    function Tokens_Creer($codeAction, $idUtilisateur,$dateFin)
    {
        $dateFin= new \DateTime($dateFin);
        $dateFin=$dateFin->format('Y-m-d H:i:s');

        $octetsAleatoires = openssl_random_pseudo_bytes (256) ;
        $jeton = sodium_bin2base64($octetsAleatoires, SODIUM_BASE64_VARIANT_ORIGINAL);

        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare(
            'INSERT INTO `token`
         VALUES (NULL,:paramValeur, :paramCodeAction, :paramIdUtilisateur, :paramDateFin);');
        $requetePreparee->bindParam('paramValeur', $jeton);
        $requetePreparee->bindParam('paramCodeAction', $codeAction);
        $requetePreparee->bindParam('paramIdUtilisateur', $idUtilisateur);
        $requetePreparee->bindParam('paramDateFin', $dateFin);
        $requetePreparee->execute();
    }

    function Tokens_Update_date($id, $dateFin)
    {
        $dateFin = new \DateTime($dateFin);
        $dateFin = $dateFin->format('Y-m-d H:i:s');
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare(
            'UPDATE `token`
            SET dateFin = :paramDateFin
            WHERE id = :id;');
        $requetePreparee->bindParam('id', $id);
        $requetePreparee->bindParam('paramDateFin', $dateFin);
        $requetePreparee->execute();
    }
}