<?php
session_start();


if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.html?erro=4"); 
    exit();
}

try {
    $banco = new PDO('sqlite:data_base/meu_banco.db');
    $banco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $banco->prepare("SELECT * FROM inscricoes WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$usuario) {
        session_destroy();
        header("Location: login.html?erro=6");
        exit();
    }

} catch(PDOException $e) {
    error_log("Erro ao carregar usuário: " . $e->getMessage());
    header("Location: login.html?erro=5"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Logada</title>
    <link rel="stylesheet" href="estilo/style.css">
</head>
<body>
    <header>
        <a href="https://fatecferraz.edu.br/">
            <img src="img/fateclogo.webp" alt="Logo da Fatec Ferraz de Vasconcelos">
        </a>
    </header>
    <section>
        <div class="container">
            <div class="titulo">
                <h1>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</h1>
            </div>
            
            <div class="dados-usuario">
                <h2>Seus dados cadastrais:</h2>
                <p><strong>ID:</strong> <?php echo $usuario['id']; ?></p>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($usuario['telefone']); ?></p>
                <p><strong>Data de Nascimento:</strong> 
                    <?php 
                    if(!empty($usuario['data_nascimento'])) {
                        echo date('d/m/Y', strtotime($usuario['data_nascimento']));
                    } else {
                        echo 'Não informada';
                    }
                    ?>
                </p>
                <p><strong>Gênero:</strong> <?php echo htmlspecialchars($usuario['genero']); ?></p>
                <p><strong>Tipo de Inscrição:</strong> <?php echo htmlspecialchars($usuario['tipo_inscricao']); ?></p>
                
                <a href="logout.php" class="btn-sair">Sair</a>
            </div>
        </div>
    </section>
</body>
</html>
