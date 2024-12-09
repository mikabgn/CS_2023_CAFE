<?php
namespace App\Fonctions;
use PHPMailer\PHPMailer\PHPMailer;

    function Redirect_Self_URL():void{
        unset($_REQUEST);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

function GenereMDP($nbChar) :string{

    return "secret";
}
function CalculComplexiteMdp($mdp) :int
{
    $nbChar = strlen($mdp);
    $baseCara = 0;
    if (preg_match('/[a-z]/', $mdp)) $baseCara += 26 ;

    if (preg_match('/[A-Z]/', $mdp)) $baseCara += 26 ;

    if (preg_match('/[0-9]/', $mdp)) $baseCara += 10 ;

    if (preg_match('/[^a-zA-Z0-9]/', $mdp)) $baseCara += 10;

    return $nbChar * log($baseCara, 2);
}

function tokenMotDePasse($nbChar){
    $chaine = "ABCDEFGHIJKLMONOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789&é\"'(-è_çà)=$^*ù!:;,~#{[|`\^@]}¤€";
    srand((double)microtime() * random_int(1,1000000) * rand(1,1000000));
    $pass = '';
    for ($i = 0; $i < $nbChar; $i++) {
        $pass .= $chaine[rand() % strlen($chaine)];
    }
    return $pass;
}
    function envoieMailTokens($valeurToken)
    {

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = '127.0.0.1';
        $mail->Port = 1025;
        $mail->SMTPAuth = false;
        $mail->SMTPAutoTLS = false;
        $mail->setFrom('café@café.fr', 'café');
        $mail->addAddress($_POST["email"], 'Mon client');
        if ($mail->addReplyTo($_POST["email"], 'café')) {
            $mail->Subject = 'Objet : Réinitialisation de mot de passe !';
            $mail->isHTML(true);
            $mail->Body = "Veuillez cliquer sur ce lien pour réinitialiser votre mdp : <a href='http://localhost:8001/index.php?action=token&token=$valeurToken'>Lien à cliquer </a>";
            if (!$mail->send()) {
                $msg = 'Désolé, quelque chose a mal tourné. Veuillez réessayer plus tard.';
            } else {
                $msg = 'Message envoyé ! Merci de nous avoir contactés.';
            }
        } else {
            $msg = 'Il doit manquer quelque chose !';
        }
        echo $msg;
    }
function envoieMail($pass)
{
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = '127.0.0.1';
    $mail->Port = 1025;
    $mail->SMTPAuth = false;
    $mail->SMTPAutoTLS = false;
    $mail->setFrom('café@café.fr', 'café');
    $mail->addAddress($_POST["email"], 'Mon client');
    if ($mail->addReplyTo($_POST["email"], 'café')) {
        $mail->Subject = 'Objet : Réinitialisation de mot de passe !';
        $mail->isHTML(false);
        $mail->Body = "Votre mot de passe à usage unique est le suivant : ".$pass;
        if (!$mail->send()) {
            $msg = 'Désolé, quelque chose a mal tourné. Veuillez réessayer plus tard.';
        } else {
            $msg = 'Message envoyé ! Merci de nous avoir contactés.';
        }
    } else {
        $msg = 'Il doit manquer quelque chose !';
    }
    echo $msg;
}

