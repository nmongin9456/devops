$(function () {

// cache le loader
var $loader = $('#loader');
$loader.hide();

// Initialisation des infobulles
  $('[data-toggle="tooltip"]').tooltip();

// Initialisation des Actions sur les VM
$('.ActionsVM').click(actionsVM());


if(window.location.pathname != '/'){
    $url = '/' + window.location.pathname.split('/')[1] + '/lib/axIndex.php';
}else{
    $url = '/lib/axIndex.php';
}
var $p = $('#ajaxParams');
var $div = $('#ajaxResult');

$loader.fadeIn(400);

$.ajax({
        url: $url,
        method: "POST",
        data: {
            action:     "GetVM",
            vm:          $p.text()
        },
        dataType: "html"
    }).done(function(data, textStatus, jqXHR){
        $div.hide();
        $div.html(data);
        $loader.fadeOut(400, function(){
                $div.fadeIn(400);
            });
    }).fail(function(jqXHR, textStatus, errorThrown){
        if(debugOn()){console.log(jqXHR);}
    });
});