<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../src/controllers/ColetaController.php';
require_once __DIR__ . '/../src/models/Morador.php';
require_once __DIR__ . '/../src/models/Catador.php';
require_once __DIR__ . '/../src/models/Administrador.php';

function e(?string $valor): string
{
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

try {
    $controller = new ColetaController(Database::conectar());
    $acao = $_POST['acao'] ?? $_GET['acao'] ?? 'inicio';
    $mensagem = $_SESSION['mensagem'] ?? '';
    unset($_SESSION['mensagem']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($acao === 'salvar') {
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;

        $controller->salvar($_POST, $id);

        $_SESSION['mensagem'] = 'Coleta salva com sucesso no banco de dados.';

        header('Location: index.php');
        exit;
    }

    if ($acao === 'aceitar') {
        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            $controller->aceitar($id, $controller->obterIdUsuarioPorEmail('catador@ecoleta.com'));
        }

        header('Location: index.php');
        exit;
    }
if ($acao === 'concluir') {
    $id = (int)($_POST['id'] ?? 0);

    if ($id > 0) {
        $controller->concluir($id, $controller->obterIdUsuarioPorEmail('catador@ecoleta.com'));
    }

    $_SESSION['mensagem'] = 'Coleta concluída com sucesso.';

    header('Location: index.php');
    exit;
}
    if ($acao === 'excluir') {
        $controller->excluir((int)$_POST['id']);

        $_SESSION['mensagem'] = 'Coleta excluída com sucesso.';

        header('Location: index.php');
        exit;
    }
}

    $indicadores = $controller->indicadores();
    $coletas = $controller->listar();
    $idMoradorDemo = $controller->obterIdUsuarioPorEmail('morador@ecoleta.com');
    $idCatadorDemo = $controller->obterIdUsuarioPorEmail('catador@ecoleta.com');
    $coletasMorador = $controller->listarPorMorador($idMoradorDemo);
    $coletasCatador = $controller->listarDisponiveisParaCatador();
    $minhasColetasCatador = $controller->listarPorCatador($idCatadorDemo);
    $dadosForm = $controller->dadosFormulario();
    $edicao = null;

    if ($acao === 'editar') {
        $edicao = $controller->obter((int)($_GET['id'] ?? 0));
        if (!$edicao) {
            throw new RuntimeException('Coleta não encontrada.');
        }
    }

    $usuarioDemo = new Morador($idMoradorDemo, 'Morador Demonstração', 'morador@ecoleta.com');
    $catadorDemo = new Catador($idCatadorDemo, 'Catador Demonstração', 'catador@ecoleta.com');
    $idAdminDemo = $controller->obterIdUsuarioPorEmail('admin@ecoleta.com');
    $adminDemo = new Administrador($idAdminDemo, 'Administrador', 'admin@ecoleta.com');
} catch (Throwable $erro) {
    http_response_code(500);
    $erroFatal = $erro->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecoleta | Gestão de Coleta Seletiva</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topo">
    <div class="marca"><span class="marca-icone">♻</span><div><strong>Ecoleta</strong><small>Coleta seletiva inteligente</small></div></div>
    <span class="tag">ODS 11 · ODS 12</span>
</header>

<main class="container">
<?php if (isset($erroFatal)): ?>
    <section class="alerta erro">
        <h2>Não foi possível conectar ou executar o sistema</h2>
        <p><?= e($erroFatal) ?></p>
        <p>Confira se o MySQL está ativo e se o banco <strong>ecoleta</strong> foi criado pelo arquivo <code>database/schema.sql</code>.</p>
    </section>
<?php else: ?>
    <section class="boas-vindas">
        <div>
            <span class="sobretitulo">PLATAFORMA DE RECICLAGEM</span>
            <h1>Organize a coleta.<br><span>Transforme o futuro.</span></h1>
            <p>Conectando moradores e cooperativas para um descarte mais consciente.</p>
        </div>
        <div class="selo">♻<small>ECONOMIA<br>CIRCULAR</small></div>
    </section>

    <?php if ($mensagem): ?><div class="alerta sucesso"><?= e($mensagem) ?></div><?php endif; ?>

    <section class="indicadores">
        <article class="indicador"><span>Coletas cadastradas</span><strong><?= $indicadores['total'] ?></strong></article>
        <article class="indicador"><span>Aguardando atendimento</span><strong><?= $indicadores['solicitadas'] ?></strong></article>
        <article class="indicador"><span>Coletas concluídas</span><strong><?= $indicadores['concluidas'] ?></strong></article>
        <article class="indicador"><span>Materiais reciclados</span><strong><?= number_format($indicadores['kg'], 2, ',', '.') ?> <small>kg</small></strong></article>
        <article class="indicador"><span>CO₂ estimado evitado</span><strong><?= number_format($indicadores['co2'], 2, ',', '.') ?> <small>kg</small></strong></article>
    </section>

    <section class="painel-info">       
     <a href="paineis/morador.php" class="painel-card">
    <b>👤 Painel do morador</b>
    <p><?= e($usuarioDemo->verPainel()) ?></p>
    <span>Acessar painel →</span>
</a>
        <a href="paineis/catador.php" class="painel-card">
    <b>🚛 Painel do catador</b>
    <p><?= e($catadorDemo->verPainel()) ?></p>
    <span>Ver coletas disponíveis →</span>
</a>    
        <a href="paineis/admin.php" class="painel-card">
    <b>🛠️ Painel administrativo</b>
    <p><?= e($adminDemo->verPainel()) ?></p>
    <span>Ver visão geral →</span>
</a>
    </section>  
  
    <section class="conteudo">
        <div class="cabecalho-secao">
            <div><span class="sobretitulo">GERENCIAMENTO</span><h2><?= $edicao ? 'Editar coleta' : 'Nova solicitação de coleta' ?></h2></div>
            <?php if ($edicao): ?><a class="botao secundario" href="index.php">Cancelar edição</a><?php endif; ?>
        </div>

        <form method="post" class="formulario" id="formColeta">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?= e((string)($edicao['id'] ?? '')) ?>">
            <div class="grade-form">
                <label>Morador
                    <select name="morador_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($dadosForm['moradores'] as $morador): ?>
                            <option value="<?= $morador['id'] ?>" <?= (string)($edicao['morador_id'] ?? '') === (string)$morador['id'] ? 'selected' : '' ?>><?= e($morador['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Catador responsável
                    <select name="catador_id">
                        <option value="">Ainda não definido</option>
                        <?php foreach ($dadosForm['catadores'] as $catador): ?>
                            <option value="<?= $catador['id'] ?>" <?= (string)($edicao['catador_id'] ?? '') === (string)$catador['id'] ? 'selected' : '' ?>><?= e($catador['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Ponto de coleta (opcional)
                    <select name="ponto_coleta_id">
                        <option value="">Coleta no endereço informado</option>
                        <?php foreach ($dadosForm['pontos'] as $ponto): ?>
                            <option value="<?= $ponto['id'] ?>" <?= (string)($edicao['ponto_coleta_id'] ?? '') === (string)$ponto['id'] ? 'selected' : '' ?>><?= e($ponto['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Data agendada
                    <input type="date" name="data_agendada" required value="<?= e($edicao['data_agendada'] ?? date('Y-m-d')) ?>">
                </label>
                <label class="campo-largo">Endereço da coleta
                    <input type="text" name="endereco_coleta" required maxlength="255" placeholder="Rua, número, bairro" value="<?= e($edicao['endereco_coleta'] ?? '') ?>">
                </label>
                <label>Status
                    <select name="status">
                        <?php foreach (['solicitada'=>'Solicitada','aceita'=>'Aceita','em_rota'=>'Em rota','concluida'=>'Concluída','cancelada'=>'Cancelada'] as $valor=>$rotulo): ?>
                            <option value="<?= $valor ?>" <?= ($edicao['status'] ?? 'solicitada') === $valor ? 'selected' : '' ?>><?= $rotulo ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="campo-largo">Observações
                    <textarea name="observacoes" rows="2" placeholder="Informações adicionais (opcional)"><?= e($edicao['observacoes'] ?? '') ?></textarea>
                </label>
            </div>

            <div class="materiais-topo"><div><h3>Materiais recicláveis</h3><p>Adicione pelo menos um material e sua quantidade em kg.</p></div><button type="button" class="botao secundario" id="adicionarMaterial">+ Adicionar material</button></div>
            <div id="listaMateriais">
                <?php
                $materiaisEdicao = $edicao['materiais'] ?? [['tipo'=>'papel','quantidade_kg'=>'']];
                foreach ($materiaisEdicao as $material):
                ?>
                <div class="linha-material">
                    <select name="material_tipo[]" required>
                        <?php foreach (['papel'=>'Papel','plastico'=>'Plástico','vidro'=>'Vidro','metal'=>'Metal','eletronico'=>'Eletrônico','outro'=>'Outro'] as $valor=>$rotulo): ?>
                            <option value="<?= $valor ?>" <?= ($material['tipo'] ?? '') === $valor ? 'selected' : '' ?>><?= $rotulo ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="material_quantidade[]" min="0.01" step="0.01" required placeholder="Quantidade (kg)" value="<?= e((string)($material['quantidade_kg'] ?? '')) ?>">
                    <button type="button" class="remover-material" title="Remover material">×</button>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="botao principal" type="submit"><?= $edicao ? 'Salvar alterações' : 'Cadastrar coleta' ?></button>
        </form>
    </section>

    <section class="conteudo">
        <div class="cabecalho-secao"><div><span class="sobretitulo">REGISTROS DO SISTEMA</span><h2>Coletas cadastradas</h2></div><span class="contador"><?= count($coletas) ?> registro(s)</span></div>
        <?php if (!$coletas): ?>
            <div class="vazio">Nenhuma coleta cadastrada. Use o formulário acima para criar a primeira.</div>
        <?php else: ?>
        <div class="tabela-responsiva">
            <table>
                <thead><tr><th>#</th><th>Morador</th><th>Data</th><th>Endereço</th><th>Materiais</th><th>Status</th><th>Ações</th></tr></thead>
                <tbody>
                <?php foreach ($coletas as $coleta): ?>
                    <tr>
                        <td>#<?= (int)$coleta['id'] ?></td>
                        <td><?= e($coleta['morador_nome']) ?></td>
                        <td><?= date('d/m/Y', strtotime($coleta['data_agendada'])) ?></td>
                        <td><?= e($coleta['endereco_coleta']) ?></td>
                        <td><?= number_format((float)$coleta['total_kg'], 2, ',', '.') ?> kg</td>
                        <td><span class="status status-<?= e($coleta['status']) ?>"><?= e(str_replace('_', ' ', ucfirst($coleta['status']))) ?></span></td>
                        <td class="acoes"><a class="acao-editar" href="index.php?acao=editar&id=<?= (int)$coleta['id'] ?>">Editar</a>
                            <form method="post" onsubmit="return confirm('Deseja realmente excluir esta coleta?')">
                                <input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= (int)$coleta['id'] ?>">
                                <button class="acao-excluir" type="submit">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </section>
<?php endif; ?>
</main>
<footer><strong>Ecoleta</strong> · Projeto acadêmico AEP 2026.2 · ODS 11 e ODS 12</footer>
<script src="assets/app.js"></script>
</body>
</html>
