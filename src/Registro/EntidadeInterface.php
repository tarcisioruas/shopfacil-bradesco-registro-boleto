<?php

namespace ShopFacil\Registro;
interface EntidadeInterface
{
    public function toArray(); 
    public function consistente();
    public function getInconsistencias();       
}
?>