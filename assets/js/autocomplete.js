console.log('OK')

function log(title, id) {
    $('#results').append('<li class="autocomplete"><a href="/movie/' + id + '">' + title + '</a><li>');
}

$("#search").autocomplete({
    source: function (request, response) {
        $('#results').html('');
        $.ajax({
            url: '/api/movies/search/' + request.term,
            dataType: "json",
            success: function (data) {

                data.forEach(function (movie, i) {
                    if (i <= 5) {
                        log(movie.title, movie.id);
                    }
                })
            }
        });
    },
    minLength: 2,
    select: function (event, ui) {
        console.log(ui)
        log("Selected: " + ui.item.value + " aka " + ui.item.id);
    }
});