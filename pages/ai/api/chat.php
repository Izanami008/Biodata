<?php
header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set('Asia/Jakarta');

$apiKey = getenv('OPENAI_API_KEY');
if (!$apiKey) {
    $apiKey = 'GANTI_DENGAN_API_KEY_KAMU';
}

$input = json_decode(file_get_contents('php://input'), true);

$message = trim($input['message'] ?? '');

if ($message === '') {
    http_response_code(400);
    echo json_encode([
        'reply' => 'Pesan kosong.'
    ]);
    exit;
}

$history = $input['history'] ?? [];

$systemPrompt =
"Kamu adalah Izanami AI.

Jawab menggunakan Bahasa Indonesia yang natural.

Jawaban harus singkat tetapi lengkap.

Gunakan konteks percakapan sebelumnya.

Jika tidak tahu jawabannya, katakan dengan jujur.

Hari: ".date('l')."

Tanggal: ".date('d F Y')."

Jam: ".date('H:i');

$messages = [
    [
        "role"=>"system",
        "content"=>$systemPrompt
    ]
];

foreach(array_slice($history,-20) as $h){

    if(empty($h["text"])) continue;

    $messages[]=[
        "role"=>$h["role"]=="ai"
            ?"assistant"
            :"user",
        "content"=>$h["text"]
    ];
}

$messages[]=[
    "role"=>"user",
    "content"=>$message
];

$payload=[
    "model"=>"gpt-4o-mini",
    "messages"=>$messages,
    "temperature"=>0.7,
    "max_tokens"=>800
];

$ch=curl_init("https://api.openai.com/v1/chat/completions");

curl_setopt_array($ch,[
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_POST=>true,
    CURLOPT_TIMEOUT=>30,
    CURLOPT_CONNECTTIMEOUT=>10,
    CURLOPT_HTTPHEADER=>[
        "Content-Type: application/json",
        "Authorization: Bearer ".$apiKey
    ],
    CURLOPT_POSTFIELDS=>json_encode($payload)
]);

$result=curl_exec($ch);

if(curl_errno($ch)){
    echo json_encode([
        "reply"=>"Koneksi ke AI gagal: ".curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

$http=curl_getinfo($ch,CURLINFO_HTTP_CODE);

curl_close($ch);

$data=json_decode($result,true);

if($http!=200){

    $err=$data["error"]["message"] ?? "Unknown error";

    echo json_encode([
        "reply"=>"OpenAI Error: ".$err
    ]);

    exit;
}

echo json_encode([
    "reply"=>trim(
        $data["choices"][0]["message"]["content"]
        ??"Maaf, aku tidak memiliki jawaban."
    )
]);
