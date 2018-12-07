document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems,  {});
});

$(document).ready(function(){
    $('.modal').modal();
});


$(document).ready(function(){
    $('select').formSelect();
});

$( "#formRessources" ).submit(function( event ) {
    event.preventDefault();
    var data = $( "#formRessources" ).serialize();
    $.ajax({
        method: "POST",
        url: "./api.php?action=saveItem",
        data: data
    })
    .done(function( msg ) {
        console.log(msg);
        M.toast({html: 'Données sauvegardées!', classes: 'rounded'});
    });
});

function deleteItem(event, id){

    $.ajax({
        method: "POST",
        url: "./api.php?action=deleteItem",
        data: {
            idItem : id
        }
    })
    .done(function( msg ) {
        console.log(msg);
        M.toast({html: 'Item delete!', classes: 'rounded'});
    });
}