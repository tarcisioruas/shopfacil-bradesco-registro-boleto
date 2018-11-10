<?php
use PHPUnit\Framework\TestCase;
use ShopFacil\Registro\Interfaces\EntidadeInterface;
use ShopFacil\Registro\Interfaces\HttpInterface;
use ShopFacil\Registro\Registro;
use ShopFacil\Registro\Boleto;
use ShopFacil\Registro\RetornoRegistro;


class RegistroTest extends TestCase
{
    private $registro;
    
    public function setUp()
    {
        $this->registro = new Registro(Registro::HOMOLOGACAO, 'merchantId', 'senha', $this->mockHttp());
        $this->boleto = $this->mockBoleto();
    }

    public function testVerificandoRegistro()
    {
        $resposta = $this->registro->registrar($this->boleto);
        $this->assertTrue($resposta INSTANCEOF RetornoRegistro);
    }

    /**
     * @expectedException ShopFacil\Registro\Exceptions\RegistroException
     * @expectedExceptionMessage MerchantId não infomado ao objeto "Registro" ou via váriaveis de ambiente
     */
    public function testValidandoExceptionSemMerchantId()
    {
        new Registro($ambiente = Registro::HOMOLOGACAO, null, 'senha', $this->mockHttp());
    }

    /**
     * @expectedException ShopFacil\Registro\Exceptions\RegistroException
     * @expectedExceptionMessage Senha não infomada ao instanciar "Registro" ou via váriaveis de ambiente
     */
    public function testValidandoExceptionSemSenha()
    {
        new Registro($ambiente = Registro::HOMOLOGACAO, '123453', null, $this->mockHttp());
    }

    public function testValidandoEntradaViaVariaveisDeAmbiente()
    {
        putenv('SHOPFACIL_MERCHANT_ID=merchant');
        putenv('SHOPFACIL_SENHA=senha');
        $registro = new Registro($ambiente = Registro::HOMOLOGACAO, null, null, $this->mockHttp());
        $this->assertTrue(true);
    }

    public function testValidandoMerchantIdViaVariaveisDeAmbiente()
    {
        putenv('SHOPFACIL_MERCHANT_ID=merchant');
        $registro = new Registro($ambiente = Registro::HOMOLOGACAO, null, 'senhaaqui', $this->mockHttp());
        $this->assertTrue(true);
    }

    public function testValidandoSenhaViaVariaveisDeAmbiente()
    {
        putenv('SHOPFACIL_SENHA=senha');
        $registro = new Registro($ambiente = Registro::HOMOLOGACAO, 'merchantId', null, $this->mockHttp());
        $this->assertTrue(true);
    }
        

    private function mockBoleto()
    {
        $boleto = $this->createMock(EntidadeInterface::class);

        $vencimento = (new DateTime())
                ->add(new DateInterval('P10D'))
                ->format('Y-m-d');

        $boleto->method('toArray')
             ->willReturn([
                'carteira' => 26,
                'nosso_numero' => 1234,
                'numero_documento' => 1234,
                'data_emissao' => date('Y-m-d'),
                'data_vencimento' => $vencimento,
                'valor_titulo' => '20045',
                'pagador' => [
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
                        'uf' => 'DF',
                    ]
                ],
                'informacoes_opcionais' => [
                    'controle_participante' => 1234,
                    'especie' => 'DV',
                    'aceite' => 'N',
                    'tipo_emissao_papeleta' => 2
                  
                ]
            ]);
        
            $boleto->method('consistente')
                ->willReturn(true);

            $boleto->method('getInconsistencias')
                ->willReturn([]);
            
            return $boleto;
    }

    private function mockHttp()
    {
        $http = $this->createMock(HttpInterface::class);

        $http->method('post')
             ->willReturn(json_encode([
                 'status' => [
                     'codigo' => 999,
                     'mensagem' => 'Essa é uma resposta mockada, o retorno não é real'
                 ]
             ]));

        $http->method('getInfo')
             ->willReturn([
                'http_code' => 200
             ]);
        
        return $http;
    }
}