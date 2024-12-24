<?php
header('Access-Control-Allow-Origin: *');
$url = 'https://api.openai.com/v1/realtime/sessions';
$data = [
    "model" => "gpt-4o-realtime-preview-2024-12-17",
    "modalities" => ["audio", "text"],
    "instructions" => "You are a friendly companion for a person who wants to chat in any language",
];

$config = include('config.php');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer '.$config['api_key'],
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Erreur cURL : ' . curl_error($ch);
    curl_close($ch);
    exit;
}

$response = json_decode($response, true);

if (isset($response['error'])) {
    echo 'Erreur API : ' . $response['error']['message'];
    curl_close($ch);
    exit;
}
$secret = $response['client_secret']['value'] ?? null;

if ($secret) {
    echo json_encode(['client_secret' => $secret]);
    curl_close($ch);
    exit();
}
    echo "Aucun secret trouvé dans la réponse.";
    curl_close($ch);

