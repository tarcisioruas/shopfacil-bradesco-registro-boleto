<?php
namespace ShopFacil\Registro;

abstract class EntidadeAbstract implements EntidadeInterface
{
    private $inconsistencias = [];

    protected function addInconsistencia( $codigo , $inconsistencia )
    {
        $this->inconsistencias[$codigo] = $inconsistencia;
    }

    public function getInconsistencias()
    {
        return $this->inconsistencias;
    }

    abstract protected function verificaConsistencia();    

    /**
     * Verifica se dados da entidade estão consistentes
     * @return boolean true para consistente e false para inconsisteste
     */
    public function consistente()
    {
        $this->verificaConsistencia();
        if( count( $this->inconsistencias ) > 0 )
            return false;
        return true;
    }
}

?>