<?php
session_start();
include('config.php'); // Conexão com o banco

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    die("Você precisa estar logado para finalizar a compra.");
}

$usuario_id = $_SESSION['usuario_id'];

// Verifica se o carrinho não está vazio
$stmt = $conn->prepare("SELECT c.id, p.nome, p.preco, c.quantidade 
                        FROM carrinho c 
                        JOIN produtos p ON c.produto_id = p.id 
                        WHERE c.usuario_id = ?");
$stmt->execute([$usuario_id]);
$itens_carrinho = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($itens_carrinho) == 0) {
    die("Erro: O carrinho está vazio.");
}

// Recebe o token do Stripe
if (!isset($_POST['stripeToken'])) {
    die('Erro: O token do Stripe não foi enviado.');
}

$token = $_POST['stripeToken'];

// Calcula o total da compra
$total = 0;
foreach ($itens_carrinho as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

// Criação da cobrança com o Stripe
try {
    // Criação da cobrança
    $charge = \Stripe\Charge::create([
        'amount' => $total * 100,  // O valor total da compra em centavos
        'currency' => 'brl',
        'description' => 'Compra na Academia Store',
        'source' => $token,
    ]);

    // Cria o pedido no banco de dados
    $stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)");
    if ($stmt->execute([$usuario_id, $total])) {
        $pedido_id = $conn->lastInsertId();  // Armazena o ID do pedido
        // Atualiza o status do pedido para 'Pago'
        $stmt_update = $conn->prepare("UPDATE pedidos SET status = 'Pago' WHERE id = ?");
        $stmt_update->execute([$pedido_id]);

        // Limpa o carrinho após finalizar a compra
        $stmt_delete = $conn->prepare("DELETE FROM carrinho WHERE usuario_id = ?");
        $stmt_delete->execute([$usuario_id]);

        echo "Compra finalizada com sucesso! <a href='produtos.php'>Voltar para a loja</a>";
    } else {
        echo "Erro ao criar pedido.";
    }
} catch (\Stripe\Exception\CardException $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>
