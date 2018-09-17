<?php
namespace ShopFacil\Registro;

class Pessoa extends EntidadeAbstract
{
    private $nome;
    private $cpfCnpj;
    private $tipoDocumento;
    private $enderecoCep;
    private $enderecoLogradouro;
    private $enderecoNumero;
    private $enderecoComplemento;
    private $enderecoBairro;
    private $enderecoCidade;
    private $enderecoUF;     

    const FISICA = 1;
    const JURIDICA = 2;
    

    /**
     * Intancia a classe Pessoa
     * @param String $nome - Nome da pessoa
     * @param String $cpf - CPF da pessoa
     * @param int $tipoDocumento - Tipo de Documento, 1 para CPF ou 2 para CNPJ
     */
    function __construct($nome, $cpfCnpj, $tipoDocumento = self::FISICA)
    {
        $this->nome = $nome;
        $this->cpfCnpj = $cpfCnpj;
        $this->tipoDocumento = $tipoDocumento;
    }

    /**
     * Configura o cep do endereço
     * @param String $cep CEP do endereço. Ex: 08773000
     */
    public function setEnderecoCEP($cep)
    {
        $this->enderecoCep = $cep;
        return $this;
    }

    /**
     * Configura o logradouro do endereço
     * @param String $logradouro - Logradouro do endereço
     * @return Pessoa
     */
    public function setEnderecoLogradouro($logradouro)
    {
        $this->enderecoLogradouro = $logradouro;
        return $this;
    }

    /**
     * Configura número do endereço
     * @param String $numero - Número do endereço
     */
    public function setEnderecoNumero($numero)
    {
        $this->enderecoNumero = $numero;
        return $this;
    }

    /**
     * Configura dados de complemento do endereço
     * @param String $complemento - Complemento do endereço
     */
    public function setEnderecoComplemento($complemento)
    {
        $this->enderecoComplemento = $complemento;
        return $this;
    }

    /**
     * Configura dados de bairro do endereço
     * @param String $bairro - Bairro do endereço
     */
    public function setEnderecoBairro($bairro)
    {
        $this->enderecoBairro = $bairro;
        return $this;
    }

    /**
     * Configura dados de cidade do endereço
     * @param String $cidade - Cidade do endereço
     */
    public function setEnderecoCidade($cidade)
    {
        $this->enderecoCidade = $cidade;
        return $this;
    }

    /**
     * Configura dados de uf do endereço
     * @param String $uf - UF do endereço
     */
    public function setEnderecoUF($uf)
    {
        $this->enderecoUF = $uf;
        return $this;
    }

    /**
     * Verifica se todos os dados obrigatórios foram informados
     * @return boolean true para dados ok e false para dados inconsistentes
     * @throws Exception
     */
    protected function verificaConsistencia()
    {
        if (empty($this->enderecoCep) || strlen($this->enderecoCep) < 8) {
            $this->addInconsistencia( 'enderecoCep', 'CEP vazio ou inconsistente - Essa informação é obrigatória');
        }
            
        if (empty($this->enderecoLogradouro)) {
            $this->addInconsistencia('enderecoLogradouro' , 'Logradouro do endereço vazio - Essa informação é obrigatória');
        }
            

        if (empty($this->enderecoNumero)) {
            $this->addInconsistencia('enderecoNumero' , 'Número do endereço vazio - Essa informação é obrigatória');
        }
            

        if (empty($this->enderecoBairro)) {
            $this->addInconsistencia('enderecoBairro' , 'Bairro do endereço vazio - Essa informação é obrigatória');
        }
            

        if (empty($this->enderecoCidade)) {
            $this->addInconsistencia('enderecoCidade' , 'Cidade do endereço vazia - Essa informação é obrigatória');
        }
            

        if (empty($this->enderecoUF) || strlen($this->enderecoUF) != 2) {
            $this->addInconsistencia('enderecoUF' , 'UF do endereço vazio ou inconsistente - Essa informação é obrigatória');
        }
    }

    public function toArray()
    {
        if(!$this->consistente()) {
            throw new EntidadeException("Há inconsistencias nos dados, use o método getInconsistencias() para verificar");
        }
        
        return [
            'nome' => $this->nome,
            'documento' => $this->cpfCnpj,
            'tipo_documento' => $this->tipoDocumento,
            'endereco' => [
                'cep' => $this->enderecoCep,
                'logradouro' => $this->enderecoLogradouro,
                'numero' => $this->enderecoNumero,
                'complemento' => $this->enderecoComplemento,
                'bairro' => $this->enderecoBairro,
                'cidade' => $this->enderecoCidade,
                'uf' => $this->enderecoUF
            ]
        ];
    }
}