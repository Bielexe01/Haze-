<?php
session_start();
include('config.php'); // Conexão com o banco

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    die("Acesso negado! Apenas administradores podem acessar esta página.");
}

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    die("ID do produto não especificado.");
}

$id = $_GET['id'];

// Remove o produto do banco de dados
$sql = "DELETE FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

// Redireciona de volta para a página de gerenciamento
header("Location: admin_dashboard.php");
exit();
?>
