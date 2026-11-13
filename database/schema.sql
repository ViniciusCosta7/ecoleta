CREATE DATABASE IF NOT EXISTS ecoleta
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecoleta;

CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefone VARCHAR(25) NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('morador','catador','administrador') NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS morador (
    usuario_id INT PRIMARY KEY,
    endereco VARCHAR(255) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS catador (
    usuario_id INT PRIMARY KEY,
    cooperativa VARCHAR(150) NOT NULL,
    area_atuacao VARCHAR(150) NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS administrador (
    usuario_id INT PRIMARY KEY,
    nivel_acesso VARCHAR(50) NOT NULL DEFAULT 'geral',
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ponto_coleta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    horario_funcionamento VARCHAR(120) NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS coleta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    morador_id INT NOT NULL,
    catador_id INT NULL,
    ponto_coleta_id INT NULL,
    endereco_coleta VARCHAR(255) NOT NULL,
    data_agendada DATE NOT NULL,
    observacoes TEXT NULL,
    status ENUM('solicitada','aceita','em_rota','concluida','cancelada') NOT NULL DEFAULT 'solicitada',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (morador_id) REFERENCES morador(usuario_id),
    FOREIGN KEY (catador_id) REFERENCES catador(usuario_id) ON DELETE SET NULL,
    FOREIGN KEY (ponto_coleta_id) REFERENCES ponto_coleta(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS material_reciclavel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coleta_id INT NOT NULL,
    tipo VARCHAR(60) NOT NULL,
    quantidade_kg DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (coleta_id) REFERENCES coleta(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notificacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    mensagem VARCHAR(255) NOT NULL,
    lida TINYINT(1) NOT NULL DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Dados iniciais para facilitar a demonstração.
-- Senha dos usuários de demonstração: password
INSERT IGNORE INTO usuario (id, nome, email, telefone, senha, tipo) VALUES
(1, 'Morador Demonstração', 'morador@ecoleta.com', '(44) 99999-1111', '$2y$12$1.As/K5ufcLbwiq4b3JY/OYA22PHs2ALRZaCRf7at7wbHYrdk/RgS', 'morador'),
(2, 'Catador Demonstração', 'catador@ecoleta.com', '(44) 99999-2222', '$2y$12$1.As/K5ufcLbwiq4b3JY/OYA22PHs2ALRZaCRf7at7wbHYrdk/RgS', 'catador'),
(3, 'Administrador', 'admin@ecoleta.com', '(44) 99999-3333', '$2y$12$1.As/K5ufcLbwiq4b3JY/OYA22PHs2ALRZaCRf7at7wbHYrdk/RgS', 'administrador');

INSERT IGNORE INTO morador (usuario_id, endereco) VALUES (1, 'Rua das Flores, 100');
INSERT IGNORE INTO catador (usuario_id, cooperativa, area_atuacao) VALUES (2, 'Cooperativa EcoVida', 'Região central');
INSERT IGNORE INTO administrador (usuario_id, nivel_acesso) VALUES (3, 'geral');

INSERT IGNORE INTO ponto_coleta (id, nome, endereco, horario_funcionamento, ativo) VALUES
(1, 'PEV Central', 'Avenida Brasil, 500', 'Segunda a sexta, 8h às 17h', 1);
