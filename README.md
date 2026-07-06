# Sistema de Gestão - PetShop (MVP)

Este é um Minimum Viable Product (MVP) desenvolvido como solução computacional para um projeto acadêmico. O sistema automatiza o agendamento de serviços, gestão de clientes, controle de pets e fluxos de atendimento de um PetShop.

## Objetivo
Proporcionar uma interface web rápida e funcional para administração de PetShops, garantindo a integridade dos dados através de uma modelagem relacional sólida, proteção contra conflitos de agenda e histórico de atendimentos.

## Tecnologias Utilizadas
O projeto foi construído focando nos fundamentos da web, sem a utilização de frameworks pesados:
* **Frontend:** HTML5, CSS3 (Responsivo), JavaScript (Vanilla)
* **Backend:** PHP (Puro/Estruturado)
* **Banco de Dados:** MySQL (Consultas com Prepared Statements e transações via PDO)
* **Servidor:** Apache (XAMPP tradicional)

## Funcionalidades Implementadas
* **Autenticação:** Sistema de login seguro com senhas criptografadas (`password_hash`).
* **Dashboard:** Painel principal interativo para acesso rápido aos módulos.
* **CRUD de Clientes e Pets:** Cadastro unificado utilizando *Transações SQL* (`Commit`/`Rollback`) para garantir a integridade relacional.
* **Gestão de Agendamentos:** Controle de status, cruzamento de dados de 5 tabelas diferentes via `INNER JOIN` e trava no banco de dados contra concorrência de horários para o mesmo funcionário.
* **Consulta Inteligente:** Barra de pesquisa unificada buscando dados simultâneos do tutor ou do pet.
* **Fale Conosco:** Formulário de contato que registra as solicitações no banco de dados e dispara e-mails via SMTP autenticado (PHPMailer).

## Estrutura de Diretórios
```text
Projeto/
├── banco/             # Scripts SQL de criação do banco e inserts
├── consultas/         # Módulo de pesquisa avançada
├── contato/           # Formulário de contato e integração SMTP
├── crud/              # Lógica de criação, leitura, edição e exclusão (Clientes, Pets, Agenda)
├── css/               # Folhas de estilo globais
├── includes/          # Componentes reutilizáveis (Header, Footer, Conexão, PHPMailer)
├── login/             # Autenticação e destruição de sessão
└── js/                # Scripts utilitários de validação