<?php
use PHPUnit\Framework\TestCase;
use ShopFacil\Registro\Exceptions\HttpException;

class HttpExceptionTest extends TestCase
{
   
    /**
     * @expectedException ShopFacil\Registro\Exceptions\HttpException
     */
    public function testPodeSerLancada()
    {
        throw new HttpException('Um erro qualquer', null, 'https://www.google.com.br', 'post');
    }

    public function testGetUrl()
    {
        $excecao = new HttpException('Um erro qualquer', null, 'https://www.google.com.br', 'post');
        $this->assertEquals('https://www.google.com.br', $excecao->getUrl());
    }

    public function testGeMetodoHttp()
    {
        $excecao = new HttpException('Um erro qualquer', null, 'https://www.google.com.br', 'post');
        $this->assertEquals('post', $excecao->getMetodoHttp());
    }
}