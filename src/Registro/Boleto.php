<?php
namespace ShopFacil\Registro;

class Boleto extends EntidadeAbstract
{
    private $carteira = 26;
    private $nossoNumero;
    private $numeroDocumento;
    private $controleParticipante;
    private $dataEmissao;
    private $dataVencimento;
    private $valorTitulo;
    private $pagador;
    private $aceite = BoletoAceiteEnum::NAO;
    private $especie = BoletoEspecieEnum::DIVERSOS;
    private $tipoEmissao = BoletoTipoEmissaoEnum::CEDENTE_EMITE;
    private $percentualMulta;
    private $percentualJuros;
    private $valorDesconto;
    private $dataDescontoAte;
    

    /**
     * Intancia a classe Boleto
     * @param Pessoa $pagador - Objeto contendo dados do cliente
     * @param float $valorTitulo - Valor do titulo a ser registrado
     * @param String $dataVencimento - String da data de vencimento no formato YYYY-MM-DD
     * @param int $nossoNumero - Identificador a ser usado para geração do boleto
     * @return Boleto
     */
    function __construct(Pessoa $pagador, $valorTitulo, $dataVencimento, $nossoNumero)
    {
        $this->pagador = $pagador;
        $this->valorTitulo = $valorTitulo;
        $this->dataVencimento = $dataVencimento;
        $this->nossoNumero = $nossoNumero;
        $this->dataEmissao = date('Y-m-d');
        $this->numeroDocumento = $nossoNumero;
        $this->controleParticipante = $nossoNumero;
    }

    /**
     * Configura o numero do documento. Se não for informado,
     * o Nosso Numero será enviado no lugar dele
     * @param int $numeroDocumento - Numero do documento
     * @return Boleto
     */
    public function setNumeroDocumento($numeroDocumento)
    {
        $this->numeroDocumento = $numeroDocumento;
        return $this;
    }

    /**
     * Configura o controle do participante, geralmente esse número é usado
     * para identificar o boleto no arquivo CNAB de retorno. Se não for informado,
     * será enviado o nosso numero
     * @param int $controleParticipante - Controle de Participante
     * @return Boleto
     */
    public function setControleParticipante($controleParticipante)
    {
        $this->controleParticipante = $controleParticipante;
        return $this;
    }

    /**
    * Configura o numero da carteira a ser usada.
    * Se não for informada, o padrão é 26
    * @param int $carteira - Numero da carteira para ser usada no registro do boleto
    * @return Boleto
    */
    public function setCarteira($carteira)
    {
        $this->carteira = $carteira;
        return $this;
    }

    /**
    * Configura a especie do boleto
    * Se não for informada, o padrão é Diversos ou DV
    * @param int $especie
    * @return Boleto
    */
    public function setEspecie($especie)
    {
        $this->especie = $especie;
        return $this;
    }

    /**
    * Configura a especie do boleto
    * Se não for informada, o padrão é Diversos ou DV
    * @param int $especie
    * @return Boleto
    */
    public function setTipoEmissao($tipoEmissao)
    {
        $this->tipoEmissao = $tipoEmissao;
        return $this;
    }

    public function setPercentualMulta($percentualMulta)
    {
        $this->percentualMulta = $percentualMulta;
        return $this;
    }

    public function setPercentualJuros($percentualJuros)
    {
        $this->percentualJuros = $percentualJuros;
        return $this;
    }

    public function setValorDesconto($valorDesconto) 
    {
        $this->valorDesconto = $valorDesconto;

        if (empty($this->dataDescontoAte)) {
            $this->dataDescontoAte = $this->dataVencimento;
        }
    }

    /**
     * Verifica se todos os dados obrigatórios foram informados
     * @return boolean true para dados ok e false para dados inconsistentes
     * @throws Exception
     */
    protected function verificaConsistencia()
    {
        if (empty($this->nossoNumero) || (int)$this->nossoNumero < 1) {
            $this->addInconsistencia('nossoNumero', 'Nosso número vazio ou inconsistente - Essa informação é obrigatória e deve conter valor inteiro maior que 0');
        }

        if (empty($this->numeroDocumento) || (int)$this->numeroDocumento < 1) {
            $this->addInconsistencia('numeroDocumento', 'Número do Documento vazio ou inconsistente - Essa informação é obrigatória e deve conter valor inteiro maior que 0');
        }

        if (empty($this->dataVencimento) || str_replace('-', '', $this->dataVencimento) < date('Ymd')) {
            $this->addInconsistencia('dataVencimento', 'Data de vencimento vazia ou inconsistente - Essa informação é obrigatória e deve ser maior ou igual a data atual');
        }

        if (empty($this->valorTitulo) || (float)$this->valorTitulo < 0) {
            $this->addInconsistencia('valorTitulo', 'Valor vazio ou inconsistente - Essa informação é obrigatória e deve ter valor maior do que 0');
        }

        if (empty($this->carteira) || (int)$this->carteira < 1) {
            $this->addInconsistencia('carteira', 'Carterira vazia ou inconsistente - Essa informação é obrigatória e deve ter valor inteiro maior do que 0');
        }

        if (!empty($this->percentualMulta) && (!is_numeric($this->percentualMulta) || $this->percentualMulta < 0)) {
            $this->addInconsistencia('percentualMulta', 'Percentual de Multa deve ser um valor numérico válido e maior que 0');
        }

        if (!empty($this->percentualJuros) && (!is_numeric($this->percentualJuros)  || $this->percentualJuros < 0)) {
            $this->addInconsistencia('percentualJuros', 'Percentual de Juros deve ser um valor numérico válido e maior que 0');
        }
        
        if (!empty($this->valorDesconto)) {
            if (!is_numeric($this->valorDesconto) || $this->valorDesconto < 0) {
                $this->addInconsistencia('valorDesconto', 'Valor de Desconto deve ser um valor numérico válido e maior que 0'); 
            }
            
            try {
                $dataDescontoAte = DateTime::createFromFormat('Y-m-d', $this->dataDescontoAte);
            } catch (\Exception $e) {
                $this->addInconsistencia('dataDescontoAte', 'A data limite de desconto não corresponde ao formato válido "Y-m-d"'); 
            }
        }
    }

    /**
     * Transforma dados encapsulados em um array formatado para ser enviado 
     * @return array
     */
    public function toArray()
    {
        $boletoArray =  [
            'carteira' => $this->carteira, // Carteira usada pelo realtime
            'nosso_numero' => $this->nossoNumero,
            'numero_documento' => $this->numeroDocumento,
            'data_emissao' => $this->dataEmissao,
            'data_vencimento' => substr($this->dataVencimento, 0 ,10),
            'valor_titulo' => str_replace(['.'], '' , number_format($this->valorTitulo, 2 )),
            'pagador' => $this->pagador->toArray(),
            'informacoes_opcionais' => [
                'controle_participante' => $this->controleParticipante,
                'especie' => $this->especie,
                'aceite' => $this->aceite,
                'tipo_emissao_papeleta' => $this->tipoEmissao,
            ]
        ];

        return $boletoArray;
        
    }
}