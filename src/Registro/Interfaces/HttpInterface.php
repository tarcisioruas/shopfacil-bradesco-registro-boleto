<?php
namespace ShopFacil\Registro\Interfaces;

interface HttpInterface
{
    public function post($url, $params, $headers);
    public function getInfo(); 
}