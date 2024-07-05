<?php
require "../../vendor/autoload.php";
// charge le contenu du .env dans $_ENV

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../config");
$dotenv->load();

$key = $_ENV['SK_TEST'];
$stripe = new Stripe\StripeClient($key);

function calculateOrderAmount(int $amount): int {
    // Replace this constant with a calculation of the order's amount
    // Calculate the order total on the server to prevent
    // people from directly manipulating the amount on the client
    return $amount * 100;
}

header('Content-Type: application/json');

try {
    // retrieve JSON from POST body
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);


    // TODO : Create a PaymentIntent with amount and currency in '$paymentIntent'
        // Create a PaymentIntent with amount and currency
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => calculateOrderAmount($jsonObj->amount),
            'currency' => 'eur',
            // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

    $output = [
        'clientSecret' => $paymentIntent->client_secret,
    ];

    echo json_encode($output);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

