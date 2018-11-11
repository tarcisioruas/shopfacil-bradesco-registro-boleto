<?php
namespace ShopFacil\Registro;

use ShopFacil\Registro\Exceptions\RegistroException;
use ShopFacil\Registro\Interfaces\EntidadeInterface;
use ShopFacil\Registro\Interfaces\HttpInterface;

class Registro 
{
    const PRODUCAO = 'https://meiosdepagamentobradesco.com.br/apiregistro/api';
    const HOMOLOGACAO = 'https://homolog.meiosdepagamentobradesco.com.br/apiregistro/api';
    
    private $ambiente;
    private $merchantId;
    private $senha;
    private $http;

    function __construct($ambiente = SELF::HOMOLOGACAO, $merchantId = null, $senha = null, HttpInterface $http = null)
    {
        $this->http = is_null($http) ? new Http() : $http;
        $this->ambiente = $ambiente;
        $this->merchantId = is_null($merchantId) ? getenv('SHOPFACIL_MERCHANT_ID') : $merchantId;
        $this->senha = is_null($senha) ? getenv('SHOPFACIL_SENHA') : $senha;

        if (!$this->merchantId) {
            throw new RegistroException('MerchantId não infomado ao objeto "Registro" ou via váriaveis de ambiente');
        }

        if (!$this->senha) {
            throw new RegistroException('Senha não infomada ao instanciar "Registro" ou via váriaveis de ambiente');
        }
    }

    public function registrar(EntidadeInterface $boleto)
    {
        $dadosRequisicao = [
            'merchant_id' => $this->merchantId,
            'boleto' => $boleto->toArray()
        ];

        return $this->requisitaRegistro($dadosRequisicao);
    }

    private function requisitaRegistro($dadosRequisicao) 
    {
        $cabecalhos = [
            'Authorization: Basic ' . base64_encode($this->merchantId . ':' . $this->senha),
            'Accept: application/json',
            'Accept-Charset: UTF-8',
            'Content-Type: application/json;UTF-8'
        ];

        $resposta = $this->http->post($this->ambiente, json_encode($dadosRequisicao), $cabecalhos);
        $requestInfo = $this->http->getInfo();
         
        $codigo = null;
        $mensagem = '';

        if(!empty($resposta)) {
            $resposta = json_decode($resposta);
            $codigo = $resposta->status->codigo;
            $mensagem = $resposta->status->mensagem;
        }

        return new RetornoRegistro($requestInfo['http_code'], $codigo, $mensagem);
    }
}