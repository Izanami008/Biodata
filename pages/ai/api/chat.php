<?php
header('Content-Type: application/json');

$apiKey = 'GANTI_DENGAN_API_KEY_KAMU';

$input = json_decode(file_get_contents('php://input'), true);

$message = $input['message'] ?? '';
$history = $input['history'] ?? [];

$messages = [
  [
    'role' => 'system',
    'content' => 'Kamu adalah Izanami AI. Jawab ringkas, jelas, natural, dan tetap mempertahankan konteks percakapan.'
  ]
];

foreach ($history as $item) {
  $messages[] = [
    'role' => $item['role'],
    'content' => $item['text']
  ];
}

$messages[] = [
  'role' => 'user',
  'content' => $message
];

$payload = [
  'model' => 'gpt-4o-mini',
  'messages' => $messages,
  'temperature' => 0.7
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$result = curl_exec($ch);

if ($result === false) {
  echo json_encode([
    'reply' => 'AI sedang tidak tersedia.'
  ]);
  curl_close($ch);
  exit;
}

curl_close($ch);

$data = json_decode($result, true);

$reply = $data['choices'][0]['message']['content'] ?? 'AI sedang tidak tersedia.';

echo json_encode([
  'reply' => $reply
]);
