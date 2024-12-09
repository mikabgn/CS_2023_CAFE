<?php
namespace App\Vue;
use App\Utilitaire\Vue_Composant;

class Vue_Mail_ReinitMdp extends Vue_Composant
{
    public function __construct()
    {
    }

    function donneTexte(): string
    {
        $str= "  
  <form action='index.php' method='post' style='    width: 50%;    display: block;    margin: 25px auto;'>
             
                <h1>Mail à renouveler</h1>
                
                <label><b>Compte</b></label>
                <input type='email' placeholder='mail du compte à renouveler le mdp' name='email' required>
                
                <button type='submit' id='submit' name='action' 
                            value='reinitmdpconfirm'>
                            Renouveler mdp
                </button>";
        $str .=  "
 </form>
    
 <form action='index.php' method='post' style='    width: 50%;    display: block;    margin: auto;'>
             
               <h1>Mail à renouveler par Token</h1>
                
               <label><b>Compte</b></label>
               <input type='email' placeholder='mail du compte à renouveler le mdp' name='email' required>
                
               <button type='submit' id='submit' name='action' 
                           value='reinitmdpconfirmTokens'>
                           Renouveler mdp
               </button>";
        $str .=  "
</form>
    ";

        return $str;
    }
}