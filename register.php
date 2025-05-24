<!DOCTYPE html>
<html>
<head>
    <title>Cadastro</title>
</head>
<body>
    <h2>Criar uma Conta</h2>
    <form action="register_process.php" method="POST">
        <label>E-mail:</label>
        <input type="email" name="email" required><br>

        <label>Senha:</label>
        <input type="password" name="senha" required><br>

        <button type="submit">Cadastrar</button>
    </form>

    <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
</body>
</html>
