# Premier League Matches Viewer

Este projeto é um sistema web para visualizar jogos da Premier League, permitindo a navegação entre rodadas e a exibição de informações detalhadas sobre os times. O sistema é desenvolvido em PHP puro, JavaScript, HTML e CSS, consumindo a API pública de futebol (Football-Data.org) e rodando em um ambiente Docker com Redis para cache.

## Estrutura do Projeto

```
📦 src/
 ┣ 📂 api/
 ┃ ┣ 📜 fetchGames.php
 ┃ ┣ 📜 fetchMatchDay.php
 ┃ ┗ 📜 fetchTeamInfo.php
 ┣ 📂 assets/
 ┃ ┣ 📂 css/
 ┃ ┃ ┗ 📜 style.css
 ┃ ┣ 📂 img/
 ┃ ┣ 📂 js/
 ┃ ┃ ┗ 📜 main.js
 ┣ 📜 index.php
 ┣ 📜 Dockerfile
 ┣ 📜 docker-compose.yml
 ┗ 📜 README.md
```

## Pré-requisitos

- [Docker](https://www.docker.com/get-started) instalado no sistema.
- Uma chave de API válida do [Football-Data.org](https://www.football-data.org/).

## Configuração e Execução

### 1. Clonar o Repositório
```sh
$ git clone https://github.com/daviwiliam/TESTE-TOWOUT.git
$ cd seu-repositorio
```

### 2. Configurar a API Key
Edite os arquivos dentro da pasta `src/api/` e substitua `X-Auth-Token` pela sua chave de API.

### 3. Subir os Containers Docker
```sh
$ docker-compose up --build
```
Isso iniciará os serviços necessários, incluindo PHP e Redis.

### 4. Acessar a Aplicação
Abra um navegador e acesse:
```
http://localhost
```
## Tecnologias Utilizadas
- PHP (sem frameworks)
- JavaScript (fetch API para requisições)
- HTML e CSS
- Redis (cache de respostas)
- Docker e Docker Compose

## Melhorias Futuras
- Implementação de testes automatizados
- Melhorias no design e usabilidade
- Adicionar mais competições além da Premier League

