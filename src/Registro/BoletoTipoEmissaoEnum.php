<?php
namespace ShopFacil\Registro;

class BoletoTipoEmissaoEnum
{
    /**
     * Cliente/Cedente emite papeleta e banco somente registra o boleto
     */
    const CEDENTE_EMITE = 2;

    /**
     * Banco registra e emite papeleta, enviando-a ao endereço do pagador 
     */
    const BANCO_EMITE = 1;
}