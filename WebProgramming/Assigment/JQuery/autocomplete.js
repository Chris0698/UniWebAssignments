$(document).ready(function () {
    'use strict';

    //Gets the search box
    $('#searchBox').autocomplete({
        source: 'server/getBook.php', //Source of the books
        minLength: 3,  //Min length for the term

        select: function (event, ui) { //Called when a book selected from the list
            console.log(ui);
            $.ajax({
                method: 'get',
                url: 'server/getFullBook.php?bookISBN=' + ui.item.value //Go to the php script to get full details

            })
                .done(function (data, status, jqxhr) {
                    console.log(data);      //For debugging
                    $('#overView').hide();  //Hide the overview text
                    $('#returnedBook').html(data);  //Write the returned text to the index page
                })
        }
    });
});