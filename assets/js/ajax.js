function goCheck(text) {
    $.ajax({
        url: "spellchecker.php",
        method: "POST",
        data: {
            text : text,
            format: 'json'
        }
    }).done(function(data) {
        $( this ).addClass( "done" );
        alert('success');
        var f =  JSON.parse(data);
        suggest(f);
    }).fail(
        function() {
            $( this ).addClass( "fail" );
            alert('fail');
        }
    );
}

function suggest(array) {

    for (var name in array) {
        // console.log('name : '+ name+' value : '+array[name]);
        console.log($(":contains('fas')" ));
    }
}

