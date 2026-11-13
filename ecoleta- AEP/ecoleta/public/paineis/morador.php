<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../src/config/Database.php';
require_once __DIR__ . '/../../src/controllers/ColetaController.php';

$controller = new ColetaController(Database::conectar());

$idMorador = $controller->obterIdUsuarioPorEmail('morador@ecoleta.com');
$coletasMorador = $controller->listarPorMorador($idMorador);
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
    <title>Painel do Morador - Ecoleta</title>

    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="container">

    <h1>👤 Painel do Morador</h1>

    <p>Acompanhe suas coletas e o andamento das solicitações.</p>

    <a href="../index.php" class="btn-acao">
        ← Voltar para início
    </a>

    <div class="painel-morador">

        <h2>Minhas coletas</h2>

        <?php if (!$coletasMorador): ?>

            <div class="vazio">
                Você ainda não possui coletas cadastradas.
            </div>

        <?php else: ?>

            <div class="tabela-responsiva">

                <table>

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Data</th>
                            <th>Endereço</th>
                            <th>Materiais</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($coletasMorador as $coleta): ?>

                            <tr>

                                <td>
                                    #<?= (int)$coleta['id'] ?>
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
                                    ) ?>
                                    kg
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

</body>
</html>