<?php
namespace ShopFacil\Registro;

use ShopFacil\Registro\Interfaces\EntidadeInterface;
use ShopFacil\Registro\Exceptions\EntidadeException;

/**
 * Abstração de uma entidade
 */
abstract class EntidadeAbstract implements EntidadeInterface
{
    private $inconsistencias = [];

    /**
     * Adiciona uma nova inconsistencia
     * @param String $codigo - Identificador da inconsistencia
     * @param String $inconsistencia - Texto da inconsistencia
     */
    protected function addInconsistencia($codigo, $inconsistencia)
    {
        $this->inconsistencias[$codigo] = $inconsistencia;
    }

    /**
     * Retorna todas as inconsistencias
     */
    public function getInconsistencias()
    {
        return $this->inconsistencias;
    }

    /**
     * Verifica todas as inconsistencias relacionadas à uma entidade
     */
    abstract protected function verificaConsistencia();    

    /**
     * Verifica se dados da entidade estão consistentes
     * @return boolean true para consistente e false para inconsisteste
     */
    public function consistente()
    {
        $this->verificaConsistencia();
        if(count($this->inconsistencias) > 0 )
            return false;
        return true;
    }

    /**
     * Converte dados da entidade para um Array
     */
    public function toArray()
    {
        if(!$this->consistente()) {
            throw new EntidadeException("Há inconsistencias nos dados, use o método EntidadeException::getInconsistencias() para verificar", null, $this->getInconsistencias());
        }

        return $this->_toArray();
    }
}