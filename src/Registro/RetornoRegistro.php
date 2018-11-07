<?php
namespace ShopFacil\Registro;

class RetornoRegistro
{
    private $codigoRespostaHttp;
    private $codigoResposta;
    private $mensagemResposta;

    function __construct($codigoRespostaHttp, $codigoResposta, $mensagemResposta) 
    {
        $this->codigoRespostaHttp = $codigoRespostaHttp;
        $this->codigoResposta = $codigoResposta;
        $this->mensagemResposta = $mensagemResposta;
    }

    public function registrado()
    {
        //Retorna 200, mas mesmo assim registra boleto
        $registrados = [0, 930051, 930053];            
        if(($this->codigoRespostaHttp == 200 && in_array($this->codigoResposta , $registrados)) || $this->codigoRespostaHttp == 201)  {
            return true;
        }
            
        return false;
    }

    public function getCodigoRespostaHttp()
    {
        return $this->codigoRespostaHttp;
    }

    public function getCodigoResposta()
    {
        return $this->codigoResposta;
    }

    public function getMensagemResposta()
    {
        return $this->mensagemResposta;
    }
}