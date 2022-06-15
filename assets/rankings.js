function getQualifyingRankings()
{
    const qualifyingRanking = document.querySelector('#qualifying-ranking');

    fetch('http://localhost:8000/api/rankings/qualifying')
        .then( (response) => {
            if( response.status === 200 ) {
                return response.json();
            }
            else {
                alert( "une erreur est survenue" );
                return;
            }})
        .then( (jsonResponse) => {
            let loader = document.querySelector('#qualifying-loader');
            qualifyingRanking.removeChild(loader);
            let container = document.createElement('ul');
            container.className = "rankings-topten";
                for (let index = 0; index < jsonResponse.length; index++) {
                    let liElement = document.createElement('li');
                    liElement.className = "list-item";

                    let positionElement = document.createElement('div');
                    positionElement.className = "ranking-position";
                    positionElement.textContent = index + 1;
                    liElement.appendChild(positionElement);
                    container.appendChild(liElement);

                    let userElement = document.createElement('div');
                    userElement.className = "ranking-username";
                    userElement.textContent = jsonResponse[index].user.personaname;
                    liElement.appendChild(userElement);
                    container.appendChild(liElement);

                    let scoreElement = document.createElement('div');
                    scoreElement.className = "ranking-score";
                    scoreElement.textContent = jsonResponse[index].qualifyingScore + " pts";
                    liElement.appendChild(scoreElement);
                    container.appendChild(liElement);
                }
            qualifyingRanking.appendChild(container);
        })
}

function getRaceRankings()
{
    const raceRanking = document.querySelector('#race-ranking');

    fetch('http://localhost:8000/api/rankings/race')
        .then( (response) => {
            if( response.status === 200 ) {
                return response.json();
            }
            else {
                alert( "une erreur est survenue" );
                return;
            }})
        .then( (jsonResponse) => {
            let loader = document.querySelector('#race-loader');
            raceRanking.removeChild(loader);
            let container = document.createElement('ul');
            container.className = "rankings-topten";
                for (let index = 0; index < jsonResponse.length; index++) {
                    let liElement = document.createElement('li');
                    liElement.className = "list-item";

                    let positionElement = document.createElement('div');
                    positionElement.className = "ranking-position";
                    positionElement.textContent = index + 1;
                    liElement.appendChild(positionElement);
                    container.appendChild(liElement);

                    let userElement = document.createElement('div');
                    userElement.className = "ranking-username";
                    userElement.textContent = jsonResponse[index].user.personaname;
                    liElement.appendChild(userElement);
                    container.appendChild(liElement);

                    let scoreElement = document.createElement('div');
                    scoreElement.className = "ranking-score";
                    scoreElement.textContent = jsonResponse[index].raceScore + " pts";
                    liElement.appendChild(scoreElement);
                    container.appendChild(liElement);
                }
            raceRanking.appendChild(container);
        })
}

function getGlobalRankings()
{
    const globalRanking = document.querySelector('#global-ranking');

    fetch('http://localhost:8000/api/rankings/global')
        .then( (response) => {
            if( response.status === 200 ) {
                return response.json();
            }
            else {
                alert( "une erreur est survenue" );
                return;
            }})
        .then( (jsonResponse) => {
            let loader = document.querySelector('#global-loader');
            globalRanking.removeChild(loader);
            let container = document.createElement('ul');
            container.className = "rankings-topten";
                for (let index = 0; index < jsonResponse.length; index++) {
                    let liElement = document.createElement('li');
                    liElement.className = "list-item";

                    let positionElement = document.createElement('div');
                    positionElement.className = "ranking-position";
                    positionElement.textContent = index + 1;
                    liElement.appendChild(positionElement);
                    container.appendChild(liElement);

                    let userElement = document.createElement('div');
                    userElement.className = "ranking-username";
                    userElement.textContent = jsonResponse[index].user.personaname;
                    liElement.appendChild(userElement);
                    container.appendChild(liElement);

                    let scoreElement = document.createElement('div');
                    scoreElement.className = "ranking-score";
                    scoreElement.textContent = jsonResponse[index].total + " pts";
                    liElement.appendChild(scoreElement);
                    container.appendChild(liElement);
                }
            globalRanking.appendChild(container);
        })
}
// getQualifyingRankings();
// getRaceRankings();
// getGlobalRankings();
document.addEventListener("DOMContentLoaded", getQualifyingRankings);
document.addEventListener("DOMContentLoaded", getRaceRankings);
document.addEventListener("DOMContentLoaded", getGlobalRankings);