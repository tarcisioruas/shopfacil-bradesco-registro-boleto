<?php
namespace ShopFacil\Registro;

use ShopFacil\Registro\Interfaces\HttpInterface;
use ShopFacil\Registro\Exceptions\HttpException;

class CurlHttp implements HttpInterface
{
    private $info = [];
    
    public function post($url, $dados, array $cabecalhos)
    {
        $canal = curl_init();
        curl_setopt($canal, CURLOPT_URL, $url);
        curl_setopt($canal, CURLOPT_POST, 1);
        curl_setopt($canal, CURLOPT_POSTFIELDS, $dados);
        curl_setopt($canal, CURLOPT_HTTPHEADER, $cabecalhos);
        curl_setopt($canal, CURLOPT_RETURNTRANSFER, true);

        $resposta = curl_exec($canal);

        if (!curl_errno($canal)) {
            $this->info = curl_getinfo($canal);
        }    

        if ($resposta === false) {
            throw new HttpException(('Erro ao efetuar requisição: ' . curl_error($canal)), 1, $url, 'post');
        }

        curl_close($canal);

        return $resposta;
    }

    public function getInfo()
    {
        return $this->info;
    }
}