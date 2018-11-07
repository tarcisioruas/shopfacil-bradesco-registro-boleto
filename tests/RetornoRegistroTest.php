<?php
use PHPUnit\Framework\TestCase;

use ShopFacil\Registro\RetornoRegistro;


class RetornoRegistroTest extends TestCase
{
    private $retorno;
    
    public function setUp()
    {
        $this->retorno = new RetornoRegistro(200, 999, 'Um retorno qualquer');
    }

    public function testGetCodigoRespostaHttp()
    {
        $this->assertEquals(200, $this->retorno->getCodigoRespostaHttp());
    }

    public function testGetCodigoResposta()
    {
        $this->assertEquals(999, $this->retorno->getCodigoResposta());
    }

    public function testGetMensagemResposta()
    {
        $this->assertEquals('Um retorno qualquer', $this->retorno->getMensagemResposta());
    }

    public function testRegistrado()
    {
        $this->assertNotTrue($this->retorno->registrado());
    }

    public function testRegistradoStatusCode201()
    {
        $retorno = new RetornoRegistro(201, 999, 'Um retorno qualquer');
        $this->assertTrue($retorno->registrado());
    }

    public function testRegistradoStatusCode200CodigoRetorno0()
    {
        $retorno = new RetornoRegistro(201, 0, 'Um retorno qualquer');
        $this->assertTrue($retorno->registrado());
    }

    public function testRegistradoStatusCode200CodigoRetorno930051()
    {
        $retorno = new RetornoRegistro(200, 930051, 'Um retorno qualquer');
        $this->assertTrue($retorno->registrado());
    }

    public function testRegistradoStatusCode200CodigoRetorno930053()
    {
        $retorno = new RetornoRegistro(200, 930053, 'Um retorno qualquer');
        $this->assertTrue($retorno->registrado());
    }
}