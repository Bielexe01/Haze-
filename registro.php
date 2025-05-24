<?php
session_start();
include('config.php'); // Conexão com o banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    if (!empty($email) && !empty($senha)) {
        // Verifica se o email já existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $erro = "Email já cadastrado!";
        } else {
            // Insere no banco sem a coluna 'nome'
            $stmt = $conn->prepare("INSERT INTO usuarios (email, senha, tipo) VALUES (?, ?, 'cliente')");
            if ($stmt->execute([$email, $senha_hash])) {
                header("Location: login.php");
                exit();
            } else {
                $erro = "Erro ao cadastrar usuário!";
            }
        }
    } else {
        $erro = "Preencha todos os campos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
        form { display: inline-block; text-align: left; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        input { width: 100%; padding: 8px; margin: 5px 0; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; }
        .erro { color: red; }
        .link-login { display: block; margin-top: 10px; color: #008CBA; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Criação de Conta</h2>
    <?php if (isset($erro)) { echo "<p class='erro'>$erro</p>"; } ?>
    <form action="registro.php" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit">Cadastrar</button>
    </form>
    <a href="login.php" class="link-login">Já tem uma conta? Faça login</a>
</body>
</html>
