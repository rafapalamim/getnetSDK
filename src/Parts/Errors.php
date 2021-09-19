<?php

namespace GetNet\Helpers;

class Errors
{
    const ERRORS_CARD = [
        '40x' => [
            'PAYMENTS-012' => 'Valor da entrada maior ou igual ao valor da transação',
            'PAYMENTS-013' => 'Valor da parcela inválido',
            'PAYMENTS-015' => 'Contatar emissor',
            'PAYMENTS-016' => 'NSU inválido',
            'PAYMENTS-019' => 'Data de emissão do cartão inválida',
            'PAYMENTS-020' => 'Data de vencimento inválida',
            'PAYMENTS-024' => 'Transação desfeita',
            'PAYMENTS-025' => 'Autenticação inválida',
            'PAYMENTS-026' => 'Autorização inválida',
            'PAYMENTS-029' => 'Pré-autorização inválida',
            'PAYMENTS-044' => 'Erro de formato',
            'PAYMENTS-050' => 'Entrar em contato com a instituição',
            'PAYMENTS-051' => 'Resposta parametrizada negativa',
            'PAYMENTS-054' => 'Pendente de confirmação',
            'PAYMENTS-055' => 'Transação cancelada',
            'PAYMENTS-056' => 'Transação não permitida neste ciclo',
            'PAYMENTS-058' => 'Transação estornada',
            'PAYMENTS-060' => 'Cartão obrigatório na transação',
            'PAYMENTS-061' => 'Rejeição genérica',
            'PAYMENTS-066' => 'Forma de pagamento inválido',
            'PAYMENTS-068' => 'Dígito cartão inválido',
            'PAYMENTS-069' => 'Transação repetida',
            'PAYMENTS-070' => 'Número do cartão não confere',
            'PAYMENTS-072' => 'Transação não cancelável',
            'PAYMENTS-073' => 'Transação já cancelada',
            'PAYMENTS-078' => 'Dados inválidos no cancelamento',
            'PAYMENTS-079' => 'Valor cancelamento inválido',
            'PAYMENTS-080' => 'Cartão inválido',
            'PAYMENTS-081' => 'Excede data',
            'PAYMENTS-082' => 'Cancelamento inválido',
            'PAYMENTS-083' => 'Use função débito',
            'PAYMENTS-084' => 'Use função crédito',
            'PAYMENTS-085' => 'Transação já efetuada',
            'PAYMENTS-090' => 'Transação não autorizada pelo cartão',
            'PAYMENTS-091' => 'Fora do prazo permitido',
            'PAYMENTS-093' => 'Autorização já encontra-se em processamento',
            'PAYMENTS-094' => 'Autorização a confirmar o recebimento',
            'PAYMENTS-098' => 'Cliente não cadastrado',
            'PAYMENTS-117' => 'Solicite ao portator ligar para o emissor',
            'PAYMENTS-118' => 'Cartao invalido ou produto não habilitado',
            'PAYMENTS-999' => 'Transacao nao processada',
            'PAYMENTS-043' => 'Registro não encontrado',
            'PAYMENTS-057' => 'Transação não existe',
            'PAYMENTS-076' => 'Transação não disponível',
            'PAYMENTS-095' => 'Autorização não encontrada'
        ],
        '50x' => [
            'PAYMENTS-042' => 'Resposta inválida',
            'PAYMENTS-059' => 'Problema rede local',
            'PAYMENTS-062' => 'Instituição temporariamente fora de operação',
            'PAYMENTS-063' => 'Mal funcionamento do sistema',
            'PAYMENTS-064' => 'Erro banco de dados',
            'PAYMENTS-071' => 'Autorizadora temporariamente bloqueada',
            'PAYMENTS-086' => 'Erro na transação',
            'PAYMENTS-099' => 'Autorizadora não inicializada',
            'PAYMENTS-100' => 'Canal desconectado',
            'PAYMENTS-107' => 'Erro de comunicação',
            'PAYMENTS-500' => 'Internal Server Error',
            'PAYMENTS-021' => 'Sistema do Emissor indisponível - Tente novamente',
            'PAYMENTS-031' => 'Timeout',
            'PAYMENTS-089' => 'Timeout interno'
        ],

    ];
}
