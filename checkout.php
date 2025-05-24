<header>
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

<style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        margin: 0;
        padding: 0;
        background-color:rgb(255, 255, 255);
    }

    .menu {
        margin-bottom: 20px;
    }

    .menu a {
        margin: 0 10px;
        text-decoration: none;
        font-weight: bold;
        color: #008CBA;
    }

    .navbar {
        
    position: relative;
    width: 100%;
    padding: 15px 0;
    font-family: 'Ubuntu', sans-serif;
    z-index: 1000; /* Certifique-se de que o z-index seja maior que o do #payment-form */
    transition: all 0.3s ease;
    background: rgb(255, 255, 255);
    height: 60px;


    }

    .navbar.sticky {
        background: #800000;
        padding: 10px 0;
    }

    .navbar .max-width {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
    }

    .navbar .logo a {
        color: black;
        font-size: 28px;
        font-weight: 700;
    }

    .navbar .logo a span {
        color: #800000;
        transition: all 0.3s ease;
    }

    .navbar.sticky .logo a span {
        color: #fff;
    }

    .navbar .menu li {
        list-style: none;
        display: inline-block;
    }

    .navbar .menu li a {
        color: black;
        font-size: 18px;
        font-weight: 500;
        margin-left: 25px;
        transition: color 0.3s ease;
    }

    .navbar .menu li a:hover {
        color: #800000;
    }

    .navbar.sticky .menu li a:hover {
        color: #fff;
    }

        #payment-form {
    background: #ffffff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
    margin: 120px auto 0; /* Ajuste a margem superior */


    }

    #card-element {
        background: #f8f8f8;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        margin-bottom: 20px;
    }

    button[type="submit"] {
        background: #800000;
        color: #ffffff;
        border: none;
        padding: 12px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
        width: 100%;
    }

    button[type="submit"]:hover {
        background: #a00000;
    }

    .home-content{
        background-color:withe;

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
                           
                        <li>
                                <a href="produtos.php">Home</a>
                            </li>
                            
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
</div>
    

<form action="finalizar_compra.php" method="POST" id="payment-form">
    <div id="card-element">
        <!-- Campo do cartão de crédito -->
    </div>
    <button type="submit">Pagar</button>
</form>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_51QxIRnIHopK6yhlH0jOJR3GJGnp1kr7yOUHAXuY9FHsV7WTSvtzrg7gJCyXXzz2AlcM5rFrkxBsmZzsfIb0Yi4eB00UuJZktkW');
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                console.log(result.error.message);
            } else {
                var token = result.token.id;
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token);
                form.appendChild(hiddenInput);
                form.submit();
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