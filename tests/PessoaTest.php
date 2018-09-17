<?php
use PHPUnit\Framework\TestCase;
use ShopFacil\Registro\EntidadeInterface;
use ShopFacil\Registro\Pessoa;

class PessoaTest extends TestCase
{
    private $pessoa;

    public function setUp()
    {
        $this->pessoa = new Pessoa('Uma pessoa', '1234567890');
    }

    public function testPessoaExtendeEntidade()
    {
        $this->assertInstanceOf(EntidadeInterface::class, $this->pessoa);
    }

    public function testValidandoComDadosInconsistentes()
    {
        $this->assertEquals(false, $this->pessoa->consistente());
    }

    public function testValidandoComDadosConsistentes()
    {
        $this->pessoa->setEnderecoCEP('12345678')
                    ->setEnderecoLogradouro('Um Logradouro')
                    ->setEnderecoNumero('123')
                    ->setEnderecoBairro('Um Bairro')
                    ->setEnderecoCidade('São Paulo')
                    ->setEnderecoUF('SP');

        $this->assertEquals(true, $this->pessoa->consistente());
    }

    public function testValidandoObrigatoriedadeDoCep()
    {
        //verificando consistencia
        $this->pessoa->consistente();

        //Verificando se o erro de CEP retorna quando ele nao é passado
        $this->assertArrayHasKey('enderecoCep', $this->pessoa->getInconsistencias());
    }

    public function testValidandoObrigatoriedadeDoLogradouro()
    {
        //verificando consistencia
        $this->pessoa->consistente();

        //Verificando se o erro de Logradouro retorna quando ele nao é passado
        $this->assertArrayHasKey('enderecoLogradouro', $this->pessoa->getInconsistencias());
    }

    public function testValidandoObrigatoriedadeDoNumero()
    {
        //verificando consistencia
        $this->pessoa->consistente();

        //Verificando se o erro de numero retorna quando ele nao é passado
        $this->assertArrayHasKey('enderecoNumero', $this->pessoa->getInconsistencias());
    }

    public function testValidandoObrigatoriedadeDoBairro()
    {
        //verificando consistencia
        $this->pessoa->consistente();

        //Verificando se o erro de bairro retorna quando ele nao é passado
        $this->assertArrayHasKey('enderecoBairro', $this->pessoa->getInconsistencias());
    }

    public function testValidandoObrigatoriedadeDaCidade()
    {
        //verificando consistencia
        $this->pessoa->consistente();

        //Verificando se o erro de cidade retorna quando ele nao é passado
        $this->assertArrayHasKey('enderecoCidade', $this->pessoa->getInconsistencias());
    }

    public function testValidandoObrigatoriedadeDaUf()
    {
        //verificando consistencia
        $this->pessoa->consistente();

        //Verificando se o erro de cidade retorna quando ele nao é passado
        $this->assertArrayHasKey('enderecoUF', $this->pessoa->getInconsistencias());
    }


    public function testValidandoArrayDeSaida()
    {
        $this->pessoa->setEnderecoCEP('12345678')
                    ->setEnderecoLogradouro('Um Logradouro')
                    ->setEnderecoNumero('123')
                    ->setEnderecoBairro('Um Bairro')
                    ->setEnderecoCidade('São Paulo')
                    ->setEnderecoComplemento('Bloco 10, Apto 444')
                    ->setEnderecoUF('SP');

        $arrayEsperado = [
            'nome' => 'Uma pessoa',
            'documento' => '1234567890',
            'tipo_documento' => '1',
            'endereco' => [
                'cep' => '12345678',
                'logradouro' => 'Um Logradouro',
                'numero' => '123',
                'complemento' => 'Bloco 10, Apto 444',
                'bairro' => 'Um Bairro',
                'cidade' => 'São Paulo',
                'uf' => 'SP'
            ]
        ];

        $this->assertEquals($arrayEsperado, $this->pessoa->toArray());
    }

    /**
     * @expectedException ShopFacil\Registro\EntidadeException
     */
    public function testValidandoExceptionEmSaidasInvalidas()
    {
        $this->pessoa->setEnderecoCEP('fasd')
                    ->setEnderecoUF('SPF')
                    ->toArray();
    }
}
?>