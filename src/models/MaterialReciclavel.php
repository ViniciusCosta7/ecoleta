<?php
declare(strict_types=1);

class MaterialReciclavel
{
    private const FATORES_CO2 = [
        'papel' => 0.90,
        'plastico' => 1.50,
        'vidro' => 0.30,
        'metal' => 2.00,
        'eletronico' => 1.20,
        'outro' => 0.50
    ];

    public function __construct(
        private string $tipo,
        private float $quantidadeKg
    ) {
        if ($quantidadeKg <= 0) {
            throw new InvalidArgumentException('A quantidade deve ser maior que zero.');
        }
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function getQuantidadeKg(): float
    {
        return $this->quantidadeKg;
    }

    public function calcularCo2Evitado(): float
    {
        $chave = strtolower($this->tipo);
        $fator = self::FATORES_CO2[$chave] ?? self::FATORES_CO2['outro'];
        return $this->quantidadeKg * $fator;
    }
}
