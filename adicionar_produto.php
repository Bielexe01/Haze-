<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('config.php');
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $categoria = $_POST['categoria'];
    $imagens = $_FILES['imagens'];

    $imagens_nomes = [];
    foreach ($imagens['name'] as $key => $nome_imagem) {
        $temp_nome = $imagens['tmp_name'][$key];
        $caminho = 'img/' . $nome_imagem;
        if (move_uploaded_file($temp_nome, $caminho)) {
            $imagens_nomes[] = $nome_imagem;
        }
    }

    $imagens_str = implode(',', $imagens_nomes);
    
    $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, imagem, categoria) 
            VALUES (:nome, :descricao, :preco, :estoque, :imagem, :categoria)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':preco', $preco);
    $stmt->bindValue(':estoque', $estoque);
    $stmt->bindValue(':imagem', $imagens_str);
    $stmt->bindValue(':categoria', $categoria);
    $stmt->execute();
    
    header('Location: admin_dashboard.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Produto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
        }
        label {
            margin-top: 10px;
            display: block;
            font-weight: bold;
        }
        input, textarea, select, button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        #modalProduto {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
        }
        .modal-content.fullscreen {
            background: white;
            margin: 5% auto;
            padding: 20px;
            width: 80%;
            max-height: 90%;
            overflow-y: auto;
            border-radius: 10px;
            position: relative;
        }
        .fechar {
            position: absolute;
            top: 10px; right: 20px;
            font-size: 28px;
            cursor: pointer;
        }
        .produto-modal-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        .imagem-produto-modal {
            flex: 1;
            min-width: 250px;
        }
        .carrossel img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 10px;
        }
        .detalhes-produto-modal {
            flex: 2;
            min-width: 250px;
        }
        .tamanho-btn {
            margin: 5px;
            padding: 8px 12px;
            border: 1px solid #ccc;
            background: white;
            cursor: pointer;
        }
        .avaliacao-estrelas input {
            display: none;
        }
        .avaliacao-estrelas label {
            font-size: 20px;
            color: gray;
            cursor: pointer;
        }
        .avaliacao-estrelas input:checked ~ label {
            color: gold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Adicionar Produto</h2>
        <form action="adicionar_produto.php" method="POST" enctype="multipart/form-data">
            <label>Nome:</label>
            <input type="text" name="nome" required>

            <label>Descrição:</label>
            <textarea name="descricao"></textarea>

            <label>Preço:</label>
            <input type="number" name="preco" step="0.01" required>

            <label>Estoque:</label>
            <input type="number" name="estoque" required>

            <label>Categoria:</label>
            <select name="categoria" required>
                <option value="Feminino">Feminino</option>
                <option value="Short Feminino">Feminino - Shorts</option>
                <option value="Camisa Feminino">Feminino - Camisas</option>
                <option value="Moleton Feminino">Feminino - Moletons</option>
                <option value="Masculino">Masculino</option>
                <option value="Short Masculino">Masculino - Shorts</option>
                <option value="Camisa Masculina">Masculino - Camisas</option>
                <option value="Moleton Masculino">Masculino - Moletons</option>
                <option value="Suplementos">Suplementos</option>
            </select>

            <label>Imagens:</label>
            <input type="file" name="imagens[]" multiple required>

            <button type="button" onclick="mostrarPreview()">Visualizar Produto</button>
            <button type="submit">Adicionar</button>
        </form>
    </div>

    <!-- Modal -->
    <div id="modalProduto" class="modal">
        <div class="modal-content fullscreen">
            <span class="fechar" onclick="fecharModal()">&times;</span>
            <h2 id="modalNome"></h2>
            <div class="produto-modal-container">
                <div class="imagem-produto-modal">
                    <div class="carrossel" id="carrosselImagens"></div>
                    <div class="carrossel-navegacao">
                        <button class="seta seta-esquerda">&#10094;</button>
                        <button class="seta seta-direita">&#10095;</button>
                    </div>
                </div>
                <div class="detalhes-produto-modal">
                    <p id="modalDescricao"></p>
                    <p>Preço: R$ <span id="modalPreco"></span></p>

                    <div class="tamanho-opcoes">
                        <label>Tamanho:</label>
                        <button type="button" class="tamanho-btn">P</button>
                        <button type="button" class="tamanho-btn">M</button>
                        <button type="button" class="tamanho-btn">G</button>
                    </div>

                    <form action="#" method="POST">
                        <input type="number" name="quantidade" value="1" min="1">
                        <button type="submit">Adicionar ao Carrinho</button>
                    </form>

                    <div id="avaliacoes">
                        <h3>Avaliações</h3>
                        <div id="listaAvaliacoes"></div>
                        <form action="#" method="POST">
                            <div class="avaliacao-estrelas">
                                <input type="radio" name="estrelas" value="5" id="estrela5"><label for="estrela5">★</label>
                                <input type="radio" name="estrelas" value="4" id="estrela4"><label for="estrela4">★</label>
                                <input type="radio" name="estrelas" value="3" id="estrela3"><label for="estrela3">★</label>
                                <input type="radio" name="estrelas" value="2" id="estrela2"><label for="estrela2">★</label>
                                <input type="radio" name="estrelas" value="1" id="estrela1"><label for="estrela1">★</label>
                            </div>
                            <textarea name="comentario" placeholder="Deixe sua avaliação"></textarea>
                            <button type="submit">Enviar Avaliação</button>
                        </form>
                    </div>
                    <br>
                    <button onclick="fecharModal()">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function mostrarPreview() {
            const nome = document.querySelector('input[name="nome"]').value;
            const descricao = document.querySelector('textarea[name="descricao"]').value;
            const preco = document.querySelector('input[name="preco"]').value;
            const imagens = document.querySelector('input[name="imagens[]"]').files;

            document.getElementById('modalNome').textContent = nome;
            document.getElementById('modalDescricao').textContent = descricao;
            document.getElementById('modalPreco').textContent = parseFloat(preco).toFixed(2);

            const carrossel = document.getElementById('carrosselImagens');
            carrossel.innerHTML = '';
            Array.from(imagens).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    carrossel.appendChild(img);
                };
                reader.readAsDataURL(file);
            });

            document.getElementById('modalProduto').style.display = 'block';
        }

        function fecharModal() {
            document.getElementById('modalProduto').style.display = 'none';
        }
    </script>
</body>
</html>
