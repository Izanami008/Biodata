<?php
header('Content-Type: application/json');

$apiKey = 'GANTI_DENGAN_API_KEY_KAMU';

date_default_timezone_set('Asia/Jakarta');

$input = json_decode(file_get_contents('php://input'), true);

$message = trim($input['message'] ?? '');
$history = $input['history'] ?? [];

$today = date('l');
$date = date('d F Y');
$time = date('H:i');

$systemPrompt =
"Kamu adalah Izanami AI.
Jawab natural, cerdas, relevan, dan mengikuti konteks percakapan.
Gunakan history chat untuk memahami referensi sebelumnya.
Jika user menanyakan waktu, gunakan info sistem.
Hari ini $today.
Tanggal $date.
Jam $time.";

$messages = [
  [
    'role' => 'system',
    'content' => $systemPrompt
  ]
];

$recent = array_slice($history, -20);

foreach ($recent as $item) {
  if (!isset($item['role']) || !isset($item['text'])) {
    continue;
  }

  $role = $item['role'] === 'ai'
    ? 'assistant'
    : 'user';

  $messages[] = [
    'role' => $role,
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

$reply =
  $data['choices'][0]['message']['content']
  ?? 'AI sedang tidak tersedia.';

echo json_encode([
  'reply' => trim($reply)
]);
