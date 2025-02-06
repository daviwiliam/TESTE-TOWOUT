<?php
$teamId = isset($_GET['teamId']) ? (int)$_GET['teamId'] : null;
$teamName = isset($_GET['teamName']) ? base64_decode($_GET['teamName']) : null;
$teamCrest = isset($_GET['teamCrest']) ? base64_decode($_GET['teamCrest']) : null;

if ($teamId === null) {
    echo json_encode(["error" => "ID do time não fornecido."]);
    exit;
}

if ($teamName === null) {
    echo json_encode(["error" => "Nome do time não fornecido."]);
    exit;
}

if ($teamCrest === null) {
    echo json_encode(["error" => "Img do time não fornecido."]);
    exit;
}

$redis = new Redis();
$redis->connect('redis', 6379);

$cacheKey = "team_info:$teamId$teamName$teamCrest";
$cachedData = $redis->get($cacheKey);

if ($cachedData) {
    echo $cachedData;
    exit;
}

$apiUrl = "https://api.football-data.org/v4/teams/$teamId/matches?season=2024"; 

$headers = [
    'X-Auth-Token: 22092fde356a4371b83602e8122a5885'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_TIMEOUT, 2);
$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode(["error" => "Erro ao buscar dados da API."]);
    exit;
}

$data = json_decode($response, true);

if (isset($data['matches']) && is_array($data['matches'])) {

    $matches = $data['matches'];
    
    $formattedMatches = [];
    
    foreach ($matches as $match) {

        if($match['competition']['code'] === 'PL'){
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
    }

    $resposta = json_encode(
        [
            'team' => [
                'name' => $teamName,
                'crest'=> $teamCrest
            ],
            'matches' => $formattedMatches
        ],
         JSON_PRETTY_PRINT
        );

    $redis->setex($cacheKey, 3600, $resposta);

    echo $resposta;

} else {
    echo json_encode(["error" => "Dados de jogos não encontrados."]);
}
