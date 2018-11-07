<?php
use PHPUnit\Framework\TestCase;
use ShopFacil\Registro\Exceptions\EntidadeException;

class EntidadeExceptionTest extends TestCase
{
   
    /**
     * @expectedException ShopFacil\Registro\Exceptions\EntidadeException
     */
    public function testPodeSerLancada()
    {
        throw new EntidadeException('Um erro qualquer', null, ['umachave' => 'uma mensagem de erro']);
    }

    public function testGetInconsistencias()
    {
        $excecao = new EntidadeException('Um erro qualquer', null, ['umachave' => 'uma mensagem de erro']);
        $this->assertEquals(['umachave' => 'uma mensagem de erro'], $excecao->getInconsistencias());
    }

    public function testGetInconsistenciasVazio()
    {
        $excecao = new EntidadeException('Um erro qualquer');
        $this->assertEquals([], $excecao->getInconsistencias());
    }
}