<?php
// Replace with your actual secret key from PayMongo
$secret_key = 'sk_test_itAEsS1vN1ahCPvSPZgCKHVa';

// Get product details from POST
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'] * 100; // PayMongo expects amount in centavos
$quantity = $_POST['quantity'];
$total_amount = $product_price * $quantity;

// Initialize cURL
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.paymongo.com/v1/checkout_sessions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

// Headers
$headers = [
    'Authorization: Basic ' . base64_encode($secret_key . ':'),
    'Content-Type: application/json',
];

// Request data (now includes PayMaya)
$data = [
    'data' => [
        'attributes' => [
            'billing' => [
                'name' => 'Customer Name', // Replace with dynamic data if needed
                'email' => 'customer@example.com' // Replace with dynamic data if needed
            ],
            'description' => 'Purchase of ' . $product_name,
            'send_email_receipt' => true,
            'show_description' => true,
            'show_line_items' => true,
            'payment_method_types' => ['gcash', 'card', 'paymaya'], // ✅ Added PayMaya
            'line_items' => [[
                'name' => $product_name,
                'amount' => $product_price,
                'currency' => 'PHP',
                'quantity' => (int)$quantity,
            ]],
            'success_url' => 'http://localhost/SYSTEM%20ARCHITECTURE/rimbao/dashboard.php',
            'cancel_url' => 'https://yourwebsite.com/dashboard.php'
        ]
    ]
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true);

// Redirect to PayMongo checkout
if (isset($responseData['data']['attributes']['checkout_url'])) {
    header('Location: ' . $responseData['data']['attributes']['checkout_url']);
    exit();
} else {
    echo "Error creating checkout session.";
    var_dump($responseData); // Debugging
}