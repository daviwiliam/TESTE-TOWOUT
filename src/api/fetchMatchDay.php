<?php

$apiUrl = "http://api.football-data.org/v4/competitions/PL";

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

if (isset($data['currentSeason']) && is_array($data['currentSeason'])) {

    $matchDay = $data['currentSeason']['currentMatchday'];

    $resposta = json_encode(['matchDay' => $matchDay], JSON_PRETTY_PRINT);

    echo $resposta;
} else {
    echo json_encode(["error" => "Dados não encontrados."]);
}
