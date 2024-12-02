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


