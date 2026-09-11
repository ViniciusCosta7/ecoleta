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

---

## Lista de Requisitos

### Requisitos Funcionais (RF)

| Código | Descrição |
|---|---|
| RF01 | O sistema deve permitir o cadastro e a autenticação de usuários, nos perfis de morador, catador e administrador. |
| RF02 | O morador deve poder cadastrar os materiais recicláveis que deseja descartar e solicitar uma coleta. |
| RF03 | O morador deve poder escolher entre agendar uma coleta domiciliar ou realizar a entrega em um ponto de coleta (PEV) cadastrado. |
| RF04 | O catador deve poder visualizar as solicitações de coleta filtradas por sua área de atuação. |
| RF05 | O catador deve poder aceitar uma coleta e atualizar seu status ao longo do fluxo (aceita → em rota → concluída/cancelada). |
| RF06 | O sistema deve calcular automaticamente o volume total reciclado e o CO2 evitado a partir dos materiais de cada coleta concluída. |
| RF07 | O administrador deve poder gerenciar os usuários e os pontos de coleta cadastrados na plataforma. |
| RF08 | O sistema deve exibir um painel de impacto ambiental, agregando os dados de coletas concluídas por morador, por cooperativa e de forma geral. |
| RF09 | O sistema deve notificar os usuários sobre mudanças relevantes no status de suas coletas. |
| RF10 | O sistema deve permitir o cadastro e a listagem de pontos de coleta (PEVs), com endereço e horário de funcionamento. |

### Requisitos Não Funcionais (RNF)

| Código | Descrição |
|---|---|
| RNF01 | **Usabilidade:** a interface deve ser simples e responsiva, utilizável por pessoas com pouca familiaridade digital. |
| RNF02 | **Segurança:** as senhas dos usuários devem ser armazenadas de forma criptografada (hash), nunca em texto plano. |
| RNF03 | **Desempenho:** as operações principais (login, agendamento de coleta, consulta de painel) não devem ultrapassar 3 segundos de resposta em condições normais de uso. |
| RNF04 | **Portabilidade:** o sistema deve funcionar corretamente nos principais navegadores web (Chrome, Firefox, Edge). |
| RNF05 | **Manutenibilidade:** o código deve seguir a separação em camadas (MVC), facilitando a manutenção e a evolução do sistema. |
| RNF06 | **Disponibilidade:** o sistema deve estar acessível via navegador a qualquer momento, sem dependência de softwares adicionais no dispositivo do usuário final. |

---

## Cronograma (1º Bimestre)

| Semana | Atividade | Entregável |
|---|---|---|
| Semana 1 | Levantamento de requisitos: observação do cenário, identificação de dores e partes interessadas. | Seção "Descoberta" e lista preliminar de dores/regras de negócio. |
| Semana 2 | Elaboração da lista de requisitos funcionais e não funcionais; definição da concepção do projeto e alinhamento com os ODS da ONU. | Lista de Requisitos e Seção "Concepção e Alinhamento". |
| Semana 3 | Definição da arquitetura (MVC), escolha e justificativa da stack tecnológica (HTML/CSS/JS, PHP, MySQL). | Seção "Justificativa Técnica e Arquitetural". |
| Semana 4 | Modelagem do domínio (diagrama de classes com herança, polimorfismo e composição) e modelagem do banco de dados (DER). | Diagrama de Classes e Diagrama do Banco de Dados (DER). |
| Semana 5 | Criação do repositório no GitHub, organização da estrutura de diretórios e consolidação final da documentação. | Repositório público no GitHub e documento de entrega do 1º bimestre. |

---

## Estrutura de pastas

- `docs/` — documentação e diagramas do projeto
- `database/` — script de criação do banco de dados 
- `public/` — ponto de entrada da aplicação e arquivos estáticos
- `src/config/` — arquivos de configuração 
- `src/models/` — classes do domínio 
- `src/controllers/` — lógica de controle das requisições
- `src/views/` — páginas/templates exibidos ao usuário
- `tests/` — reservado para testes automatizados 
