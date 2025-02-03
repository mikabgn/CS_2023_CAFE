<?php

use PHPUnit\Framework\TestCase;
require_once 'CSRF--reload.php';
class CSRF_Test extends TestCase
{
    protected function setUp(): void
    {
        session_start();
        $_SESSION = [];
    }
    public function testGenereCSRFInitial()
    {
        $token = genereCSRF();
        $this->assertNotEmpty($token, "Un nouveau token CSRF doit être créé pour chaque session.");
        $this->assertIsString($token, "Le token CSRF doit être une chaîne de caractères.");
        $this->assertArrayHasKey("CSRF", $_SESSION, "La variable de session CSRF doit être définie.");
        $this->assertCount(1, $_SESSION["CSRF"], "La session CSRF doit contenir un token.");
    }
    public function testGenereCSRFReutilisation()
    {
        $token1 = genereCSRF();
        $token2 = genereCSRF();
        $this->assertSame($token1, $token2, "Le token CSRF doit être refusé si il n'est pas utilisé.");
    }
    public function testGenereCSRFApresutilisation()
    {
        $token1 = genereCSRF();
        verifierCSRF($token1);
        $token2 = genereCSRF();
        $this->assertNotSame($token1, $token2, "Un nouveau token CSRF doit être généré après utilisation.");
        $this->assertCount(2, $_SESSION["CSRF"], "La session CSRF doit contenir deux token.");
    }
    public function testGenereChampMasqueCSRF()
    {
        $hiddenField = genereChampHiddenCSRF();
        $this->assertStringContainsString('type="hidden"', $hiddenField, "Le champ caché doit contenir le token CSRF.");
        $this->assertStringContainsString('name="CSRF"', $hiddenField, "Le champ caché doit avoir le nom 'CSRF'.");
    }
    public function testGenereVarHrefCSRF()
    {
        $hrefVar = genereVarHrefCSRF();
        $this->assertStringStartsWith('&CSRF=', $hrefVar, "La variable Href doit commencer par '&CSRF='.");
    }
    public function testVerifierCSRFValid()
    {
        $token = genereCSRF();
        $isValid = verifierCSRF($token);
        $this->assertTrue($isValid, "Le token CSRF doit être valide.");
        $this->assertEquals(1, $_SESSION["CSRF"][0]["nbUsage"], "Le nombre d'utilisations du token CSRF doit être incrémenté.");
    }
    public function testVerifierCSRFInvalid()
    {
        $isValid = verifierCSRF('invalid_token');
        $this->assertFalse($isValid, "Le token CSRF doit être invalide.");
    }
    public function testDireIsReload()
    {
        $token1 = genereCSRF();
        verifierCSRF($token1);
        $token2 = genereCSRF();
        $this->assertFalse(direIsReload(), "La page ne doit pas être rafraichie.");
        verifierCSRF($token2);
        $token3 = genereCSRF();
        $this->assertTrue(direIsReload(), "La page devrait se recharger après la seconde utilisation du jeton.");
    }
}