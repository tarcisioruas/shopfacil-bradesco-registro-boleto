<?php
namespace ShopFacil\Registro\Interfaces;

interface HttpInterface
{
    public function post($url, $params, array $headers);
    public function getInfo(); 
}