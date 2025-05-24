<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha

    // Verifica se o e-mail já existe
    $checkEmail = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $checkEmail->execute([$email]);
    
    if ($checkEmail->rowCount() > 0) {
        echo "E-mail já cadastrado! <a href='register.php'>Tente outro</a>";
        exit();
    }

    // Insere o usuário no banco de dados
    $sql = "INSERT INTO usuarios (email, senha) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$email, $senha])) {
        echo "Cadastro realizado com sucesso! <a href='login.php'>Fazer login</a>";
    } else {
        echo "Erro ao cadastrar. Tente novamente.";
    }
}
?>
