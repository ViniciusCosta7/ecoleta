<?php
declare(strict_types=1);

require_once __DIR__ . '/MaterialReciclavel.php';

class Coleta
{
    private array $materiais = [];

    public function __construct(
        private ?int $id,
        private int $moradorId,
        private ?int $catadorId,
        private ?int $pontoColetaId,
        private string $endereco,
        private string $dataAgendada,
        private string $status,
        private ?string $observacoes = null
    ) {}

    public function adicionarMaterial(MaterialReciclavel $material): void
    {
        $this->materiais[] = $material;
    }

    public function validar(): void
    {
        if ($this->moradorId <= 0 || trim($this->endereco) === '') {
            throw new InvalidArgumentException('Informe o morador e o endereço da coleta.');
        }
        if (count($this->materiais) === 0) {
            throw new InvalidArgumentException('A coleta deve possuir pelo menos um material reciclável.');
        }
        $data = DateTime::createFromFormat('Y-m-d', $this->dataAgendada);
        if (!$data || $data->format('Y-m-d') !== $this->dataAgendada) {
            throw new InvalidArgumentException('Informe uma data válida.');
        }
        $permitidos = ['solicitada', 'aceita', 'em_rota', 'concluida', 'cancelada'];
        if (!in_array($this->status, $permitidos, true)) {
            throw new InvalidArgumentException('Status inválido.');
        }
    }

    public function getMateriais(): array
    {
        return $this->materiais;
    }

    public function calcularTotalKg(): float
    {
        return array_reduce(
            $this->materiais,
            fn(float $total, MaterialReciclavel $m): float => $total + $m->getQuantidadeKg(),
            0.0
        );
    }

    public function calcularCo2Evitado(): float
    {
        return array_reduce(
            $this->materiais,
            fn(float $total, MaterialReciclavel $m): float => $total + $m->calcularCo2Evitado(),
            0.0
        );
    }
}
