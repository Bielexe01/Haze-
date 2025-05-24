<?php
session_start();
include('config.php');

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produto_id = $_POST['produto_id'];
    $usuario_id = $_POST['usuario_id'];
    $estrelas = $_POST['estrelas'];
    $comentario = $_POST['comentario'];

    // Insere a avaliação no banco de dados
    $stmt = $conn->prepare("INSERT INTO avaliacao (produto_id, usuario_id, estrelas, comentario) VALUES (?, ?, ?, ?)");
    $stmt->execute([$produto_id, $usuario_id, $estrelas, $comentario]);

    echo "Obrigado pela sua avaliação!";
}
?>
