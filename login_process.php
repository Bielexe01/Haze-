<?php
session_start();
include('config.php');

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($senha, $user['senha'])) {
    $_SESSION['user_id'] = $user['id'];
    header('Location: admin_dashboard.php');
} else {
    echo "Login falhou.";
}
?>