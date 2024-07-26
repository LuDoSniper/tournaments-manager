document.addEventListener("DOMContentLoaded", function() {
    // Ajouter la création de tournois
    var overlay = document.getElementById('overlay')
    var tournament_form = document.getElementById('tournament-form')
    var add = document.getElementById('add')

    overlay.addEventListener('click', function() {
        overlay.classList.add('inactive')
        tournament_form.classList.add('inactive')
    })
    add.addEventListener('click', function() {
        overlay.classList.remove('inactive')
        tournament_form.classList.remove('inactive')
    })

    // Gestion de la date
    var today = new Date().toISOString().split('T')[0]
    document.getElementById('starting_date').value = today

    // Ajouter le déplacement vers les tournois
    var divs = document.querySelectorAll('div[id^=tournament-]')
    divs = Array.from(divs)
    var tournaments = divs.filter(function(div){
        return div.id !== "tournament-form"
    })

    tournaments.forEach(function(tournament) {
        tournament.addEventListener('click', function() {
            var id = tournament.id.split('-')[1]
            var form = document.createElement('form')
            form.method = "POST"
            form.action = "tournament.php"

            var field_id = document.createElement('input')
            field_id.type = 'hidden'
            field_id.name = "id"
            field_id.value = id

            form.appendChild(field_id)
            document.body.appendChild(form)

            form.submit()
        })
    })
})