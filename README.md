# Ecoleta — 2ª Entrega da AEP

Sistema web desenvolvido para facilitar o gerenciamento de solicitações de coleta seletiva, conectando moradores e coletores e promovendo a reciclagem.

## Tecnologias
- PHP 8.3+
- MySQL
- HTML5, CSS3 e JavaScript
- PDO
- MVC simplificado

## Funcionalidades
- Cadastro, consulta, edição e exclusão de coletas.
- Associação de materiais recicláveis.
- Gerenciamento do status das solicitações.
- Painéis de morador, catador e administrador.
- Indicadores de materiais reciclados e CO₂ estimado evitado.
- Integração e persistência de dados no banco de dados.

## Programação Orientada a Objetos
- Abstração: classe `Usuario`.
- Herança: `Morador`, `Catador` e `Administrador`.
- Polimorfismo: sobrescrita do método `verPainel()`.
- Composição: `Coleta` e `MaterialReciclavel`.

## Banco de Dados
O projeto utiliza MySQL para armazenar usuários, solicitações de coleta, materiais recicláveis e PEVs.

## Objetivo
Aplicar conceitos de desenvolvimento web, programação orientada a objetos e integração com banco de dados em uma solução voltada à coleta seletiva e à sustentabilidade.

---
