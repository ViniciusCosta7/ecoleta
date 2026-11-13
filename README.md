# Ecoleta — 2ª Entrega da AEP

Plataforma web acadêmica para organizar solicitações de coleta seletiva, conectar moradores a cooperativas e acompanhar materiais recicláveis e impacto ambiental estimado.

## Tecnologias
- PHP 8.3 ou superior.
- MySQL 8.0 ou MariaDB com InnoDB.
- HTML5, CSS3 e JavaScript.
- PDO para acesso ao banco de dados.
- Organização em camadas, com MVC simplificado.

## Funcionalidades presentes
- Cadastro, consulta, edição e exclusão de solicitações de coleta.
- Associação de um ou mais materiais recicláveis a cada coleta.
- Fluxo de situação: `solicitada` → `aceita` → `em_rota` → `concluida`, além de `cancelada`.
- Seleção de PEV previamente cadastrado no banco.
- Painéis demonstrativos de morador, catador e administrador.
- Indicadores de coletas, quilogramas de materiais e CO₂ estimado evitado.
- Persistência das coletas e materiais no MySQL.

O controller localiza os registros de demonstração pelo e-mail, sem depender de IDs fixos no código.

## Orientação a objetos
- Classe abstrata `Usuario`.
- Herança por `Morador`, `Catador` e `Administrador`.
- Polimorfismo por sobrescrita de `verPainel()` com `#[\\Override]`.
- Composição entre `Coleta` e objetos `MaterialReciclavel`.
