<?php 
namespace ShopFacil\Registro\Exceptions;

class HttpException extends \Exception
{
    private $url;
    private $metodoHttp;

    function __construct($mensagem, $codigo, $url, $metodoHttp) 
    {
        $this->url = $url;
        $this->metodoHttp = $metodoHttp;       
        parent::__construct($mensagem, $codigo);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getMetodoHttp()
    {
        return $this->metodoHttp;
    }
}