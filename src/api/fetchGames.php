<?php
$matchday = isset($_GET['round']) ? (int)$_GET['round'] : 1;

$redis = new Redis();
$redis->connect('redis', 6379);

$cacheKey = "games:$matchday";
$cachedData = $redis->get($cacheKey);

if ($cachedData) {
    echo $cachedData;
    exit;
}

$apiUrl = "https://api.football-data.org/v4/competitions/PL/matches?matchday=$matchday";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiUrl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'X-Auth-Token: 22092fde356a4371b83602e8122a5885'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

if ($response === false) {
    echo json_encode(["error" => "Erro ao buscar dados da API."]);
    exit;
}

$data = json_decode($response, true);

if (isset($data['matches']) && is_array($data['matches'])) {
    $matches = $data['matches'];
    
    $formattedMatches = [];
    
    foreach ($matches as $match) {
        $formattedMatches[] = [
            'homeTeam' => [
                'id' => $match['homeTeam']['id'],
                'name' => $match['homeTeam']['name'],
                'crest' => $match['homeTeam']['crest']
            ],
            'awayTeam' => [
                'id' => $match['awayTeam']['id'],
                'name' => $match['awayTeam']['name'],
                'crest' => $match['awayTeam']['crest']
            ],
            'utcDate' => $match['utcDate'],
            'status' => $match['status'],
            'matchday' => $match['matchday'],
            'score' => $match['score'],
        ];
    }

    $resposta = json_encode(['matches' => $formattedMatches], JSON_PRETTY_PRINT);

    $redis->setex($cacheKey, 3600, $resposta);

    echo $resposta;
} else {
    echo json_encode(["error" => "Dados de jogos não encontrados."]);
}
