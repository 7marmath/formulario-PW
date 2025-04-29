<?php
    $banco = new PDO('sqlite:data_base/meu_banco.db');

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];
    $genero = $_POST['gender'];
    $tipo_inscricao = $_POST['tipo'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $mensagem = $_POST['mensagem'];

    $sql = "INSERT INTO INSCRICOES(nome,email,telefone,data_nascimento,genero,tipo_inscricao,senha,mensagem) values(:nome,:email,:telefone,:data_nascimento,:genero,:tipo_inscricao,:senha,:mensagem)";
    $stmt = $banco->prepare($sql);

    $stmt->execute([
        ':nome'=>$nome,
        ':email'=>$email,
        ':telefone'=>$telefone,
        ':data_nascimento'=>$data_nascimento,
        ':genero'=>$genero,
        ':tipo_inscricao'=>$tipo_inscricao,
        ':senha'=>$senha,
        ':mensagem'=>$mensagem
    ]);

    header("Location: index.html");

?>