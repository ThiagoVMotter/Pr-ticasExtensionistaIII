CREATE DATABASE IF NOT EXISTS petshop_mvp
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE petshop_mvp;

CREATE TABLE funcionario (
    id_funcionario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, -- Será gravada com password_hash()
    perfil ENUM('administrador', 'funcionario') DEFAULT 'funcionario',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    endereco VARCHAR(255)
);

CREATE TABLE pet (
    id_pet INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    especie VARCHAR(50) NOT NULL,
    raca VARCHAR(50),
    sexo ENUM('Macho', 'Femea') NOT NULL,
    peso DECIMAL(5,2),
    cor VARCHAR(50),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente) ON DELETE RESTRICT
);

CREATE TABLE servico (
    id_servico INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    duracao INT NOT NULL -- Duração em minutos
);

CREATE TABLE produto (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    quantidade_estoque INT NOT NULL DEFAULT 0
);

CREATE TABLE agendamento (
    id_agendamento INT AUTO_INCREMENT PRIMARY KEY,
    id_pet INT NOT NULL,
    id_servico INT NOT NULL,
    id_funcionario INT NOT NULL,
    data_agendamento DATE NOT NULL,
    horario TIME NOT NULL,
    status ENUM('Pendente', 'Confirmado', 'Concluido', 'Cancelado') DEFAULT 'Pendente',
    FOREIGN KEY (id_pet) REFERENCES pet(id_pet) ON DELETE RESTRICT,
    FOREIGN KEY (id_servico) REFERENCES servico(id_servico) ON DELETE RESTRICT,
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario) ON DELETE RESTRICT,
    UNIQUE KEY uk_agenda_funcionario (id_funcionario, data_agendamento, horario)
);

CREATE TABLE atendimento (
    id_atendimento INT AUTO_INCREMENT PRIMARY KEY,
    id_agendamento INT NOT NULL,
    observacoes TEXT NOT NULL,
    data_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_agendamento) REFERENCES agendamento(id_agendamento) ON DELETE RESTRICT
);

CREATE TABLE venda (
    id_venda INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('Pendente', 'Paga', 'Cancelada') DEFAULT 'Pendente',
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente) ON DELETE RESTRICT
);

CREATE TABLE item_venda (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_venda INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_venda) REFERENCES venda(id_venda) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produto(id_produto) ON DELETE RESTRICT
);

CREATE TABLE mensagens_contato (
    id_mensagem INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    assunto VARCHAR(150) NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO funcionario (nome, email, senha, perfil) VALUES 
('Administrador do Sistema', 'admin@petshop.com', '$2y$10$wYx16U6B0yv69q7Z69.5u.2356nJw1Z6m/n33a2/aFvT/bX9d8kGC', 'administrador'),
('Funcionario Exemplo', 'funcionario@petshop.com', '$2y$10$wYx16U6B0yv69q7Z69.5u.2356nJw1Z6m/n33a2/aFvT/bX9d8kGC', 'funcionario');

INSERT INTO cliente (nome, telefone, email, endereco) VALUES 
('João Silva', '(49) 99999-9999', 'joao@email.com', 'Rua Central, 123, Joaçaba');

INSERT INTO pet (id_cliente, nome, especie, raca, sexo, peso, cor) VALUES 
(1, 'Rex', 'Cachorro', 'Labrador', 'Macho', 25.50, 'Amarelo');

INSERT INTO servico (nome, descricao, preco, duracao) VALUES 
('Banho e Tosa Completo', 'Banho com shampoo especial, secagem, escovação e tosa na máquina.', 85.00, 90),
('Consulta Clínica', 'Avaliação geral do pet com o veterinário responsável.', 150.00, 45);

INSERT INTO produto (nome, descricao, preco, quantidade_estoque) VALUES 
('Ração Premium 15kg', 'Ração seca sabor carne para cães adultos.', 189.90, 20),
('Shampoo Antipulgas', 'Shampoo de tratamento dermatológico 500ml.', 45.50, 15);