<?php

use PHPUnit\Framework\TestCase;

class CalculTestComplexiterMdp extends TestCase
{
    public function testMdpAvecMinuscule()
    {
        $this->assertEquals(23 , App\Fonctions\CalculComplexiteMdp("aubry"));
    }
    public function testMdpAvecMinuscule_Speciale()
    {
        $this->assertEquals(51  , App\Fonctions\CalculComplexiteMdp("super@ubry"));
    }
    public function testMdpAvecMinuscule_Majuscule_Speciale_Nombre()
    {
        $this->assertEquals(86 , App\Fonctions\CalculComplexiteMdp("Super@ubry2022"));
    }
    public function testMdpAvecMinuscule_Majuscule_Speciale_Nombre__PlusLong()
    {
        $this->assertEquals(141 , App\Fonctions\CalculComplexiteMdp("Giroud-Président||2027"));
    }
}