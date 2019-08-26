<?php
use PHPUnit\Framework\TestCase;
use ShopFacil\Registro\StreamHttp;

class StreamHttpTest extends TestCase
{
    private $http;

    public function setUp()
    {
        $this->http = new StreamHttp();
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
}