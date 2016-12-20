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
            //e.preventDefault();
            var $this = $(this);
            var $vm = $this.data('vm');
            var $ligne = $this.data('ligne');
            var $action = $this.data('action');
            //var $csrf = $this.data('csrf');
          
            if(window.location.pathname != '/'){
                $url = '/' + window.location.pathname.split('/')[1] + '/lib/axGetVMInfo.php';
            }else{
                $url = '/lib/axGetVMInfo.php';
            }
            console.log($url);
            console.log($vm);
            //console.log($csrf);
            console.log($action);
            console.log($ligne);
            
            $.ajax({
                url:            $url,
                method:         "POST",
                data: {
                    action:     $action,
                    ligne:      $ligne,
                    vm:         $vm
                    //csrf:       $csrf
                },
                dataType: "xml"
            }).fail(function(data, textStatus, jqXHR){
                console.log(textStatus);
            })
            
            /*
            $r.done(function(data, textStatus, jqXHR){
               /* 
                var $returnCode = ($('xml>returnCode', data).text());
                var $vm = ($('xml>result>vm>name', data).text());
                var $ligne = ($('xml>ligne', data).text());
                var $powerstate = ($('xml>result>vm>powerstate', data).text());
                if ($returnCode == 4488){
                    if($powerstate == 'PoweredOn'){
                        $("#STATUS_"+$ligne).html('<span class="glyphicon glyphicon-check text-success"></span>');
                        $('[data-action="StartVM"][data-ligne="'+$ligne+'"]').addClass("disabled");
                        $('[data-action="ShutdownVM"][data-ligne="'+$ligne+'"]').removeClass("disabled");
                        $('[data-action="RestartVM"][data-ligne="'+$ligne+'"]').removeClass("disabled");
                        $('[data-action="ForceStopVM"][data-ligne="'+$ligne+'"]').removeClass("disabled");
                    }else{
                        $("#STATUS_"+$ligne).html('<span class="glyphicon glyphicon-remove text-danger"></span>');
                        $('[data-action="StartVM"][data-ligne="'+$ligne+'"]').removeClass("disabled");
                        $('[data-action="ShutdownVM"][data-ligne="'+$ligne+'"]').addClass("disabled");
                        $('[data-action="RestartVM"][data-ligne="'+$ligne+'"]').addClass("disabled");
                        $('[data-action="ForceStopVM"][data-ligne="'+$ligne+'"]').addClass("disabled");
                    }
                }
                
            });*/
            
        });
    });
});
