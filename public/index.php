<?php require "../app/views/checkout.html";
require "../vendor/autoload.php";

// charge le contenu du .env dans $_ENV

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../config");
$dotenv->load();