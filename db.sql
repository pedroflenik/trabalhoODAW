CREATE DATABASE biblioteca;

CREATE TABLE clientes (
    idCliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50),
    cpf CHAR(11) UNIQUE,
    senha VARCHAR(100),
    telefone CHAR(12),
    multa DECIMAL(10,2) DEFAULT 0.00
);

