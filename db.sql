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
    dataDaUltimaMulta DATE,  
    dataDevolucao DATE NULL,  
    numRenovacoes INT DEFAULT 0, 
    status CHAR(1) DEFAULT 'E',  -- Status do empréstimo (E: emprestado,D: devolvido, A:atrasado)
    PRIMARY KEY (idExemplar, idCliente, dataEmprestimo), 
    FOREIGN KEY (idExemplar) REFERENCES exemplares(idExemplar),
    FOREIGN KEY (idCliente) REFERENCES clientes(idCliente),
    INDEX (idExemplar, idCliente)  
);



DELIMITER $$

CREATE PROCEDURE VerificarEmprestimosAtrasados()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE v_idExemplar INT;
    DECLARE v_idCliente INT;
    DECLARE v_dataEmprestimo DATE;
    DECLARE v_dataEsperada DATE;
    DECLARE v_dataDevolucao DATE;
    DECLARE v_numRenovacoes INT;
    DECLARE v_status CHAR(1);
    DECLARE v_dataUltimaMulta DATE;
    DECLARE v_dataAtual DATE;
    DECLARE v_multa DECIMAL(10,2);
    -- cursor
    DECLARE cur CURSOR FOR 
        SELECT idExemplar, idCliente, dataEmprestimo, dataEsperadaDevolucao, dataDevolucao, numRenovacoes, status, dataDaUltimaMulta
        FROM emprestimos
        WHERE status = 'E' OR (status = 'A' AND dataDevolucao IS NULL);

    -- had
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO v_idExemplar, v_idCliente, v_dataEmprestimo, v_dataEsperada, v_dataDevolucao, v_numRenovacoes, v_status, v_dataUltimaMulta;

        IF done THEN
            LEAVE read_loop;
        END IF;

        SET v_dataAtual = CURDATE();

        
        IF v_dataUltimaMulta < v_dataAtual THEN
           
            
            SET v_multa = DATEDIFF(v_dataAtual, v_dataUltimaMulta) * 1.00;  -- 1 real por dia
           
            UPDATE emprestimos 
            SET status = 'A', 
                dataDaUltimaMulta = v_dataAtual
            WHERE idExemplar = v_idExemplar 
              AND idCliente = v_idCliente
              AND dataEmprestimo = v_dataEmprestimo;

     
            UPDATE clientes
            SET multa = multa + v_multa
            WHERE idCliente = v_idCliente;
        END IF;

    END LOOP;

    CLOSE cur;
END $$

DELIMITER ;


CREATE TABLE reservas (
    idExemplar INT,
    idCliente INT,
    dataReserva DATE,
    dataFinalReserva DATE,  
    status CHAR(1) DEFAULT 'P',  -- Status do empréstimo (P: pendente,E: entregue, X:expirado)
    PRIMARY KEY (idExemplar, idCliente, dataReserva), 
    FOREIGN KEY (idExemplar) REFERENCES exemplares(idExemplar),
    FOREIGN KEY (idCliente) REFERENCES clientes(idCliente),
    INDEX (idExemplar, idCliente)  
);


INSERT INTO bibliotecarios (nome,cpf,senha) VALUES ('adm1','123','123');

INSERT INTO clientes (nome, cpf, senha, telefone, multa) VALUES
('teste', '123', '123', '11997654321', 0.00),
('João Silva', '12345678901', 'senha123', '11987654321', 0.00),
('Maria Oliveira', '23456789012', 'senha456', '11976543210', 50.00),
('Pedro Souza', '34567890123', 'senha789', '11965432109', 10.00),
('Ana Costa', '45678901234', 'senha101', '11954321098', 0.00),
('Carlos Pereira', '56789012345', 'senha102', '11943210987', 20.00);


INSERT INTO generos (genero) VALUES 
('Acao'),
('Aventura'),
('Misterio'),
('Ficcao'),
('Fantasia'),
('Drama');


INSERT INTO livros (isbn, titulo, autor, editora, edicao, genero_id) VALUES
('9780261102736', 'Silmarilion', 'Tolkien', 'HarperCollins', 1, 5), 
('9788535901771', 'Cores Proibidas', 'Yukio Mishima', 'Companhia Das Letras', 1, 6), 
('9788560281527', 'Norwegian Wood', 'Haruki Murakami', 'Alfaguara', 3, 5), 
('9786555942149', 'Ping Pong - Vol 1', 'Tayo Matsumoto', 'Editora Jbc ', 1, 1), 
('9788551308431', 'Caixa Patriotismo E Quem Sao Mishimas?', 'Yukio Mishima', 'Autentica Editora', 1, 6); 

INSERT INTO exemplares (isbn) VALUES
('9780261102736'),
('9780261102736'),
('9780261102736'),
('9788535901771'),
('9788560281527'),
('9788560281527'),
('9786555942149'),
('9786555942149'),
('9788551308431');


--INSERT INTO emprestimos (idExemplar, idCliente, dataEmprestimo, dataEsperadaDevolucao,dataDaUltimaMulta) VALUES (10, 1, '2024-06-01','2024-06-07','2024-06-07');
--UPDATE clientes SET multa =0;