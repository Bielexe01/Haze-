<?php
session_start();
include('config.php');

// Verifica se é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    die("Acesso negado!");
}

// Verifica se os dados foram enviados
if (isset($_POST['id']) && isset($_POST['tipo'])) {
    $id = $_POST['id'];
    $tipo = $_POST['tipo'];

    // Atualiza o tipo do usuário
    $stmt = $conn->prepare("UPDATE usuarios SET tipo = ? WHERE id = ?");
    $stmt->execute([$tipo, $id]);
}

// Redireciona de volta para o dashboard
header("Location: dashboard.php");
exit;
?>
