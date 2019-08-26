<?php
namespace ShopFacil\Registro;

use ShopFacil\Registro\Interfaces\HttpInterface;
use ShopFacil\Registro\Exceptions\HttpException;

class StreamHttp implements HttpInterface
{
    private $info = [];
    
    public function post($url, $dados, array $cabecalhos)
    {
        $this->ativandoErroComoExcessao();

        $cabecalho = '';
        if (is_array($cabecalhos) && count($cabecalhos) > 0) {
            foreach ($cabecalhos AS $chave => $valor) {
                $cabecalho .= $valor . "\r\n"; 
            }
        } 

        $cabecalho .= 'Content-Length: ' . sizeof($dados);

        $opcoes = array('http' =>
            array(
                'method' => 'POST',
                'header' => $cabecalho,
                'content' => $dados
            )
        );

        $contexto = stream_context_create($opcoes);

        try {
            $recurso = fopen($url, 'r', false, $contexto);  

            $this->info = $this->processaMetaDados(stream_get_meta_data($recurso));

            $retorno = stream_get_contents($recurso);

            fclose($recurso);

        } catch (\ErrorException $e) {
            $this->restaurandoModoDeErro();
            throw new HttpException(('Erro ao efetuar requisição: ' . $e->getMessage()), 1, $url, 'post');
        }

        $this->restaurandoModoDeErro();

        return $retorno;
    }

    private function ativandoErroComoExcessao() 
    {
        set_error_handler(function ($severity, $message, $file, $line) {
            throw new \ErrorException($message, $severity, $severity, $file, $line);
        });
    }

    private function restaurandoModoDeErro() 
    {
        restore_error_handler();
    }

    private function processaMetaDados($metaDados) 
    {
        $status = $metaDados['wrapper_data'][0];
        $status = trim(str_replace('HTTP/1.1', '', $status));
        $status = preg_replace("/[^0-9]/", "", $status);

        return ['http_code' => (int)$status];
    }

    public function getInfo()
    {
        return $this->info;
    }
}