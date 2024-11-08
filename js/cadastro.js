




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



function verificaCpf(){
    
}