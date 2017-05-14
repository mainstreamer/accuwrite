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

        console.log('ajax successful');
        console.log(data);
        var f =  JSON.parse(data);
        console.log('TIME:'+f[f.length-1]['total']+' WORDS CHECKED:'+f[f.length-1]['words']);
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
        if (array[name]['value'] == 'underline') {
            $('#word_'+array[name]['id']).css({"text-decoration":"underline", "text-decoration-color":"red"});
        } else {
            buildTooltip(name, array);
        }
    }
}

function buildTooltip(name, array) {

    $('#word_'+array[name]['id']).tooltip({trigger:'manual',
        title : array[name]['value']
        // container : '#word_'+name
    }).tooltip('show');

    $('#word_'+array[name]['id']).after(' ');


    $('.tooltip').on('click', function(el){
        $(this).prev().text(el.target.innerText);
        $(this).hide();
    });


    $('span').on('click', function(){
        if ($(this).next().hasClass('tooltip')) {
            $(this).next().remove();
        }
    });

    $('span').hover(function(){
        if ($(this).next().hasClass('tooltip')) {
            $(this).css('cursor', 'cell');
        }
    });

}

function approveSuggestion() {

    $('div.tooltip-inner.approve').on('click', function (){
        var newVal = $('div.tooltip-inner.approve').parent('.tooltip-inner')[0].innerText;
        $('#word_'+name).text(newVal);
        $('div.tooltip-inner.approve').closest('.tooltip').hide();
        // $('div.tooltip').hide();
    });
}

function rejectSuggestion() {
    $('div.tooltip-inner.reject').on('click', function (){
        $('div.tooltip-inner.reject').closest('.tooltip').hide();
    });
}