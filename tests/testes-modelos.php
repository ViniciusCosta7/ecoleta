<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/models/MaterialReciclavel.php';
require_once __DIR__ . '/../src/models/Coleta.php';
require_once __DIR__ . '/../src/models/Morador.php';
require_once __DIR__ . '/../src/models/Catador.php';
require_once __DIR__ . '/../src/models/Administrador.php';

function verificar(bool $condicao, string $mensagem): void
{
    if (!$condicao) {
        throw new RuntimeException("FALHOU: {$mensagem}");
    }
    echo "OK: {$mensagem}" . PHP_EOL;
}

$papel = new MaterialReciclavel('papel', 10.0);
verificar(abs($papel->calcularCo2Evitado() - 9.0) < 0.0001, 'cálculo estimado de CO2 para papel');

$coleta = new Coleta(null, 1, null, null, 'Rua de Teste, 100', date('Y-m-d'), 'solicitada');
$coleta->adicionarMaterial($papel);
$coleta->validar();
verificar(abs($coleta->calcularTotalKg() - 10.0) < 0.0001, 'soma da quantidade de materiais');
verificar(abs($coleta->calcularCo2Evitado() - 9.0) < 0.0001, 'cálculo de CO2 da coleta');

$rejeitouColetaVazia = false;
try {
    $vazia = new Coleta(null, 1, null, null, 'Rua de Teste, 100', date('Y-m-d'), 'solicitada');
    $vazia->validar();
} catch (InvalidArgumentException) {
    $rejeitouColetaVazia = true;
}
verificar($rejeitouColetaVazia, 'regra de negócio impede coleta sem material');

$usuarios = [
    new Morador(1, 'Morador Teste', 'morador@teste.com'),
    new Catador(2, 'Catador Teste', 'catador@teste.com'),
    new Administrador(3, 'Admin Teste', 'admin@teste.com'),
];
$paineis = array_map(static fn(Usuario $usuario): string => $usuario->verPainel(), $usuarios);
verificar(count(array_unique($paineis)) === 3, 'polimorfismo: cada perfil apresenta seu próprio painel');

echo PHP_EOL . 'Todos os testes de modelo passaram.' . PHP_EOL;
