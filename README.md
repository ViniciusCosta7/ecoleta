# Ecoleta — 2ª Entrega da AEP

Plataforma web acadêmica para organizar solicitações de coleta seletiva, conectar moradores a cooperativas e acompanhar materiais recicláveis e impacto ambiental estimado.

## Tecnologias
- PHP 8.3 ou superior (uso do atributo nativo `#[\\Override]`).
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

## Estrutura
```text
ecoleta/
├── README.md
├── database/schema.sql
├── docs/manual-execucao.md
├── tests/testes-modelos.php
├── public/
│   ├── index.php
│   ├── paineis/
│   └── assets/
└── src/
    ├── config/Database.php
    ├── controllers/ColetaController.php
    └── models/
```

## Como executar no Windows com XAMPP
1. Instale XAMPP com PHP 8.3 ou superior e MySQL/MariaDB.
2. Copie a pasta `ecoleta` para `C:\\xampp\\htdocs\\`.
3. Inicie Apache e MySQL no painel do XAMPP.
4. Importe `database/schema.sql` pelo phpMyAdmin.
5. Confira as credenciais em `src/config/Database.php` (configuração padrão local: usuário `root`, senha vazia).
6. Acesse `http://localhost/ecoleta/public/`.

## Contas de demonstração
- `morador@ecoleta.com` — morador
- `catador@ecoleta.com` — catador
- `admin@ecoleta.com` — administrador
- Senha inicial dos registros: `password`.

**Atenção:** os perfis são demonstrativos; esta versão não implementa autenticação/login real nem controle de acesso de produção. Não use essas credenciais em ambiente público.

## Testes
Execute `php tests/testes-modelos.php` no terminal, dentro da pasta do projeto. Os testes automatizados verificam regras dos modelos, cálculo de materiais e polimorfismo sem exigir conexão com o banco.

A validação integrada de CRUD e persistência deve ser realizada com MySQL/MariaDB ativo. O roteiro está em `docs/manual-execucao.md`.

## Limitações conhecidas
- Não há tela administrativa para cadastrar, editar ou excluir usuários e PEVs.
- O formulário permite selecionar PEVs já cadastrados no banco.
- Não há filtros de solicitações por região ou tipo de material.
- As contas de demonstração não representam autenticação real.
- A edição e a exclusão estão disponíveis na interface; recomenda-se demonstrá-las e confirmá-las no ambiente local antes da apresentação.

## Observações
Os fatores de CO₂ são estimativas didáticas configuradas em `MaterialReciclavel.php`, não índices oficiais. Cada integrante deve realizar contribuições reais no GitHub, com commits identificáveis, conforme a orientação da AEP.
