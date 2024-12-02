CREATE DATABASE biblioteca;

CREATE TABLE clientes (
    idCliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50),
    cpf CHAR(11) UNIQUE,
    senha VARCHAR(100),
    telefone CHAR(12),
    multa DECIMAL(10,2) DEFAULT 0.00
);

CREATE TABLE bibliotecarios (
    idBibliotecario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50),
    cpf CHAR(11) UNIQUE,
    senha VARCHAR(100)
);


CREATE TABLE generos (
    idGenero INT AUTO_INCREMENT PRIMARY KEY,
    genero VARCHAR(50)
);


CREATE TABLE livros (
    isbn VARCHAR(14) PRIMARY KEY,
    titulo VARCHAR(50) NOT NULL,
    autor VARCHAR(50) NOT NULL,
    editora VARCHAR(50) NOT NULL,
    edicao INT NOT NULL,
    genero_id INT,
    FOREIGN KEY (genero_id) REFERENCES generos(idGenero)
);

CREATE TABLE exemplares (
    idExemplar INT AUTO_INCREMENT PRIMARY KEY, 
    isbn VARCHAR(20),
    FOREIGN KEY (isbn) REFERENCES livros(isbn)  
);


CREATE TABLE emprestimos (
    idExemplar INT,
    idCliente INT,
    dataEmprestimo DATE,
    dataEsperadaDevolucao DATE,  
    dataDevolucao DATE NULL,  
    numRenovacoes INT DEFAULT 0, 
    status CHAR(1) DEFAULT 'E',  -- Status do empréstimo (E: emprestado,D: devolvido, A:atrasado)
    PRIMARY KEY (idExemplar, idCliente, dataEmprestimo), 
    FOREIGN KEY (idExemplar) REFERENCES exemplares(idExemplar),
    FOREIGN KEY (idCliente) REFERENCES clientes(idCliente),
    INDEX (idExemplar, idCliente)  
);