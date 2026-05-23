<?php

header("Content-Type: application/json");

include 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['text']) || empty(trim($data['text']))) {
    echo json_encode([
        "error" => "Input text is required"
    ]);
    exit;
}

$payload = [
    "model" => "gpt-5-mini",
    "input" => "Summarize professionally:\n\n" . $data['text']
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/responses");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $OPENAI_API_KEY
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode([
        "error" => curl_error($ch)
    ]);
    exit;
}

curl_close($ch);

$result = json_decode($response, true);

/*
DEBUG RESPONSE
*/
// echo "<pre>";
// print_r($result);
// exit;

$output = $result['output'][0]['content'][0]['text']['value']
    ?? $result['output_text']
    ?? null;

if (!$output) {
    echo json_encode([
        "error" => "No response generated",
        "api_response" => $result
    ]);
    exit;
}

echo json_encode([
    "response" => $output
]);
