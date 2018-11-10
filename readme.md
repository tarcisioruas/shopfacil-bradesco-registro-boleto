[![Build Status](https://travis-ci.org/tarcisioruas/shopfacil-bradesco-registro-boleto.svg?branch=master)](https://travis-ci.org/tarcisioruas/shopfacil-bradesco-registro-boleto) [![Codecov](https://img.shields.io/codecov/c/github/tarcisioruas/shopfacil-bradesco-registro-boleto.svg)](https://codecov.io/gh/tarcisioruas/shopfacil-bradesco-registro-boleto)
[![Quality Gate](https://sonarcloud.io/api/project_badges/measure?project=tarcisioruas_shopfacil-bradesco-registro-boleto&metric=alert_status)](https://sonarcloud.io/dashboard/index/tarcisioruas_shopfacil-bradesco-registro-boleto)

# PHP ShopFacil Bradesco Registro de Boletos SDK

Este SDK é designado à ajudar desenvolvedores PHP à integrar seus projetos com ShopFacil Bradesco no intuito de registrar boletos..

#### Instalação
```
composer require "tarcisioruas/shopfacil-bradesco-registro-boleto"
```
<details>
 <summary>Precisa de ajuda com a instalação?</summary>

## Instale o Composer
Se o comando de instalação acima não funcionar, instale o composer usando as instruções de instalação abaixo e tente novamente.

#### Debian / Ubuntu
```
sudo apt-get install curl
curl -s http://getcomposer.org/installer | php
php composer.phar install
```
Após a instalação do composer, repita o comando de instalação do sdk abaixo:
```
php composer.phar require "tarcisioruas/shopfacil-bradesco-registro-boleto"
```

#### Windows:
[Faça o download do Composer para Windows](https://getcomposer.org/doc/00-intro.md#installation-windows)
</details>

#### Como Usar
##### Configurações Preliminares

Para se conectar à API do ShopFacil Bradesco, é necessário ter em mãos as credenciais de acesso. Para obter às suas, entrem em contato com o suporte responsável.

##### Códigos de Exemplo

Registrando um boleto.
```php
<?php
require 'vendor/autoload.php';

use ShopFacil\Registro\Pessoa;
use ShopFacil\Registro\Boleto;
use ShopFacil\Registro\Registro;

$nome = 'Nome de Uma Pessoa';
$cpf = '1234567890';
$pagador = new Pessoa($nome, $cpf);

/*
 * Definindo o endereço do pagador, esse passo só é necessário caso o banco seja o responsável por emitir 
 * a papeleta. 
 */
$pagador->setEnderecoCEP('12345678')
        ->setEnderecoLogradouro('Um Logradouro')
        ->setEnderecoNumero('123')
        ->setEnderecoBairro('Um Bairro')
        ->setEnderecoCidade('São Paulo')
        ->setEnderecoComplemento('Bloco 10, Apto 444')
        ->setEnderecoUF('SP');

// Iniciando a configuração do boleto
$valorDoBoleto = 150.30;
$vencimento = '2018-12-24';
$nossoNumero = 1234; //Indentificador do Boleto, pedido ou referencia interna do sistema
$boleto = new Boleto($pagador, $valorDoBoleto, $vencimento, $nossoNumero);

// Definindo um percentual de multa, caso seja desejável (2.00%)
$boleto->setPercentualMulta(2);

// Definindo um percentual de juros, caso seja desejável (0.033%)
$boleto->setPercentualJuros(0.033);


// Definindo um desconto por antecipação, caso seja desejável
// Atribuindo valor de descontos
$descontoPorPagamentoAntecipado = 20.45;
$dataLimiteDeDesconto = '2018-12-10';
$boleto->setValorDesconto($descontoPorPagamentoAntecipado, $dataLimiteDeDesconto);

// Requisitando o registro do boleto
$merchantId = 'seuMerchantIdAqui';
$senha = 'suaSenhaAqui';

// Ou Registro::PRODUCAO
$ambiente = Registro::HOMOLOGACAO;

$registro = new Registro($ambiente, $merchantId, $senha);

try
{
    $retorno = $registro->registrar($boleto);
} 
catch (EntidadeException $e) 
{
    var_dump($e->getInconsistencias());
}
catch (HttpException $e) 
{
    echo $e->getMessage();
}

// Verificando se o boleto foi registrado com sucesso
if ($retorno->registrado()) {
    echo 'Boleto registrado com sucesso <br />';
}


// Mostrando código de retorno e mensagem
$codigoRespostaHttp = $retorno->getCodigoRespostaHttp();
$codigoResposta = $retorno->getCodigoResposta();
$mensagemResposta = $retorno->getMensagemResposta();

echo $codigoRespostaHttp . ' - ' . $codigoResposta . ' - ' . $mensagemResposta . '<br />';
```

### Desenvolvimento

Quer contribuir? Ótimo!

Se encontrou e corrigiu um bug ou implementou uma nova funcionalidade, sinta-se à vontade para nos enviar um pull request. Você será adicionado à lista de desenvolvedores automaticamente.
