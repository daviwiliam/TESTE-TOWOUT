document.addEventListener("DOMContentLoaded", function () {
    let currentRound = 1;

    async function loadMatchDay() {
        try {
            const response = await fetch('src/api/fetchMatchDay.php');
            const data = await response.json();
            currentRound = data.matchDay + 1 || 1;
            console.log('Current Round:', currentRound);
        } catch (error) {
            console.error('Erro ao carregar a rodada:', error);
        }
    }
    
    loadMatchDay();

    document.getElementById("btnPremierLeague").addEventListener("click", showNavigation);
    document.getElementById("prevRound").addEventListener("click", () => changeRound(-1));
    document.getElementById("nextRound").addEventListener("click", () => changeRound(1));
    document.getElementById("backToRound").addEventListener("click", showGamesContainer);

    function showNavigation() {
        const navigation = document.getElementById("navigation");
        navigation.classList.remove("hidden");
        document.getElementById("teamInfo").classList.add("hidden");
        document.getElementById("gamesContainer").classList.remove("hidden");
        fetchGames(currentRound);
    }

    function changeRound(direction) {
        if ((direction < 0 && currentRound > 1) || (direction > 0 && currentRound < 38)) {
            currentRound += direction;
            fetchGames(currentRound);
        }
    }

    function showGamesContainer() {
        document.getElementById("teamInfo").classList.add("hidden");
        document.getElementById("gamesContainer").classList.remove("hidden");
        document.getElementById("navigation").classList.remove("hidden");
    }

    function fetchGames(round) {
        console.log(`Buscando jogos da rodada ${round}...`);
        
        fetch(`src/api/fetchGames.php?round=${round}`)
            .then(response => response.json())
            .then(data => displayGames(data.matches, round))
            .catch(error => console.error('Erro ao buscar jogos:', error));
    }

    function displayGames(matches, round) {
        const gamesContainer = document.getElementById("gamesContainer");
        gamesContainer.innerHTML = "";
        document.getElementById("currentRound").textContent = `Rodada ${round}`;

        if (Array.isArray(matches) && matches.length > 0) {
            matches.forEach(match => {
                const gameDiv = document.createElement("div");
                gameDiv.classList.add("game");
                gameDiv.innerHTML = generateGameHTML(match);
                gamesContainer.appendChild(gameDiv);

                gameDiv.querySelectorAll('.team img').forEach(logo => {
                    logo.addEventListener('click', () => fetchTeamInfo(logo.parentElement));
                });
            });
        } else {
            gamesContainer.innerHTML = "<p>Não há jogos disponíveis para esta rodada.</p>";
        }
    }

    function generateGameHTML(match) {
        const homeTeam = createTeamHTML(match.homeTeam);
        const awayTeam = createTeamHTML(match.awayTeam);
        const matchTime = formatMatchTime(match.utcDate);
        const score = match.status === "FINISHED" 
            ? `${match.score.fullTime.home} x ${match.score.fullTime.away}`
            : matchTime;

        return `
            <div class="match-info">
                <div class="homeTeam">${homeTeam}</div>
                <div class="score">${score}</div>
                <div class="awayTeam">${awayTeam}</div>
            </div>
        `;
    }

    function createTeamHTML(team) {
        return `
            <div class="team" data-team-id="${team.id}">
                <img src="${team.crest}" alt="${team.name}">
                <span>${team.name}</span>
            </div>
        `;
    }

    function formatMatchTime(date) {
        const matchDate = new Date(date);
        if (isNaN(matchDate)) {
            return 'Data inválida';
        }
        const dayMonth = matchDate.toLocaleDateString("pt-BR", { day: '2-digit', month: '2-digit' });
        const time = matchDate.toLocaleTimeString("pt-BR", { hour: '2-digit', minute: '2-digit' });
        return `${dayMonth} - ${time}`;
    }

    function fetchTeamInfo(team) {
        let teamId = team.dataset.teamId;
        let teamName = btoa(team.querySelector('img').alt);
        let teamCrest = btoa(team.querySelector('img').src);
        console.log(`Buscando informações do time: ${teamId}`);

        fetch(`src/api/fetchTeamInfo.php?teamId=${teamId}&teamName=${teamName}&teamCrest=${teamCrest}`)
            .then(response => response.json())
            .then(data => displayTeamInfo(data))
            .catch(error => console.error('Erro ao buscar informações do time:', error));
    }

    function displayTeamInfo(teamInfo) {
        if (teamInfo.error) {
            const gamesContainer = document.getElementById("gamesContainer");
            gamesContainer.innerHTML = `<p>${teamInfo.error}</p>`;
            return;
        }
        
        document.getElementById("gamesContainer").classList.add("hidden");
        document.getElementById("navigation").classList.add("hidden");

        const teamDetails = document.getElementById("teamDetails");
        teamDetails.innerHTML = generateTeamInfoHTML(teamInfo);

        const teamMatchesContainer = document.getElementById("teamMatches");
        teamInfo.matches.forEach(match => {
            const gameDiv = document.createElement("div");
            gameDiv.classList.add("game");
            gameDiv.innerHTML = generateGameHTML(match);
            teamMatchesContainer.appendChild(gameDiv);

            gameDiv.querySelectorAll('.team img').forEach(logo => {
                logo.addEventListener('click', () => fetchTeamInfo(logo.parentElement));
            });
        });

        document.getElementById("teamInfo").classList.remove("hidden");
    }

    function generateTeamInfoHTML(teamInfo) {
        return `
            <div id="headTeamInfo">
                <img src="${teamInfo.team.crest}" alt="${teamInfo.team.name}">
                <h2>${teamInfo.team.name}</h2>
            </div>
            <div>
                <h3>Partidas</h3>
                <div id="teamMatches"></div>
            </div>
        `;
    }
});
