<?php
session_start();
include('config.php'); // Conexão com o banco

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    die("Você precisa estar logado para ver seu carrinho.");
}

$usuario_id = $_SESSION['usuario_id'];

// Busca os produtos no carrinho
$stmt = $conn->prepare("SELECT c.id, p.nome, p.preco, c.quantidade, c.cor, c.tamanho 
                        FROM carrinho c 
                        JOIN produtos p ON c.produto_id = p.id 
                        WHERE c.usuario_id = ?");
$stmt->execute([$usuario_id]);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Função para buscar as imagens de um produto
function getImagensProduto($produto_id, $conn) {
    $stmt = $conn->prepare("SELECT imagem FROM produto_imagens WHERE produto_id = ?");
    $stmt->execute([$produto_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f8f8f8;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        td img {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .total {
            text-align: right;
            font-size: 1.2em;
            margin-top: 20px;
            color: #333;
        }
        .checkout {
            display: block;
            text-align: center;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #800000;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }
        .checkout:hover {
            background-color: #800000;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Seu Carrinho</h2>
    <table>
        <tr>
            <th>Imagem</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Cor</th>
            <th>Tamanho</th>
            <th>Total</th>
            <th>Ações</th>
        </tr>
        <?php 
        $total_geral = 0; // Inicializa o total geral
        foreach ($itens as $item) {
            // Busca as imagens do produto
            $imagens = getImagensProduto($item['id'], $conn);
            // Calcula o total de cada item
            $total = $item['preco'] * $item['quantidade'];
            $total_geral += $total; // Atualiza o total geral
        ?>
        <tr>
            <td>
                <?php 
                foreach ($imagens as $imagem) {
                    echo '<img src="' . $imagem['imagem'] . '" alt="Imagem do produto">';
                }
                ?>
            </td>
            <td><?= $item['nome'] ?></td>
            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
            <td><?= $item['quantidade'] ?></td>
            <td><?= $item['cor'] ?></td>
            <td><?= $item['tamanho'] ?></td>
            <td>R$ <?= number_format($total, 2, ',', '.') ?></td>
            <td>
                <a href="remover_carrinho.php?id=<?= $item['id'] ?>">Remover</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div class="total">
        <strong>Total: R$ <?= number_format($total_geral, 2, ',', '.') ?></strong>
    </div>
    <a href="checkout.php" class="checkout">Finalizar Compra</a>
</div>

</body>
</html>
