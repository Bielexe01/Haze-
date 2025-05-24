<?php
session_start();
include('config.php'); // Conexão com o banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (!empty($email) && !empty($senha)) {
        // Consulta no banco
        $stmt = $conn->prepare("SELECT id, senha, tipo FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se encontrou o usuário e se a senha está correta
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            // Redirecionamento correto
            if ($usuario['tipo'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: produtos.php");
            }
            exit();
        } else {
            $erro = "Email ou senha inválidos!";
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
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
        form { display: inline-block; text-align: left; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        input { width: 100%; padding: 8px; margin: 5px 0; }
        button { width: 100%; padding: 10px; background: #800000; color: white; border: none; cursor: pointer; }
        .erro { color: red; }
        .link-criar { display: block; margin-top: 10px; color: #800000; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($erro)) { echo "<p class='erro'>$erro</p>"; } ?>
    <form action="login.php" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit">Entrar</button>
    </form>
    <a href="registro.php" class="link-criar">Criar Conta</a>
</body>
</html>
