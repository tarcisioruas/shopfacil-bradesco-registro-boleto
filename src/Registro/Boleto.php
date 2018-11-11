<?php
namespace ShopFacil\Registro;

use ShopFacil\Registro\Interfaces\EntidadeInterface;

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
    private $multaAPartirDeXDias = 1;
    private $jurosAPartirDeXDias = 1;

    const NOSSO_NUMERO_FIELD = 'nossoNumero';
    const NUMERO_DOCUMENTO_FIELD = 'numeroDocumento';
    const DATA_VENCIMENTO_FIELD = 'dataVencimento';
    const VALOR_TITULO_FIELD = 'valorTitulo';
    const CARTEIRA_FIELD = 'carteira';
    const PERCENTUAL_MULTA_FIELD = 'percentualMulta';
    const PERCENTUAL_JUROS_FIELD = 'percentualJuros';
    const VALOR_DESCONTO_FIELD = 'valorDesconto';
    const DATA_DESCONTO_ATE_FIELD = 'dataDescontoAte';

    private $arrayConsistencia = [
        Boleto::NOSSO_NUMERO_FIELD => false,
        Boleto::NUMERO_DOCUMENTO_FIELD => false,
        Boleto::DATA_VENCIMENTO_FIELD => false,
        Boleto::VALOR_TITULO_FIELD => false,
        Boleto::CARTEIRA_FIELD => true,
        Boleto::PERCENTUAL_MULTA_FIELD => true,
        Boleto::PERCENTUAL_JUROS_FIELD => true,
        Boleto::VALOR_DESCONTO_FIELD => true,
        Boleto::DATA_DESCONTO_ATE_FIELD => true,
    ];

    private $errosConsistencia = [
        Boleto::NOSSO_NUMERO_FIELD => 'Nosso número vazio ou inconsistente - Essa informação é obrigatória e deve conter valor inteiro maior que 0',
        Boleto::NUMERO_DOCUMENTO_FIELD => 'Número do Documento vazio ou inconsistente - Essa informação é obrigatória e deve conter valor inteiro maior que 0',
        Boleto::DATA_VENCIMENTO_FIELD => 'Data de vencimento vazia ou inconsistente - Essa informação é obrigatória e deve ser maior ou igual a data atual',
        Boleto::VALOR_TITULO_FIELD => 'Valor vazio ou inconsistente - Essa informação é obrigatória e deve ter valor maior do que 0',
        Boleto::CARTEIRA_FIELD => 'Carterira vazia ou inconsistente - Essa informação é obrigatória e deve ter valor inteiro maior do que 0',
        Boleto::PERCENTUAL_MULTA_FIELD => 'Percentual de Multa deve ser um valor numérico válido e maior que 0',
        Boleto::PERCENTUAL_JUROS_FIELD => 'Percentual de Juros deve ser um valor numérico válido e maior que 0',
        Boleto::VALOR_DESCONTO_FIELD => 'Valor de Desconto deve ser um valor numérico válido e maior que 0',
        Boleto::DATA_DESCONTO_ATE_FIELD => 'A data limite de desconto não corresponde ao formato válido "Y-m-d"',
    ];

    /**
     * Intancia a classe Boleto
     * @param Pessoa $pagador - Objeto contendo dados do cliente
     * @param float $valorTitulo - Valor do titulo a ser registrado
     * @param String $dataVencimento - String da data de vencimento no formato YYYY-MM-DD
     * @param int $nossoNumero - Identificador a ser usado para geração do boleto
     * @return Boleto
     */
    function __construct(EntidadeInterface $pagador, $valorTitulo, $dataVencimento, $nossoNumero)
    {
        $this->pagador = $pagador;
        $this->valorTitulo = $valorTitulo;
        $this->dataVencimento = $dataVencimento;
        $this->nossoNumero = $nossoNumero;
        $this->dataEmissao = date('Y-m-d');
        $this->numeroDocumento = $nossoNumero;
        $this->controleParticipante = $nossoNumero;

        $this->arrayConsistencia[Boleto::NOSSO_NUMERO_FIELD] = !empty($this->nossoNumero) && (int)$this->nossoNumero > 0;
        $this->arrayConsistencia[Boleto::NUMERO_DOCUMENTO_FIELD] = !empty($this->numeroDocumento) && (int)$this->numeroDocumento > 0;
        $this->arrayConsistencia[Boleto::DATA_VENCIMENTO_FIELD] = !empty($this->dataVencimento) && str_replace('-', '', $this->dataVencimento) >= date('Ymd');
        $this->arrayConsistencia[Boleto::VALOR_TITULO_FIELD] = !empty($this->valorTitulo) && (int)$this->valorTitulo >= 0;
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

        $this->arrayConsistencia[Boleto::NUMERO_DOCUMENTO_FIELD] = !empty($this->numeroDocumento) && (int)$this->numeroDocumento > 0;

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
        
        $this->arrayConsistencia[Boleto::CARTEIRA_FIELD] = !empty($this->carteira) && (int)$this->carteira > 0;

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

        $this->arrayConsistencia[Boleto::PERCENTUAL_MULTA_FIELD] = !empty($this->percentualMulta) && is_numeric($this->percentualMulta) && (float)$this->percentualMulta >= 0;
        
        return $this;
    }

    public function setMultaAPartirDeXDias($qtdDias)
    {
        $this->multaAPartirDeXDias = $qtdDias;
        return $this;
    }

    public function setPercentualJuros($percentualJuros)
    {
        $this->percentualJuros = $percentualJuros;

        $this->arrayConsistencia[Boleto::PERCENTUAL_JUROS_FIELD] = !empty($this->percentualJuros) && is_numeric($this->percentualJuros) && (float)$this->percentualJuros >= 0;

        return $this;
    }

    public function setJurosAPartirDeXDias($qtdDias)
    {
        $this->jurosAPartirDeXDias = $qtdDias;
        return $this;
    }

    public function setValorDesconto($valorDesconto, $dataDescontoAte = null) 
    {
        $this->valorDesconto = $valorDesconto;
        $this->dataDescontoAte = $dataDescontoAte;

        if (empty($this->dataDescontoAte)) {
            $this->dataDescontoAte = $this->dataVencimento;
        }

        list($ano, $mes, $dia) = explode('-', $this->dataDescontoAte);
        $this->arrayConsistencia[Boleto::DATA_DESCONTO_ATE_FIELD] = checkdate((int)$mes, (int)$dia, (int)$ano);
        $this->arrayConsistencia[Boleto::VALOR_DESCONTO_FIELD] = !empty($this->valorDesconto) && is_numeric($this->valorDesconto) && (float)$this->valorDesconto >= 0;

        return $this;
    }

    /**
     * Verifica se todos os dados obrigatórios foram informados
     * @return boolean true para dados ok e false para dados inconsistentes
     * @throws Exception
     */
    protected function verificaConsistencia()
    {
        foreach ($this->arrayConsistencia as $campo => $isConsistente) {
            if (!$isConsistente) {
                $this->addInconsistencia($campo, $this->errosConsistencia[$campo]); 
            }
        }
    }

    /**
     * Transforma dados encapsulados em um array formatado para ser enviado 
     * @return array
     */
    protected function _toArray()
    {
        $boletoArray =  [
            'carteira' => $this->carteira, // Carteira usada pelo realtime
            'nosso_numero' => $this->nossoNumero,
            'numero_documento' => $this->numeroDocumento,
            'data_emissao' => $this->dataEmissao,
            'data_vencimento' => substr($this->dataVencimento, 0 ,10),
            'valor_titulo' => str_replace(['.'], '' , number_format($this->valorTitulo, 2 )),
            'pagador' => $this->pagador->toArray()
        ];

        $informacoesOpcionais = [
            'controle_participante' => $this->controleParticipante,
            'especie' => $this->especie,
            'aceite' => $this->aceite,
            'tipo_emissao_papeleta' => $this->tipoEmissao,
        ];

        if ($this->percentualMulta > 0) {
            $informacoesOpcionais['perc_multa_atraso'] = (int)number_format($this->percentualMulta, 5, '' , '');
            $informacoesOpcionais['valor_multa_atraso'] = (int)number_format($this->valorTitulo / 100 * $this->percentualMulta, 2, '' , '');
            $informacoesOpcionais['qtde_dias_multa_atraso'] = $this->multaAPartirDeXDias;
        }

        if ($this->percentualJuros > 0) {
            $informacoesOpcionais['perc_juros'] = (int)number_format($this->percentualJuros, 5, '' , '');
            $informacoesOpcionais['valor_juros'] = (int)number_format($this->valorTitulo / 100 * $this->percentualJuros, 2, '' , '');
            $informacoesOpcionais['qtde_dias_juros'] = $this->jurosAPartirDeXDias;
        }

        if (!empty($this->valorDesconto) && $this->valorDesconto > 0)
        {
            $percentualDesconto = 100 / $this->valorTitulo * $this->valorDesconto;
            $informacoesOpcionais['perc_desconto_1'] = (int)str_replace([ '.' , ',' ], '' , number_format((float)$percentualDesconto, 5));
            $informacoesOpcionais['valor_desconto_1'] = (int)str_replace([ '.' , ',' ], '' , number_format((float)$percentualDesconto, 5));
            $informacoesOpcionais['data_limite_desconto_1'] =  $this->dataDescontoAte; 
        }

        $boletoArray['informacoes_opcionais'] = $informacoesOpcionais;

        return $boletoArray;
    }
}