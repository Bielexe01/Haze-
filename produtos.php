<?php
session_start();
include('config.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Busca os produtos organizados por categorias
$stmt = $conn->prepare("SELECT * FROM produtos ORDER BY categoria, subcategoria");
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organiza os produtos em um array por categoria e subcategoria
$categorias = [];
foreach ($produtos as $produto) {
    $categorias[$produto['categoria']][$produto['subcategoria']][] = $produto;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <script src="https://identity.netlify.com/v1/netlify-identity-widget.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js"></script>
    <title>Produtos</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .menu { margin-bottom: 20px; }
        .menu a { margin: 0 10px; text-decoration: none; font-weight: bold; color: #008CBA; }
        
        .navbar {
    position: fixed;
    width: 100%;
    padding: 30px 0;
    font-family: 'Ubuntu', sans-serif;
    z-index: 999;
    transition: all 0.3s ease;
    background: rgb(0, 0, 0); /* Torna a navbar parcialmente transparente */
    height: 48px;
    
    
}

.navbar.sticky {
    background: #800000; /* Mantém a transparência ao rolar */
    padding: 15px 0;
    
}

.navbar .max-width{
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.navbar .logo a{
  color: #fff;
  font-size: 35px;
  font-weight: 500;
}
.navbar .logo a span {
  color: #800000;
  transition: all 0.3s ease;
  
}
.navbar.sticky .logo a span{
  color: #fff;
}
.navbar .menu li{
  list-style: none;
  display: inline-block;
}
.navbar .menu li a{
  color: #fff;
  font-size: 18px;
  font-weight: 500;
  margin-left: 25px;
  transition: color 0.3s ease;
}
.navbar .menu li a:hover{
  color: #800000;
}
.navbar.sticky .menu li a:hover{
  color: #fff;
}
.owl-carousel .item {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fff;
    border-radius: 10px;
    border: 100px;
    
}

   .seta {
            cursor: pointer;
            font-size: 20px;
            margin: 10px;
            user-select: none;
        }

.owl-carousel img {
    height: 400px;
    border-radius: 5px;
    width: 2500px;
}
.produto {
            display: inline-block;
            margin: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            width: 200px;
        }
        .produto img {
            width: 170px;
            height: 170px;
            object-fit: cover;
        }
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
    background: white;
 
    padding: 20px;
    width: 50%;
    max-height: 100%; /* Adicione isso para garantir que o modal não ultrapasse a tela */
    width: 100%;
    overflow-y: auto; /* Adicione isso para permitir rolagem se necessário */
    text-align: center;
    position: relative;
    border-radius: 10px;
}

        .fechar {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 20px;
            cursor: pointer;
        }
        .carrossel {
            display: flex;
            overflow: hidden;
            width: 100%;
            justify-content: center;
            align-items: center;
        }
        .carrossel img {
            max-width: 300px;
            height: auto;
            display: none;
        }
        .carrossel img.ativa {
            display: block;
            left:10px;
        }
    
        .modal-content label{
            color: black;
        }

        .tamanho-opcoes {
    display: flex;
    justify-content: center;
    gap: 10px;
margin-top: 10px;
}

.tamanho-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #f0f0f0;
    border: 2px solid #ccc;
    font-size: 16px;
    color: #333;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: background-color 0.3s;
}

.tamanho-btn:hover {
    background-color: #ddd;
}

.tamanho-btn.selected {
    background-color: #800000;
    color: white;
}

.cor-opcoes {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 10px;
}

.cor-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #ccc;
    cursor: pointer;
    transition: transform 0.3s, border-color 0.3s;
}

.cor-btn:hover {
    transform: scale(1.1);
    border-color: #333;
}

.cor-btn.selected {
    border-color: #000;
}

/* Modal Container */
.produto-modal-container {
    display: flex;
    gap: 20px; /* Espaço entre a imagem e os detalhes */
    align-items: flex-start;
}

/* Imagem do Produto */
.imagem-produto-modal {
    flex: 1;
    max-width: 450px; /* Defina o tamanho da imagem conforme necessário */
    margin-bottom: 20px;
}

.carrossel img {
    width: 100%; /* Imagem vai preencher todo o espaço do container */
    height: 50%;
}

/* Detalhes do Produto */
.detalhes-produto-modal {
    flex: 2;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}
 .detalhes-produto-modal p{
    font-size: 22px;
 }
/* Opções de Cor e Tamanho */
.tamanho-opcoes, .cor-opcoes {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.tamanho-btn, .cor-btn {
    padding: 10px 20px;
    border-radius: 45%;
    cursor: pointer;
    width: 60px;
    height: 55px;
}

.cor-btn {
    width: 40;
    height: 40px;
    border-radius: 50%;
}

.cor-btn.selected {
    border-color: #000;
}

.tamanho-btn:hover, .cor-btn:hover {
    transform: scale(1.1);
}

/* Avaliações e Formas de Pagamento */
#avaliacoes, #formasPagamento {
    margin-top: 20px;
}

textarea {
    width: 100%;
    height: 100px;
}

button.seta {
    background-color: #800000;
    border: none;
    font-size: 30px;
    cursor: pointer;
    margin: 10px;
    width:50px; 
    heigth:40px;
}

button.fechar {
    background-color: transparent;
    border: none;
    font-size: 40px;
    color: #000;
    cursor: pointer;
}
 
#modalNome {
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    margin: 0 auto;
    font-size: 28px; /* Ajuste conforme quiser */
    font-weight: bold;
}


.espaco-navbar {
    padding-top: 110px; /* espaço suficiente para empurrar o conteúdo para baixo da navbar */
}

    </style>
</head>
<body>
<div class="scroll-up-btn">
        <ion-icon name="chevron-up-outline"></ion-icon>
    </div>
        <div class="navbar">
            <div class="max-width">
                <div class="logo"><a href="#">HAZE<span>SUPLEMENTOS</span></a></div><div class="ftl"></div>
                <ul class="menu">
                    <nav class="nav">
                        <button class="hamburger"></button>
                        <ul>
                           
                            <li class="dropdown">
                                <a>Roupas</a>
                
                                <div class="dropdown-menu">
                                <div class="menu">
        <a href="#feminino">Feminino</a>
        <a href="#masculino">Masculino</a>
        <a href="#suplementos">Suplementos</a>
    </div>
                     
                            
                            
                            <li>
                                <a href="vizualizar_carrinho.php">Carrinho</a>
                            </li>

                            <li>                                              
                                <a href="login.php">Administrador</a></li>


                            </li>
                        
                            <li>
                                <a href="https://wa.me/5516996349439">Minhas Compras</a>
                            </li>
                        </ul>
                  
                    </nav></nav>
                <div class="menu-btn">
                     <ion-icon name="menu-outline"></ion-icon>
                </div>
            </div>
        </div>

   
       <section class="home" id="home">

                <div class="home-content">
                   
                </div>
            </div> 
            <div class="espaco-navbar">
            <!-- Carrossel -->
    <div class="owl-carousel owl-theme">
        <div class="item">
            <img src="carrossel2.png" alt="Imagem 1">
        </div>
        <div class="item">
            <img src="carrosel.jpg" alt="Imagem 2">
        </div>
        <div class="item">
            <img src="carrossel2.png" alt="Imagem 3">
        </div>
        <!-- Adicione mais itens conforme necessário -->
    </div>
            </div>
  
        </section>
       
        <section id="section2"></section>
        <div class="typing-2">
               <span class="typing-2"></span>
            </div>
       

    <!-- Exibe produtos por categoria -->
    <?php foreach ($categorias as $categoria => $subcategorias) { ?>
        <h2 id="<?php echo strtolower($categoria); ?>"><?php echo ucfirst($categoria); ?></h2>

        <?php foreach ($subcategorias as $subcategoria => $produtos) { ?>
            <h3><?php echo ucfirst($subcategoria); ?></h3>
            <div>
                <?php foreach ($produtos as $produto) { ?>
                    <div class="produto" onclick="abrirModal('<?php echo $produto['id']; ?>', '<?php echo htmlspecialchars($produto['nome']); ?>', '<?php echo htmlspecialchars($produto['descricao']); ?>', '<?php echo $produto['preco']; ?>', '<?php echo $produto['imagem']; ?>')">
                    <img src="<?php echo !empty($produto['imagem']) ? explode(',', $produto['imagem'])[0] : 'sem-imagem.png'; ?>">
                    <h4><?php echo htmlspecialchars($produto['nome']); ?></h4>
                        <p>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>

  <!-- Modal para detalhes do produto -->
<div id="modalProduto" class="modal">
    <div class="modal-content fullscreen">
        <span class="fechar" onclick="fecharModal()">&times;</span>
        <h2 id="modalNome"></h2>

        <!-- Container para a imagem e os detalhes -->
        <div class="produto-modal-container">
            <!-- Imagem do Produto -->
            <div class="imagem-produto-modal">
                <div class="carrossel" id="carrosselImagens"></div>
    <div class="carrossel-navegacao">
        <button class="seta seta-esquerda">&#10094;</button>
        <button class="seta seta-direita">&#10095;</button>
    </div>
            </div>

            <!-- Detalhes do Produto -->
            <div class="detalhes-produto-modal">
                <p id="modalDescricao"></p>
                <p>Preço: R$ <span id="modalPreco"></span></p>

                <br><br>                
                <div class="tamanho-opcoes">
                <label>Tamanho:</label>
                    <button type="button" class="tamanho-btn" data-tamanho="P">P</button>
                    <button type="button" class="tamanho-btn" data-tamanho="M">M</button>
                    <button type="button" class="tamanho-btn" data-tamanho="G">G</button>
                </div>
                <br><br>
                <form action="carrinho.php" method="POST">
                    <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                    <input type="hidden" name="cor" id="inputCor">
                    <input type="hidden" name="tamanho" id="inputTamanho">
                    <input type="number" name="quantidade" value="1" min="1">
                    <button type="submit">Adicionar ao Carrinho</button>
                </form> 
		    <!-- Avaliações do Produto -->
                <div id="avaliacoes">
                    <h3>Avaliações</h3>
                    <div id="listaAvaliacoes"></div>
                    <form action="avaliar.php" method="POST">
                    <div class="avaliacao-estrelas">
    <input type="radio" name="estrelas" value="5" id="estrela5"><label for="estrela5">★</label>
    <input type="radio" name="estrelas" value="4" id="estrela4"><label for="estrela4">★</label>
    <input type="radio" name="estrelas" value="3" id="estrela3"><label for="estrela3">★</label>
    <input type="radio" name="estrelas" value="2" id="estrela2"><label for="estrela2">★</label>
    <input type="radio" name="estrelas" value="1" id="estrela1"><label for="estrela1">★</label>
</div>

                        <textarea name="comentario" placeholder="Deixe sua avaliação" required></textarea>
                        <input type="hidden" name="produto_id" id="modalProdutoId">
                        <button type="submit">Enviar Avaliação</button>
                    </form>
                </div>
                <br>
    
                
                <button onclick="fecharModal()">Fechar</button>
            </div>
        </div>
    </div>
</div>


<style>
.fullscreen {
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
    background: white;
    overflow-y: auto;
    text-align: center;
}

.avaliacao-estrelas {
    display: flex;
    justify-content: center;
    gap: 5px;
    flex-direction: row-reverse; /* Isso inverte a ordem visual */
}

.avaliacao-estrelas input {
    display: none;

}

.avaliacao-estrelas label {
    font-size: 24px;
    color: #ccc;
    cursor: pointer;
}

.avaliacao-estrelas input:checked ~ label {
    color: gold;
}
</style>

    

    <script>
        document.querySelectorAll('.cor-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        // Remove a classe 'selected' de todos os botões
        document.querySelectorAll('.cor-btn').forEach(function(btn) {
            btn.classList.remove('selected');
        });

        // Adiciona a classe 'selected' ao botão clicado
        this.classList.add('selected');

        // Define o valor da cor no campo oculto (opcional, se necessário)
        document.getElementById("inputCor").value = this.getAttribute('data-cor');
    });
});
 

        document.querySelectorAll('.tamanho-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        // Remove a classe 'selected' de todos os botões
        document.querySelectorAll('.tamanho-btn').forEach(function(btn) {
            btn.classList.remove('selected');
        });

        // Adiciona a classe 'selected' ao botão clicado
        this.classList.add('selected');

        // Define o valor do tamanho no campo oculto (opcional, se necessário)
        document.getElementById("inputTamanho").value = this.getAttribute('data-tamanho');
    });
});


     function abrirModal(id, nome, descricao, preco, imagens) {
    document.getElementById("modalProdutoId").value = id;
    document.getElementById("modalNome").innerText = nome;
    document.getElementById("modalDescricao").innerText = descricao;
    document.getElementById("modalPreco").innerText = preco;
    

    // Limpar o carrossel existente
    const carrosselImagens = document.getElementById("carrosselImagens");
    carrosselImagens.innerHTML = '';

    // Dividir as imagens se existirem várias
    console.log("Imagens recebidas:", imagens);
let imagensArray = imagens ? imagens.split(',') : [];

    
    // Adicionar cada imagem ao carrossel
    imagensArray.forEach(function(imagem, index) {
        const imgElement = document.createElement("img");
        imgElement.src = imagem.trim(); // Remover espaços extras
        imgElement.classList.add('carrossel-imagem');
        
        // Adicionar a classe 'ativa' apenas à primeira imagem
        if (index === 0) {
            imgElement.classList.add('ativa');
        }

        carrosselImagens.appendChild(imgElement);
    });

    // Exibir o modal
    document.getElementById("modalProduto").style.display = "block";
}

// Controle das setas do carrossel
let imagemIndex = 0;

document.querySelector('.seta-direita').addEventListener('click', function() {
    const imagens = document.querySelectorAll('.carrossel img');
    imagens[imagemIndex].classList.remove('ativa');
    imagemIndex = (imagemIndex + 1) % imagens.length;
    imagens[imagemIndex].classList.add('ativa');
});

document.querySelector('.seta-esquerda').addEventListener('click', function() {
    const imagens = document.querySelectorAll('.carrossel img');
    imagens[imagemIndex].classList.remove('ativa');
    imagemIndex = (imagemIndex - 1 + imagens.length) % imagens.length;
    imagens[imagemIndex].classList.add('ativa');
});

function fecharModal() {
    document.getElementById("modalProduto").style.display = "none";
}


        document.getElementById("cor").addEventListener("change", function() {
            document.getElementById("inputCor").value = this.value;
        });

        document.getElementById("tamanho").addEventListener("change", function() {
            document.getElementById("inputTamanho").value = this.value;
        });

        // Adiciona a classe sticky ao rolar
window.onscroll = function() {
    var navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) { // Defina o valor que funciona para você
        navbar.classList.add('sticky');
    } else {
        navbar.classList.remove('sticky');
    }
};

let indexImagem = 0;
function navegarCarrossel(direction) {
    const imagens = document.querySelectorAll(".carrossel img");
    if (imagens.length > 0) {
        // Remover a classe "ativa" de todas as imagens
        imagens.forEach(function(imagem) {
            imagem.classList.remove("ativa");
        });

        // Atualiza o índice da imagem com base na direção
        indexImagem = (indexImagem + direction + imagens.length) % imagens.length;

        // Adiciona a classe "ativa" à imagem atual
        imagens[indexImagem].classList.add("ativa");
    }
}

// Chame as funções de navegação
document.querySelector(".seta-esquerda").addEventListener("click", function() {
    navegarCarrossel(-1);
});

document.querySelector(".seta-direita").addEventListener("click", function() {
    navegarCarrossel(1);
});


    </script>
    
    <script>
    $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
            loop: true,          // Faz o carrossel voltar ao início quando chega ao final
            margin: 10,          // Espaçamento entre os itens
            nav: false,           // Adiciona navegação
            dots: true,          // Exibe os pontos de navegação
            autoplay: true,      // Ativa autoplay
            autoplayTimeout: 2000, // Tempo entre as transições (3000ms = 3 segundos)
            autoplayHoverPause: true, // Pausa o autoplay ao passar o mouse
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });
    });
</script>

     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
     <footer>
<section id="fot"></section>
   <footer class="footer">
    <div class="containerf">
        <div class="row">
            <div class="footer-col">
                <center><h4>compania</h4>
                <ul>
                    <li><a href="https://astralix.netlify.app/#about">Sobre</a></li>
                    <li><a href="https://astralix.netlify.app/#services">serviços</a></li>
                    
                    <li><a href="https://astralix.netlify.app/">empressa afiliada</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>ajuda</h4>
                <ul>
                    <li><a href="https://www.instagram.com/direct/t/17845317267048510">chat/instagram</a></li>
                    <li><a href="https://wa.me/5516996349439">whatsapp</a></li>
             
                </ul>
            </div>
            <div class="footer-col">
                <h4>siga nossas redes</h4>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/ghstore170/"><i class="fab fa-instagram"></i></a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</footer>  

</body>
</html>
