

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
