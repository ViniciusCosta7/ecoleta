<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Coleta.php';

class ColetaController
{
    public function __construct(private PDO $db) {}

    public function obterIdUsuarioPorEmail(string $email): int
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM usuario WHERE email = :email LIMIT 1"
        );
        $stmt->execute([':email' => trim($email)]);
        $id = $stmt->fetchColumn();

        if ($id === false) {
            throw new RuntimeException('Usuário de demonstração não encontrado: ' . $email);
        }

        return (int) $id;
    }

    public function listar(): array
    
{
    $sql = "SELECT c.*,
                   u.nome AS morador_nome,
                   cat.nome AS catador_nome,
                   p.nome AS ponto_nome,
                   COALESCE(SUM(m.quantidade_kg), 0) AS total_kg
            FROM coleta c
            INNER JOIN usuario u ON u.id = c.morador_id
            LEFT JOIN usuario cat ON cat.id = c.catador_id
            LEFT JOIN ponto_coleta p ON p.id = c.ponto_coleta_id
            LEFT JOIN material_reciclavel m ON m.coleta_id = c.id
            GROUP BY c.id
            ORDER BY c.criado_em DESC";

    return $this->db->query($sql)->fetchAll();
}

    public function obter(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM coleta WHERE id = ?");
        $stmt->execute([$id]);
        $coleta = $stmt->fetch();
        if (!$coleta) {
            return null;
        }

        $stmt = $this->db->prepare("SELECT tipo, quantidade_kg FROM material_reciclavel WHERE coleta_id = ?");
        $stmt->execute([$id]);
        $coleta['materiais'] = $stmt->fetchAll();
        return $coleta;
    }

    public function salvar(array $dados, ?int $id = null): void
    {
        $materiais = $this->normalizarMateriais($dados);
        $objeto = new Coleta(
            $id,
            (int)$dados['morador_id'],
            !empty($dados['catador_id']) ? (int)$dados['catador_id'] : null,
            !empty($dados['ponto_coleta_id']) ? (int)$dados['ponto_coleta_id'] : null,
            trim($dados['endereco_coleta']),
            $dados['data_agendada'],
            $dados['status'] ?? 'solicitada',
            trim($dados['observacoes'] ?? '')
        );

        foreach ($materiais as $material) {
            $objeto->adicionarMaterial(new MaterialReciclavel($material['tipo'], $material['quantidade_kg']));
        }
        $objeto->validar();

        $this->db->beginTransaction();
        try {
            if ($id === null) {
                $sql = "INSERT INTO coleta (morador_id, catador_id, ponto_coleta_id, endereco_coleta, data_agendada, status, observacoes)
                        VALUES (:morador, :catador, :ponto, :endereco, :data, :status, :obs)";
            } else {
                $sql = "UPDATE coleta SET morador_id=:morador, catador_id=:catador, ponto_coleta_id=:ponto,
                        endereco_coleta=:endereco, data_agendada=:data, status=:status, observacoes=:obs WHERE id=:id";
            }

            $stmt = $this->db->prepare($sql);
            $params = [
                ':morador' => (int)$dados['morador_id'],
                ':catador' => !empty($dados['catador_id']) ? (int)$dados['catador_id'] : null,
                ':ponto' => !empty($dados['ponto_coleta_id']) ? (int)$dados['ponto_coleta_id'] : null,
                ':endereco' => trim($dados['endereco_coleta']),
                ':data' => $dados['data_agendada'],
                ':status' => $dados['status'] ?? 'solicitada',
                ':obs' => trim($dados['observacoes'] ?? '')
            ];
            if ($id !== null) {
                $params[':id'] = $id;
            }
            $stmt->execute($params);

            if ($id === null) {
                $id = (int)$this->db->lastInsertId();
            } else {
                $stmt = $this->db->prepare("DELETE FROM material_reciclavel WHERE coleta_id = ?");
                $stmt->execute([$id]);
            }

            $stmtMaterial = $this->db->prepare(
                "INSERT INTO material_reciclavel (coleta_id, tipo, quantidade_kg) VALUES (?, ?, ?)"
            );
            foreach ($materiais as $material) {
                $stmtMaterial->execute([$id, $material['tipo'], $material['quantidade_kg']]);
            }

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
public function listarPorMorador(int $moradorId): array
{
    $sql = "SELECT c.*,
                   COALESCE(SUM(m.quantidade_kg), 0) AS total_kg
            FROM coleta c
            LEFT JOIN material_reciclavel m ON m.coleta_id = c.id
            WHERE c.morador_id = ?
            GROUP BY c.id
            ORDER BY c.criado_em DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$moradorId]);

    return $stmt->fetchAll();
}
public function listarDisponiveisParaCatador(): array
{
    $sql = "SELECT c.*,
                   u.nome AS morador_nome,
                   COALESCE(SUM(m.quantidade_kg), 0) AS total_kg
            FROM coleta c
            INNER JOIN usuario u ON u.id = c.morador_id
            LEFT JOIN material_reciclavel m ON m.coleta_id = c.id
            WHERE c.status = 'solicitada'
            GROUP BY c.id
            ORDER BY c.data_agendada ASC, c.criado_em DESC";

    return $this->db->query($sql)->fetchAll();
}
public function listarPorCatador(int $catadorId): array
{
    $sql = "SELECT c.*,
                   u.nome AS morador_nome,
                   COALESCE(SUM(m.quantidade_kg), 0) AS total_kg
            FROM coleta c
            INNER JOIN usuario u ON u.id = c.morador_id
            LEFT JOIN material_reciclavel m ON m.coleta_id = c.id
            WHERE c.catador_id = ?
            GROUP BY c.id
            ORDER BY c.data_agendada ASC, c.criado_em DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$catadorId]);

    return $stmt->fetchAll();
}           
public function aceitar(int $id, int $catadorId): bool
{
    $sql = "UPDATE coleta
            SET catador_id = ?, status = 'aceita'
            WHERE id = ? AND status = 'solicitada' AND catador_id IS NULL";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$catadorId, $id]);

    return $stmt->rowCount() === 1;
}  
public function iniciarRota(int $id, int $catadorId): bool
    {
        $sql = "UPDATE coleta
                SET status = 'em_rota'
                WHERE id = ? AND catador_id = ? AND status = 'aceita'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $catadorId]);
        return $stmt->rowCount() === 1;
    }

    public function concluir(int $id, int $catadorId): bool
    {
        $sql = "UPDATE coleta
                SET status = 'concluida'
                WHERE id = ? AND catador_id = ? AND status = 'em_rota'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $catadorId]);
        return $stmt->rowCount() === 1;
    }

    public function excluir(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM coleta WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function dadosFormulario(): array
    {
        $moradores = $this->db->query(
            "SELECT u.id, u.nome FROM morador m INNER JOIN usuario u ON u.id=m.usuario_id ORDER BY u.nome"
        )->fetchAll();
        $catadores = $this->db->query(
            "SELECT u.id, u.nome FROM catador c INNER JOIN usuario u ON u.id=c.usuario_id ORDER BY u.nome"
        )->fetchAll();
        $pontos = $this->db->query("SELECT id, nome FROM ponto_coleta WHERE ativo=1 ORDER BY nome")->fetchAll();
        return compact('moradores', 'catadores', 'pontos');
    }

    public function indicadores(): array
    {
        $sql = "SELECT COUNT(*) AS total,
                       SUM(status='solicitada') AS solicitadas,
                       SUM(status='concluida') AS concluidas
                FROM coleta";
        $dados = $this->db->query($sql)->fetch();

        $impacto = $this->db->query(
            "SELECT
                COALESCE(SUM(m.quantidade_kg), 0) AS kg,
                COALESCE(SUM(m.quantidade_kg * CASE m.tipo
                    WHEN 'papel' THEN 0.90
                    WHEN 'plastico' THEN 1.50
                    WHEN 'vidro' THEN 0.30
                    WHEN 'metal' THEN 2.00
                    WHEN 'eletronico' THEN 1.20
                    ELSE 0.50
                END), 0) AS co2
             FROM coleta c
             INNER JOIN material_reciclavel m ON m.coleta_id = c.id
             WHERE c.status = 'concluida'"
        )->fetch();

        return [
            'total' => (int)($dados['total'] ?? 0),
            'solicitadas' => (int)($dados['solicitadas'] ?? 0),
            'concluidas' => (int)($dados['concluidas'] ?? 0),
            'kg' => (float)($impacto['kg'] ?? 0),
            'co2' => (float)($impacto['co2'] ?? 0)
        ];
    }

    private function normalizarMateriais(array $dados): array
    {
        $tipos = $dados['material_tipo'] ?? [];
        $quantidades = $dados['material_quantidade'] ?? [];
        $resultado = [];

        foreach ($tipos as $i => $tipo) {
            $tipo = strtolower(trim((string)$tipo));
            $quantidade = (float)($quantidades[$i] ?? 0);
            if ($tipo !== '' && $quantidade > 0) {
                $permitidos = ['papel', 'plastico', 'vidro', 'metal', 'eletronico', 'outro'];
                if (!in_array($tipo, $permitidos, true)) {
                    throw new InvalidArgumentException('Tipo de material inválido.');
                }
                $resultado[] = ['tipo' => $tipo, 'quantidade_kg' => $quantidade];
            }
        }

        return $resultado;
    }
}
