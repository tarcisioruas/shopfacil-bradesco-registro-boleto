<?php
namespace ShopFacil\Registro;

class BoletoAceiteEnum
{
    /**
     * Pagador confirmou o recebimento do boleto
     */
    const SIM = 'S';

    /**
     * Não é necessário o pagador confirmar o aceite do boleto
     */
    const NAO = 'N';
}