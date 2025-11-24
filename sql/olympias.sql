-- ========================
-- BANCO DE DADOS: OLYMPIAS
-- ========================

DROP DATABASE IF EXISTS olympias;
CREATE DATABASE olympias
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE olympias;

-- ==================
-- 1. TABELA USUÁRIOS
-- ==================

DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('aluno','funcionario','admin') DEFAULT 'aluno',

    nome VARCHAR(100) NOT NULL,
    sobrenome VARCHAR(100),

    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,

    cpf CHAR(11) UNIQUE,
    telefone VARCHAR(20),

    card_last4 VARCHAR(4),
    payment_status ENUM('pendente','pago') DEFAULT 'pendente',

    data_nascimento DATE,
    genero ENUM('masculino','feminino','outro') DEFAULT 'outro',

    cep VARCHAR(20),
    endereco VARCHAR(255),
    numero VARCHAR(10),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(50),
    complemento VARCHAR(100),

    unidade INT,
    plano VARCHAR(50),

    foto_perfil VARCHAR(255),

    ativo TINYINT(1) DEFAULT 1,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    email_confirmado TINYINT(1) DEFAULT 0,
    confirm_token VARCHAR(255),
    token_expira DATETIME,

    data_inicio DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================
-- 2. TABELA ACADEMIAS
-- ===================

DROP TABLE IF EXISTS academias;

CREATE TABLE academias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    imagem_url VARCHAR(255) NOT NULL,
    map_url VARCHAR(600),
    telefone VARCHAR(20),
    horario_funcionamento VARCHAR(150),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- INSERÇÃO DAS 6 UNIDADES
INSERT INTO academias (nome, endereco, cidade, estado, imagem_url, map_url, telefone, horario_funcionamento)
VALUES
('OLYMPIAS João Pessoa - Manaíra',
 'Av. Gov. Flávio Ribeiro Coutinho, 805 - Manaíra',
 'João Pessoa', 'PB',
 'assets/img/unidades/pb_manaira.jpg',
 'https://www.google.com/maps/embed?...',
 '(83) 98888-0000',
 'Seg-Sex: 6h às 22h, Sáb: 8h às 18h'),

('OLYMPIAS João Pessoa - Tambaú',
 'Av. Almirante Tamandaré, 180 - Tambaú',
 'João Pessoa', 'PB',
 'assets/img/unidades/pb_tambau.jpg',
 'https://www.google.com/maps/embed?...',
 '(83) 98888-1111',
 'Seg-Sex: 6h às 22h, Sáb: 8h às 18h'),

('OLYMPIAS Campina Grande - Centro',
 'Rua Marechal Floriano Peixoto, 122 - Centro',
 'Campina Grande', 'PB',
 'assets/img/unidades/pb_campina.jpg',
 'https://www.google.com/maps/embed?...',
 '(83) 98888-2222',
 'Seg-Sex: 6h às 22h, Sáb: 8h às 18h'),

('OLYMPIAS Patos - Centro',
 'Rua Epitácio Pessoa, 450 - Centro',
 'Patos', 'PB',
 'assets/img/unidades/pb_patos.jpg',
 'https://www.google.com/maps/embed?...',
 '(83) 98888-3333',
 'Seg-Sex: 6h às 22h, Sáb: 8h às 18h'),

('OLYMPIAS Cabedelo - Praia do Jacaré',
 'Av. Cabo Branco, 500 - Praia do Jacaré',
 'Cabedelo', 'PB',
 'assets/img/unidades/pb_cabedelo.jpg',
 'https://www.google.com/maps/embed?...',
 '(83) 98888-4444',
 'Seg-Sex: 6h às 22h, Sáb: 8h às 18h'),

('OLYMPIAS João Pessoa – Tambaú Sul',
 'Av. Almirante Tamandaré, 2200 – Tambaú Sul – João Pessoa',
 'João Pessoa', 'PB',
 'assets/img/unidades/pb_joaopessoa_tambau_sul.jpg',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m...',
 '(83) 3333-4567',
 'Seg a Sex: 5h às 23h | Sáb e Dom: 6h às 18h');

-- ==================
-- 3. TABELA PARCELAS
-- ==================

DROP TABLE IF EXISTS parcelas;

CREATE TABLE parcelas (
    id INT auto_increment PRIMARY KEY,
    usuario_id INT NOT NULL,
    numero INT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    vencimento DATE NOT NULL,
    status ENUM('pendente','pago') DEFAULT 'pendente',
    pago_em DATETIME DEFAULT NULL,

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================
-- 4. TABELA MENSAGENS
-- ===================

DROP TABLE IF EXISTS mensagens;

CREATE TABLE mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remetente_id INT NOT NULL,
    destinatario_id INT NOT NULL,
    assunto VARCHAR(150),
    corpo TEXT,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lida TINYINT(1) DEFAULT 0,

    FOREIGN KEY (remetente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (destinatario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============
-- FIM DO SCRIPT
-- =============