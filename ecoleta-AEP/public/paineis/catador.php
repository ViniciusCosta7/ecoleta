<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../src/config/Database.php';
require_once __DIR__ . '/../../src/controllers/ColetaController.php';

$controller = new ColetaController(Database::conectar());

$idCatador = $controller->obterIdUsuarioPorEmail('catador@ecoleta.com');
$coletasCatador = $controller->listarDisponiveisParaCatador();
$minhasColetasCatador = $controller->listarPorCatador($idCatador);

function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao = $_POST['acao'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($acao === 'aceitar' && $id > 0) {
        $controller->aceitar($id, $idCatador);

        header('Location: catador.php');
        exit;
    }

    if ($acao === 'iniciar_rota' && $id > 0) {
        $controller->iniciarRota($id, $idCatador);
        header('Location: catador.php');
        exit;
    }

    if ($acao === 'concluir' && $id > 0) {
        $controller->concluir($id, $idCatador);

        header('Location: catador.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel do Catador - Ecoleta</title>

    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="container">

    <section class="conteudo">

        <div class="cabecalho-secao">

            <div>
                <span class="sobretitulo">
                    PAINEL DO CATADOR
                </span>

                <h2>
                    Coletas disponíveis
                </h2>
            </div>

            <span class="contador">
                <?= count($coletasCatador) ?> coleta(s)
            </span>

        </div>

        <div class="painel-morador">

            <div class="resumo-morador">

                <h3>
                    Catador Demonstração
                </h3>

                <p>
                    Confira as coletas que estão aguardando atendimento.
                </p>

            </div>

            <?php if (!$coletasCatador): ?>

                <div class="vazio">
                    Não existem coletas disponíveis no momento.
                </div>

            <?php else: ?>

                <div class="tabela-responsiva">

                    <table>

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Morador</th>
                                <th>Data</th>
                                <th>Endereço</th>
                                <th>Materiais</th>
                                <th>Status</th>
                                <th>Ação</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($coletasCatador as $coleta): ?>

                                <tr>

                                    <td>
                                        #<?= (int)$coleta['id'] ?>
                                    </td>

                                    <td>
                                        <?= e($coleta['morador_nome']) ?>
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

                                    <td>

                                        <form method="post">

                                            <input
                                                type="hidden"
                                                name="acao"
                                                value="aceitar"
                                            >

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int)$coleta['id'] ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn-acao"
                                            >
                                                Aceitar coleta
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <section class="conteudo">

        <div class="cabecalho-secao">

            <div>

                <span class="sobretitulo">
                    MINHAS COLETAS
                </span>

                <h2>
                    Coletas aceitas
                </h2>

            </div>

            <span class="contador">
                <?= count($minhasColetasCatador) ?> coleta(s)
            </span>

        </div>


        <div class="painel-morador">

            <?php if (!$minhasColetasCatador): ?>

                <div class="vazio">
                    Você ainda não aceitou nenhuma coleta.
                </div>

            <?php else: ?>

                <div class="tabela-responsiva">

                    <table>

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Morador</th>
                                <th>Data</th>
                                <th>Endereço</th>
                                <th>Materiais</th>
                                <th>Status</th>
                                <th>Ação</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($minhasColetasCatador as $coleta): ?>

                                <tr>

                                    <td>
                                        #<?= (int)$coleta['id'] ?>
                                    </td>

                                    <td>
                                        <?= e($coleta['morador_nome']) ?>
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

                                    <td>

                                        <?php if ($coleta['status'] === 'aceita'): ?>

                                            <form method="post">
                                                <input type="hidden" name="acao" value="iniciar_rota">
                                                <input type="hidden" name="id" value="<?= (int)$coleta['id'] ?>">
                                                <button type="submit" class="btn-acao">Iniciar rota</button>
                                            </form>

                                        <?php elseif ($coleta['status'] === 'em_rota'): ?>

                                            <form method="post">
                                                <input type="hidden" name="acao" value="concluir">
                                                <input type="hidden" name="id" value="<?= (int)$coleta['id'] ?>">
                                                <button type="submit" class="btn-acao">Concluir coleta</button>
                                            </form>

                                        <?php else: ?>
                                            —
                                        <?php endif; ?>



                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

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