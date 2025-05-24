<?php
// Configuração do banco de dados
$host = 'localhost';
$dbname = 'academia_store';
$username = 'root';
$password = '';

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Configuração do Stripe (certifique-se de usar sua chave secreta correta)
require_once('vendor/autoload.php');
\Stripe\Stripe::setApiKey('sk_test_51QxIRnIHopK6yhlHcsIrNueDMdNDNTQJUv68L6uaA58UTx1qfWOV6gibDwNPL2hRRU6x3MmVxxR59bIASJmbDekk00Px3DNfcc');  // Substitua com sua chave secreta
?>
