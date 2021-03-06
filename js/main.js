$(function (){

// cache le loader
var $loader = $('#loader');
$loader.hide();

if(window.location.pathname != '/'){
    $url = '/' + window.location.pathname.split('/')[1] + '/axIndex.php';
}else{
    $url = '/axIndex.php';
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
    }).fail(function(request, status, err) {
        var $divResult = $('#ajaxResult');
        $divResult.hide();
        $divResult.removeClass("alert alert-success");
        $divResult.addClass("alert alert-danger");
        $divResult.text(err);
        $('#loader').fadeOut(400, function(){
                    $divResult.fadeIn(400);
                });
    }).done(function(data, textStatus, jqXHR){

        //Le Programme Continue lors de la réponse positive d'ajax...
        $div.hide();
        $div.html(data);
        $loader.fadeOut(400, function(){
                $div.fadeIn(400);
            });
        // Initialisation des infobulles
        $('[data-toggle="tooltip"]').tooltip();

        // Initialisation des Actions sur les VM pour tous les bouton ayant la classe 'actionVM' et n'ayant pas la classe 'disabled'
        $('.actionsVM:not(.disabled)').click(function(e){
            e.preventDefault();
            runAction($(this));
        });
    });
});


function runAction($o){
			$o.blur();
            var $div = $('#ajaxResult');
            var $loader = $('#loader');
            var $vm = $o.data('vm');
            var $ligne = $o.data('ligne');
            var $action = $o.data('action');
          
            if(window.location.pathname != '/'){
                $url = '/' + window.location.pathname.split('/')[1] + '/axGetVMInfo.php';
            }else{
                $url = '/axGetVMInfo.php';
            }
            console.log($url);
            console.log($vm);
            console.log($action);
            console.log($ligne);
            
			// cache la page et montre le loader
			$div.fadeOut(400, function(){
							$loader.fadeIn(400);
						});

            $.ajax({
                url:            $url,
                method:         "POST",
                data: {
                    action:     $action,
                    ligne:      $ligne,
                    vm:         $vm,
                    timeout:    120000
                },
                dataType: "xml"
            }).fail(function(request, status, err) {
                if (status == "timeout") {
                    // timeout -> reload the page and try again
                    console.log("timeout");
                    //window.location.reload();
                } else {
                    // another error occured  
                    //alert("error: " + request + status + err);
                    //window.location.reload();
                }
                $('#ajaxResultInfo').removeClass("alert-success");
                $('#ajaxResultInfo').addClass("alert-danger");
                $('#ajaxResultInfo').text(err);
                $loader.fadeOut(400, function(){
							$div.fadeIn(400);
						});
            }).done(function(data, textStatus, jqXHR){
				// cache le loader et montre les resultats de ajax
				$loader.fadeOut(400, function(){
							$div.fadeIn(400);
						});
				
                var $returnCode = ($('xml>returnCode', data).text());
                var $vm = ($('xml>result>vm>name', data).text());
                var $ligne = ($('xml>ligne', data).text());
                var $powerstate = ($('xml>result>vm>powerstate', data).text());
                var $url = ($('xml>result>vm>url', data).text());
                if($url){window.open($url, '_blank','height=400,width=800,toolbar=0,location=0,menubar=0');}
                if ($returnCode == 4488){
                    //Affichage dans la barre de status
                    $('#ajaxResultInfo').removeClass("alert-danger");
                    $('#ajaxResultInfo').addClass("alert-success");
                    $('#ajaxResultInfo').text($('xml>statusMSG', data).text());

                    // Suppression de l'event onClick de tous les boutons ActionVM
                    removeEventAction($('[data-action="StartVM"][data-ligne="'+$ligne+'"]'));
                    removeEventAction($('[data-action="ShutdownVM"][data-ligne="'+$ligne+'"]'));
                    removeEventAction($('[data-action="RestartVM"][data-ligne="'+$ligne+'"]'));
                    removeEventAction($('[data-action="ForceStopVM"][data-ligne="'+$ligne+'"]'));

                    if($powerstate == 'PoweredOn'){
                        $("#STATUS_"+$ligne).html('<span class="glyphicon glyphicon-check text-success"></span>');
                        $('[data-action="StartVM"][data-ligne="'+$ligne+'"]').addClass("disabled");
                        $('[data-action="ShutdownVM"][data-ligne="'+$ligne+'"]').removeClass("disabled");
                        addEventAction($('[data-action="ShutdownVM"][data-ligne="'+$ligne+'"]'));
                        $('[data-action="RestartVM"][data-ligne="'+$ligne+'"]').removeClass("disabled");
                        addEventAction($('[data-action="RestartVM"][data-ligne="'+$ligne+'"]'));
                        $('[data-action="ForceStopVM"][data-ligne="'+$ligne+'"]').removeClass("disabled");
                        addEventAction($('[data-action="ForceStopVM"][data-ligne="'+$ligne+'"]'));
                    }else{
                        $("#STATUS_"+$ligne).html('<span class="glyphicon glyphicon-remove text-danger"></span>');
                        $('[data-action="StartVM"][data-ligne="'+$ligne+'"]').removeClass("disabled");
                        addEventAction($('[data-action="StartVM"][data-ligne="'+$ligne+'"]'));
                        $('[data-action="ShutdownVM"][data-ligne="'+$ligne+'"]').addClass("disabled");
                        $('[data-action="RestartVM"][data-ligne="'+$ligne+'"]').addClass("disabled");
                        $('[data-action="ForceStopVM"][data-ligne="'+$ligne+'"]').addClass("disabled");
                    }
                }else{ 
                    //Affichage dans la barre de status
                    $('#ajaxResultInfo').removeClass("alert-danger");
                    $('#ajaxResultInfo').addClass("alert-danger");
                    $('#ajaxResultInfo').text("Requete en erreur : "+$('xml>statusMSG', data).text());
                }
                
            });
}

function addEventAction($button){
    $button.bind("click", function(e){
        e.preventDefault();
        runAction($(this));
    });   
}

function removeEventAction($button){
    $button.unbind( "click" );
}