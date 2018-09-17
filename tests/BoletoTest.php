<?php
use PHPUnit\Framework\TestCase;
use ShopFacil\Registro\EntidadeInterface;
use ShopFacil\Registro\Boleto;
use ShopFacil\Registro\BoletoEspecieEnum;
use ShopFacil\Registro\BoletoTipoEmissaoEnum;


class BoletoTest extends TestCase
{
    private $pessoa;
    private $boleto;
    private $dadosValidos;
    private $valorBoleto;

    public function setUp()
    {
        $this->valorBoleto = 200.45;

        $date = (new DateTime())
                ->add(new DateInterval('P10D'))
                ->format('Y-m-d');

        $this->pessoa = $this->mockPessoa();
        $this->boleto = new Boleto($this->pessoa, $this->valorBoleto, $date, 1234);
        $this->dadosValidos = [
            'carteira' => 26, // Carteira usada pelo realtime
            'nosso_numero' => 1234,
            'numero_documento' => 1234,
            'data_emissao' => date('Y-m-d'),
            'data_vencimento' => $date,
            'valor_titulo' => '20045', // Retira a pontuação e conserva até duas casas decimais
            'pagador' => $this->pessoa->toArray(), // Intuito não é testar a classe de pessoa
            'informacoes_opcionais' => [
                'controle_participante' => 1234,
                'especie' => 'DV',
                'aceite' => 'N',
                'tipo_emissao_papeleta' => 2
            ]
        ];
    }

    public function testBoletoExtendeEntidade()
    {
        $this->assertInstanceOf(EntidadeInterface::class, $this->boleto);
    }

    public function testValidandoComDadosInconsistentes()
    {
        $boleto = $this->makeBoletoInconsistente();

        $this->assertEquals(false, $boleto->consistente());
    }

    public function testValidandoComDadosConsistentes()
    {
        $this->boleto->consistente();
        $this->assertEquals(true, $this->boleto->consistente());
    }

    public function testValidandoValidacaoDeValor()
    {
        $boleto = $this->makeBoletoInconsistente();

        // verificando consistencia
        $boleto->consistente();

        // Verificando se o erro de valor é retornado
        $this->assertArrayHasKey('valorTitulo', $boleto->getInconsistencias());
    }

    public function testValidandoValidacaoDeDataDeVencimento()
    {
        $boleto = $this->makeBoletoInconsistente();

        // Verificando consistencia
        $boleto->consistente();

        // Verificando se o erro de data de vencimento é retornado
        $this->assertArrayHasKey('dataVencimento', $boleto->getInconsistencias());
    }

    public function testValidandoValidacaoDeNossoNumero()
    {
        $boleto = $this->makeBoletoInconsistente();
        $boleto->consistente();

        // Verificando se o erro de nosso numero
        $this->assertArrayHasKey('nossoNumero', $boleto->getInconsistencias());
    }

    public function testValidandoValidacaoDeCarteira()
    {
        $boleto = $this->makeBoletoInconsistente();
        $boleto->consistente();

        // Verificando se o erro de carteira
        $this->assertArrayHasKey('carteira', $boleto->getInconsistencias());
    }

    public function testValidandoValidacaoDeNumeroDocumento()
    {
        $boleto = $this->makeBoletoInconsistente();
        $boleto->consistente();

        // Verificando se o erro de numero documento
        $this->assertArrayHasKey('numeroDocumento', $boleto->getInconsistencias());
    }

    public function testValidandoArrayDeSaida()
    {
        $this->assertEquals($this->dadosValidos, $this->boleto->toArray());
    }

    public function testValidandoSetCarteira()
    {
        $this->boleto->setCarteira(456);
        $this->dadosValidos['carteira'] = 456;
        $this->assertEquals($this->dadosValidos, $this->boleto->toArray());
    }

    public function testValidandoSetControleParticipante()
    {
        $this->boleto->setControleParticipante(102040);
        $this->dadosValidos['informacoes_opcionais']['controle_participante'] = 102040;
        $this->assertEquals($this->dadosValidos, $this->boleto->toArray());
    }

    public function testValidandoSetNossoNumero()
    {
        $this->boleto->setNumeroDocumento(4321);
        $this->dadosValidos['numero_documento'] = 4321;
        $this->assertEquals($this->dadosValidos, $this->boleto->toArray());
    }

    public function testValidandoSetEspecie()
    {
        $this->boleto->setEspecie(BoletoEspecieEnum::MENSALIDADE_ESCOLAR);
        $this->dadosValidos['informacoes_opcionais']['especie'] = BoletoEspecieEnum::MENSALIDADE_ESCOLAR;
        $this->assertEquals($this->dadosValidos, $this->boleto->toArray());
    }

    public function testValidandoSetTipoEmissao()
    {
        $this->boleto->setTipoEmissao(BoletoTipoEmissaoEnum::BANCO_EMITE);
        $this->dadosValidos['informacoes_opcionais']['tipo_emissao_papeleta'] = BoletoTipoEmissaoEnum::BANCO_EMITE;
        $this->assertEquals($this->dadosValidos, $this->boleto->toArray());

        $this->boleto->setTipoEmissao(BoletoTipoEmissaoEnum::CEDENTE_EMITE);
        $this->dadosValidos['informacoes_opcionais']['tipo_emissao_papeleta'] = BoletoTipoEmissaoEnum::CEDENTE_EMITE;
        $this->assertEquals($this->dadosValidos, $this->boleto->toArray());
    }

    public function testValidandoPercentualMulta()
    {
        // Atribuindo multa de 2.15% ao boleto
        $this->boleto->setPercentualMulta(2.15);

        $this->dadosValidos['informacoes_opcionais']['perc_multa_atraso'] = 215000;
        $this->dadosValidos['informacoes_opcionais']['valor_multa_atraso'] = 431;
        $this->dadosValidos['informacoes_opcionais']['qtde_dias_multa_atraso'] = 1;
        $this->assertEquals($this->dadosValidos, $this->boleto->toArray());

        $this->boleto->setMultaAPartirDeXDias(7);
        $this->dadosValidos['informacoes_opcionais']['qtde_dias_multa_atraso'] = 7;
        $this->assertEquals($this->dadosValidos, $this->boleto->toArray());
    }

    public function testValidandoPercentualJuros()
    {
        // Atribuindo Juros de 1% ao boleto
        $this->boleto->setPercentualJuros(1);

        $this->dadosValidos['informacoes_opcionais']['perc_juros'] = 100000;
        $this->dadosValidos['informacoes_opcionais']['valor_juros'] = 200;
        $this->dadosValidos['informacoes_opcionais']['qtde_dias_juros'] = 1;
        $this->assertEquals($this->dadosValidos, $this->boleto->toArray());

        $this->boleto->setJurosAPartirDeXDias(5);
        $this->dadosValidos['informacoes_opcionais']['qtde_dias_juros'] = 5;
        $this->assertEquals($this->dadosValidos, $this->boleto->toArray());
    }

    private function mockPessoa()
    {
        $pessoa = $this->createMock(EntidadeInterface::class);

        $pessoa->method('toArray')
             ->willReturn([
                'nome' => 'Uma Pessoa',
                'documento' => '1234567890',
                'tipo_documento' => 2,
                'endereco' => [
                    'cep' => '12345678',
                    'logradouro' => 'Um Logradouro',
                    'numero' => '123',
                    'complemento' => 'Um Complemento',
                    'bairro' => 'Um Bairro',
                    'cidade' => 'Uma Cidade',
                    'uf' => 'DF'
                ]
            ]);
        
            $pessoa->method('consistente')
                ->willReturn(true);

            $pessoa->method('getInconsistencias')
                ->willReturn([]);
            
            return $pessoa;
    }

    private function makeBoletoInconsistente()
    {
        $date = (new DateTime())
                ->sub(new DateInterval('P10D'))
                ->format('Y-m-d');
        
        $boleto = new Boleto($this->mockPessoa(), -150.40, $date, -1234);
        return $boleto->setCarteira(-2323);
    }
}
?>