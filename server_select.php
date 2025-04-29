<?php
session_start();


if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.html?erro=5");
    exit();
}

try {
    $banco = new PDO('sqlite:data_base/meu_banco.db');
    $banco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Validação básica
    if(empty($email) || empty($senha)) {
        header("Location: login.html?erro=1"); 
        exit();
    }

    $stmt = $banco->prepare("SELECT * FROM inscricoes WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if($usuario && password_verify($senha, $usuario['senha'])) {
        
        session_regenerate_id(true);
        
        
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_name'] = $usuario['nome'];
        $_SESSION['logged_in'] = true;

        error_log("Login bem-sucedido para: " . $email);
        
        header("Location: logado.php");
        exit();
    } else {
        header("Location: login.html?erro=2"); 
        exit();
    }
} catch(PDOException $e) {
    error_log("Erro no login: " . $e->getMessage());
    header("Location: login.html?erro=3"); 
    exit();
}
?>