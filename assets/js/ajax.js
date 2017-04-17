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
        // console.log('id : '+ name+' suggestion : '+array[name]['value']);
        console.log($('#word_'+name).title = array[name]['value']);
        buildTooltip(name, array);



        // console.log($(":contains('fas')" ));
    }
}


function buildTooltip(name, array) {

    $('#word_'+name).tooltip({trigger:'manual',
        title : array[name]['value'],
        // container : '#word_'+name
    }).tooltip('show');
    $('#word_'+name).after(' ');


    // $('#word_'+name).next()[0].append("<div class='tooltip-inner'>LAL</div>");
    // $('div.tooltip-inner:not(.cross)').append('<div class="tooltip-inner cross reject" pid="'+name+'"><i class="fa fa-times"></i></div>');

    // $('div.tooltip-inner:not(.cross)').append('<div class="tooltip-inner approve cross"><i class="fa fa-check"></i></div>');

    // $('div.reject').on('click', function (){
    //     $('div.reject').closest('.tooltip').hide();
    // });

    $('.tooltip').on('click', function(el){
        $(this).prev().text(el.target.innerText);
        $(this).hide();
    });


    $('span').on('click', function(el){
        if ($(this).next().hasClass('tooltip')) {
            $(this).next().hide();
        }
        // text(el.target.innerText);
        // $(this).hide();
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

