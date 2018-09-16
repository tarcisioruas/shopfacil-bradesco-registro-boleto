<?php
namespace ShopFacil\Registro;

class Pessoa extends EntidadeAbstract
{
    private $nome;
    private $cpf_cnpj;
    private $tipo_documento;
    private $endereco_cep;
    private $endereco_logradouro;
    private $endereco_numero;
    private $endereco_complemento;
    private $endereco_bairro;
    private $endereco_cidade;
    private $endereco_uf;     

    const FISICA = 1;
    const JURIDICA = 2;
    

    /**
     * Intancia a classe Pessoa
     * @param String $nome - Nome da pessoa
     * @param String $cpf - CPF da pessoa
     * @param int $tipo_documento - Tipo de Documento, 1 para CPF ou 2 para CNPJ
     */
    function __construct( $nome , $cpf_cnpj , $tipo_documento = self::FISICA )
    {
        $this->nome = $nome;
        $this->cpf_cnpj = $cpf_cnpj;
        $this->tipo_documento = $tipo_documento;
    }

    /**
     * Configura o cep do endereço
     * @param String $cep CEP do endereço. Ex: 08773000
     */
    public function setEnderecoCEP( $cep )
    {
        $this->endereco_cep = $cep;
        return $this;
    }

    /**
     * Configura o logradouro do endereço
     * @param String $logradouro - Logradouro do endereço
     * @return Pessoa
     */
    public function setEnderecoLogradouro( $logradouro )
    {
        $this->endereco_logradouro = $logradouro;
        return $this;
    }

    /**
     * Configura número do endereço
     * @param String $numero - Número do endereço
     */
    public function setEnderecoNumero( $numero )
    {
        $this->endereco_numero = $numero;
        return $this;
    }

    /**
     * Configura dados de complemento do endereço
     * @param String $complemento - Complemento do endereço
     */
    public function setEnderecoComplemento( $complemento )
    {
        $this->endereco_complemento = $complemento;
        return $this;
    }

    /**
     * Configura dados de bairro do endereço
     * @param String $bairro - Bairro do endereço
     */
    public function setEnderecoBairro( $bairro )
    {
        $this->endereco_bairro = $bairro;
        return $this;
    }

    /**
     * Configura dados de cidade do endereço
     * @param String $cidade - Cidade do endereço
     */
    public function setEnderecoCidade( $cidade )
    {
        $this->endereco_cidade = $cidade;
        return $this;
    }

    /**
     * Configura dados de uf do endereço
     * @param String $uf - UF do endereço
     */
    public function setEnderecoUF( $uf )
    {
        $this->endereco_uf = $uf;
        return $this;
    }

    /**
     * Verifica se todos os dados obrigatórios foram informados
     * @return boolean true para dados ok e false para dados inconsistentes
     * @throws Exception
     */
    protected function verificaConsistencia()
    {
        if( empty( $this->endereco_cep ) || strlen( $this->endereco_cep ) < 8 )
            $this->addInconsistencia( 'endereco_cep', 'CEP vazio ou inconsistente - Essa informação é obrigatória');
            
        if( empty( $this->endereco_logradouro ) )
            $this->addInconsistencia('endereco_logradouro' , 'Logradouro do endereço vazio - Essa informação é obrigatória');

        if( empty( $this->endereco_numero ) )
            $this->addInconsistencia('endereco_numero' , 'Número do endereço vazio - Essa informação é obrigatória');

        if( empty( $this->endereco_bairro ) )
            $this->addInconsistencia('endereco_bairro' , 'Bairro do endereço vazio - Essa informação é obrigatória');

        if( empty( $this->endereco_cidade ) )
            $this->addInconsistencia('endereco_cidade' , 'Cidade do endereço vazia - Essa informação é obrigatória');

        if( empty( $this->endereco_uf ) || strlen( $this->endereco_uf ) != 2 )
            $this->addInconsistencia('endereco_uf' , 'UF do endereço vazio ou inconsistente - Essa informação é obrigatória');
    }

    public function toArray()
    {
    
        if( $this->consistente() )
        {
            return [
                'nome' => $this->nome,
                'documento' => $this->cpf_cnpj,
                'tipo_documento' => $this->tipo_documento,
                'endereco' => [
                    'cep' => $this->endereco_cep,
                    'logradouro' => $this->endereco_logradouro,
                    'numero' => $this->endereco_numero,
                    'bairro' => $this->endereco_bairro,
                    'cidade' => $this->endereco_cidade,
                    'uf' => $this->endereco_uf
                ]
            ];
        }
        else
            throw new EntidadeException("Há inconsistencias nos dados, use o método getInconsistencias() para verificar");
        
        return [];
    }
}