<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../src/config/Database.php';
require_once __DIR__ . '/../../src/controllers/ColetaController.php';

$controller = new ColetaController(Database::conectar());

$indicadores = $controller->indicadores();
$coletas = $controller->listar();

function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel Administrativo - Ecoleta</title>

    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="container">

    <section class="conteudo">

        <div class="cabecalho-secao">

            <div>
                <span class="sobretitulo">
                    PAINEL ADMINISTRATIVO
                </span>

                <h2>
                    Visão geral do sistema
                </h2>
            </div>

            <span class="contador">
                <?= count($coletas) ?> coleta(s)
            </span>

        </div>


        <div class="painel-morador">

            <div class="resumo-morador">

                <h3>
                    Administrador
                </h3>

                <p>
                    Acompanhe os principais indicadores e todas as coletas do sistema.
                </p>

            </div>


            <div class="cards-indicadores">

                <div class="card-indicador">
                    <span>Total de coletas</span>
                    <strong>
                        <?= $indicadores['total'] ?>
                    </strong>
                </div>

                <div class="card-indicador">
                    <span>Aguardando atendimento</span>
                    <strong>
                        <?= $indicadores['solicitadas'] ?>
                    </strong>
                </div>

                <div class="card-indicador">
                    <span>Coletas concluídas</span>
                    <strong>
                        <?= $indicadores['concluidas'] ?>
                    </strong>
                </div>

                <div class="card-indicador">
                    <span>Materiais reciclados</span>
                    <strong>
                        <?= number_format(
                            $indicadores['kg'],
                            2,
                            ',',
                            '.'
                        ) ?> kg
                    </strong>
                </div>

                <div class="card-indicador">
                    <span>CO₂ estimado evitado</span>
                    <strong><?= number_format($indicadores['co2'], 2, ',', '.') ?> kg</strong>
                </div>

            </div>


            <div class="tabela-admin">

                <h3>
                    Todas as coletas
                </h3>


                <?php if (!$coletas): ?>

                    <div class="vazio">
                        Nenhuma coleta cadastrada.
                    </div>

                <?php else: ?>

                    <div class="tabela-responsiva">

                        <table>

                            <thead>

                                <tr>
                                    <th>#</th>
                                    <th>Morador</th>
                                    <th>Catador</th>
                                    <th>Data</th>
                                    <th>Endereço</th>
                                    <th>Kg</th>
                                    <th>Status</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($coletas as $coleta): ?>

                                    <tr>

                                        <td>
                                            #<?= (int)$coleta['id'] ?>
                                        </td>

                                        <td>
                                            <?= e($coleta['morador_nome']) ?>
                                        </td>

                                        <td>
                                            <?= e(
                                                $coleta['catador_nome']
                                                ?? 'Não atribuído'
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= date(
                                                'd/m/Y',
                                                strtotime($coleta['data_agendada'])
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= e($coleta['endereco_coleta']) ?>
                                        </td>

                                        <td>
                                            <?= number_format(
                                                (float)$coleta['total_kg'],
                                                2,
                                                ',',
                                                '.'
                                            ) ?> kg
                                        </td>

                                        <td>

                                            <span class="status status-<?= e($coleta['status']) ?>">

                                                <?= e(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        ucfirst($coleta['status'])
                                                    )
                                                ) ?>

                                            </span>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </section>


    <div style="margin-top: 25px;">

        <a href="../index.php" class="btn-acao">
            ← Voltar para início
        </a>

    </div>

</div>

</body>
</html>