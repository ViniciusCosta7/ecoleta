# Ecoleta

Plataforma web que conecta moradores a cooperativas de catadores para agendamento
de coleta seletiva de materiais recicláveis, com acompanhamento de status e
painel de impacto ambiental (kg reciclados e CO2 evitado).

## ODS relacionados
- ODS 11 — Cidades e Comunidades Sustentáveis
- ODS 12 — Consumo e Produção Responsáveis

## Stack tecnológica
- Front-end: HTML5, CSS3, JavaScript
- Back-end: PHP
- Banco de dados: MySQL

## Status do projeto
Fase atual: **Concepção e planejamento arquitetural** (1º Bimestre).
Nenhum código funcional foi implementado ainda — esta fase contém apenas a
estrutura de diretórios e o projeto arquitetural (diagramas de classes e DER).

## Estrutura de pastas
Veja `docs/documento_projeto.pdf` para a documentação completa do projeto.

- `docs/` — documentação e diagramas do projeto
- `database/` — script de criação do banco de dados (schema.sql)
- `public/` — ponto de entrada da aplicação e arquivos estáticos (CSS/JS/imagens)
- `src/config/` — arquivos de configuração (ex: conexão com banco de dados)
- `src/models/` — classes do domínio (Usuario, Morador, Catador, Coleta, etc.)
- `src/controllers/` — lógica de controle das requisições
- `src/views/` — páginas/templates exibidos ao usuário
- `tests/` — reservado para testes automatizados (próximas etapas)
