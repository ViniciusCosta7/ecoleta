<?php
declare(strict_types=1);

require_once __DIR__ . '/Usuario.php';

class Administrador extends Usuario
{
    #[\Override]
    public function verPainel(): string
    {
        return 'Acesse os indicadores gerais e gerencie os registros da plataforma.';
    }
}
