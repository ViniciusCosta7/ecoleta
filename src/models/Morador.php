<?php
declare(strict_types=1);

require_once __DIR__ . '/Usuario.php';

class Morador extends Usuario
{
    #[\Override]
    public function verPainel(): string
    {
        return 'Acompanhe seu histórico e solicite novas coletas.';
    }
}
