

function sairPag() {
    window.location.href = '../php/logout.php';
}



function mostraSenha(){
    if(document.getElementById("idSenha").type =="password"){
        document.getElementById("idSenha").type = "text"
        document.getElementById("idConfirmarSenha").type = "text"
        document.getElementById("idBotaoMostraSenha").value = "Esconder Senha"
    }else{
        document.getElementById("idSenha").type = "password"
        document.getElementById("idConfirmarSenha").type = "password"
        document.getElementById("idBotaoMostraSenha").value = "Mostrar Senha"
        
    }

}


function confirmarExcluirAdm(id,nome,cpf){
    if (confirm("Você tem certeza que desja excluir o seguite administrador:\nNome: " + nome + "\nCPF: " + cpf)) {
        window.location.href = "../php/deletarAdm.php?id=" + id;
    }
}

function confirmarExcluirCliente(id,nome,cpf){
    if (confirm("Você tem certeza que desja excluir o seguite cliente:\nNome: " + nome + "\nCPF: " + cpf)) {
        window.location.href = "../php/deletarCliente.php?id=" + id;
    }
}


function openEditModalAdm(id, nome, cpf) {
    document.getElementById('adminId').value = id;              
    document.getElementById('editNome').value = nome;            
    document.getElementById('editCpf').value = cpf;             
}

function openEditModalCliente(id, nome, cpf, multa) {
    document.getElementById('clienteId').value = id; 
    document.getElementById('editClienteCpf').value = cpf;             
    document.getElementById('editClienteNome').value = nome;     
    document.getElementById('editClienteMulta').value = multa;   
}


function openModalCadatrarExemplar(isbn) {
   console.log(isbn);
   document.getElementById('isbnExemplar').value = isbn;
}

function confirmarExluirLivro(isbn,titulo,editora){
    if (confirm("Você tem certeza que desja excluir o livro:\nISBN: " + isbn + "\nTitulo: " + titulo + "\nEditora: " + editora)) {
        window.location.href = "../php/deletarLivro.php?isbn=" + isbn;
    }



}

function openModalEdicaoLivro(isbn,titulo,autor,editora,edicao,genero){
    document.getElementById('isbnEditarExemplar').value = isbn; 
    document.getElementById('tituloLivroId').value = titulo;             
    document.getElementById('autorLivroId').value = autor;     
    document.getElementById('editoraLivroId').value = editora;   
    document.getElementById('edicaoLivroId').value = edicao;   
  
     let generoSelect = document.getElementById('selectGeneroEditLivroModal');
    
  
     for (let i = 0; i < generoSelect.options.length; i++) {
         if (generoSelect.options[i].value === genero) {
             generoSelect.selectedIndex = i; 
             break;
         }
     } 
}


function confirmaExcluirExemplar(id,isbn,titulo,editora){
    if (confirm("Você tem certeza que desja excluir o exemplar:\nID: " + id + "\nISBN: " + isbn + "\nTitulo: " + titulo + "\nEditora: " + editora)) {
        window.location.href = "../php/deletarExemplar.php?idExemplar=" + id;
    }
}

function renovaEmprestimo(idExemplar,idCliente,dataEmprestimo,numRenovacoes){
    if (confirm("Você term certeza que deseja renovar o emprestimo com:\nID EXEMPLAR == "+ idExemplar + "\nID CLIENTE == "+ idCliente + "\nDATA EMPRESTIMO == " + dataEmprestimo)) {
        window.location.href = "../php/renovaEmprestimo.php?idExemplar=" + idExemplar + "&idCliente="+idCliente+"&dataEmprestimo="+dataEmprestimo+"&numRenovacoes="+numRenovacoes;
    };
}
   
function entregarEmprestimo(idExemplar,idCliente,dataEmprestimo,numRenovacoes){
    if (confirm("Você term certeza que deseja entregar o emprestimo com:\nID EXEMPLAR == "+ idExemplar + "\nID CLIENTE == "+ idCliente + "\nDATA EMPRESTIMO == " + dataEmprestimo)) {
        window.location.href = "../php/entregrarEmprestimo.php?idExemplar=" + idExemplar + "&idCliente="+idCliente+"&dataEmprestimo="+dataEmprestimo+"&numRenovacoes="+numRenovacoes;
    };
}


function entregarReserva(idExemplar,idCliente,dataReserva){
    if (confirm("Você term certeza que deseja a reserva com:\nID EXEMPLAR == "+ idExemplar + "\nID CLIENTE == "+ idCliente + "\nDATA RESERVA == " + dataReserva)) {
        window.location.href = "../php/entregarReserva.php?idExemplar=" + idExemplar + "&idCliente="+idCliente+"&dataEmprestimo="+dataReserva;
    };
}


function pagamentoMultaModal(idCliente,multa){
    
    document.getElementById('clienteIdMultaId').value = idCliente;
    document.getElementById('multaId').value = multa; 
}


function abrirCadastroReservaModalCliente(idExemplar){
    document.getElementById('idExemplarReserva').value = idExemplar;
}