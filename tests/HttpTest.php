<?php
use PHPUnit\Framework\TestCase;
use ShopFacil\Registro\Http;

class HttpTest extends TestCase
{
    private $http;

    public function setUp()
    {
        $this->http = new Http();
    }
    
    /**
     * @expectedException ShopFacil\Registro\Exceptions\HttpException
     */
    public function testFailPost()
    {
        $this->http->post('http://localhost:9999', [], []);
    }

    public function testSemInformacoes()
    {
        $this->assertEquals([], $this->http->getInfo());
    }

    public function testPost()
    {
        $resposta = $this->http->post('https://www.google.com.br/search', ['q' => 'phpunit'], []);
        $info = $this->http->getInfo();
        $this->assertEquals(405, $info['http_code']);
    }
}