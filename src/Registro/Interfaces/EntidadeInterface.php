<?php
namespace ShopFacil\Registro\Interfaces;

interface EntidadeInterface
{
    public function toArray(); 
    public function consistente();
    public function getInconsistencias();       
}