<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premier League</title>
    <link rel="stylesheet" href="src/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <button id="btnPremierLeague">
            <img src="src/assets/img/premier-league.png" alt="Premier League">
        </button>

        <div id="navigation" class="hidden">
            <button id="prevRound"><i class="bi bi-chevron-left"></i></button>
            <span id="currentRound"></span>
            <button id="nextRound"><i class="bi bi-chevron-right"></i></button>
        </div>

        <div id="gamesContainer"></div>

        <div id="teamInfo" class="hidden">
            <button id="backToRound"><i class="bi bi-arrow-return-left"></i></button>
            <div id="teamDetails"></div>
        </div>
    </div>

    <script src="src/assets/js/main.js"></script>
</body>
</html>
