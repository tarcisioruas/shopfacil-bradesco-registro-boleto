<?php 
namespace ShopFacil\Registro\Exceptions;

class EntidadeException extends \Exception
{
    private $inconsistencias =  [];

    function __construct($mensagem, $codigo = null, array $inconsistencias = []) 
    {
        $this->inconsistencias = $inconsistencias;
        parent::__construct($mensagem, $codigo);
    }

    public function getInconsistencias()
    {
        return $this->inconsistencias;
    }
}