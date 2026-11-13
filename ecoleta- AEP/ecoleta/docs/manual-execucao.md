# Manual de execução e demonstração — Ecoleta

## Preparação
1. Instale XAMPP com PHP 8.3 ou superior e MySQL/MariaDB.
2. Copie a pasta `ecoleta` para `htdocs`.
3. Inicie Apache e MySQL.
4. Importe `database/schema.sql` pelo phpMyAdmin.
5. Confira as credenciais em `src/config/Database.php`.
6. Acesse `http://localhost/ecoleta/public/`.

## Roteiro de demonstração
1. Abra a página inicial e apresente os indicadores.
2. Selecione o morador de demonstração, informe endereço e data.
3. Adicione ao menos um material e sua quantidade em kg; salve.
4. Mostre o registro na listagem e no painel do morador.
5. No painel do catador, aceite uma solicitação disponível.
6. Inicie a rota e conclua a coleta.
7. Mostre a atualização do status e dos indicadores ambientais.
8. Demonstre também a edição e exclusão de uma coleta de teste.

## Testes automatizados
Execute `php tests/testes-modelos.php` na pasta raiz. O script verifica cálculo estimado de CO₂, soma de materiais, validação de coleta sem material e polimorfismo. Esses testes não substituem a integração com MySQL.

## Orientação a objetos
- `Usuario` é uma classe abstrata e declara `verPainel()`.
- `Morador`, `Catador` e `Administrador` herdam de `Usuario`.
- Cada classe filha sobrescreve `verPainel()` com `#[\\Override]`.
- `Coleta` mantém objetos `MaterialReciclavel`, formando composição no modelo.
- No banco, `material_reciclavel.coleta_id` referencia `coleta.id` com `ON DELETE CASCADE`.

## Persistência
A classe `Database` cria a conexão PDO com MySQL. O controller utiliza consultas preparadas e transação para salvar a coleta e seus materiais. A exclusão remove os materiais associados por cascata. A extensão `pdo_mysql` deve estar habilitada.

## Limitações conhecidas
Esta versão não possui autenticação real, tela administrativa para gerenciar usuários/PEVs nem filtros por região ou material. O formulário permite selecionar PEV previamente cadastrado. As contas de demonstração existem para facilitar a apresentação local.

Os fatores de CO₂ são estimativas didáticas configuradas no modelo `MaterialReciclavel`; não devem ser apresentados como valores oficiais.

## Repositório
Inserir a URL do repositório GitHub e conferir se todos os integrantes realizaram commits próprios, conforme a orientação da disciplina.
