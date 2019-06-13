(function( $ ) {
	'use strict';
  
  $(function(){
    var cont=$('#qua_num').val();
    var lista=[];
    var io=[];
    var ck=[];
    var num=0;
    var pos=[];
    var lista_ass=quattro_val['posts'];
    var _123_=quattro_val['titolo_post'];
    
    list();

    for(var i=0;i<lista.length;i++){
      ck[i]=quattro_val['auth_val'][i]; 
    }

    inizializza();

    var lung_ass=lista_ass.length;
    var conta=-1;
    for(var x=0;x<lung_ass;x++){
      conta++;
      if(ck[conta]!='ins'){
        lista.splice(x,1);
        x--;
        lung_ass--;
      }
    }
    
    genera();
    popola();

    function inizializza(){
      console.log('ini ',ck);
    for(var ix=0; ix<lista.length; ix++){
      console.log(ck[ix]);
        if(ck[ix]!=""){
          var t=ck[ix];
          var y=[t,quattro_val['posts_id'][ix]];
        $("#1").append('<input type="text" name="qua_post_'+ix+'" id="qua_post_'+ix+'" value="'+y+'" hidden>');
      }else {
           $("#1").append('<input type="text" name="qua_post_'+ix+'" id="qua_post_'+ix+'" value="" hidden>');     
        }
       }
    }
    
    function genera(){
      console.log(lista);
      for(var test in lista){
        $("#qua_lista").append(
                '<li>'+
                '<button type="button" id="qua_rem" data-id="'+lista[test]+'">-</button> '+
                lista[test]+
                '</li>');
        $("#qua_cerca").val("");
        $('#qua_num').val(cont);       
        cont++;
      for(var i in lista_ass){
        if(lista_ass[i][lista[test]]){
          ck[i]='ins';
         // console.log(i);
        }
      }
      }
      if($("#qua_cerca").val("")!="")
                                    list();
      $('#1').children().remove();
      inizializza();
    }

    $("#test").on('click','#qua_but',function(){
      $("#qua_cerca").val($(this).attr('data-id'));
      lista=[$(this).attr('data-id')];
      console.log();
      genera();
      $(this).parent().parent().remove();
      
       });
    
   $("#qua_lista").on('click','#qua_rem',function(){
      var inizio=$(this).attr("data-id");
        for(var i=0; i< lista_ass.length; i++){
        if(lista_ass[i][inizio]){
            if(quattro_val['auth_val'][i]!="")
                ck[i]='rem';
            else ck[i]="";
        }    
    }
    list();
      $('#1').children().remove();
      inizializza();
      $(this).parent().remove();
      $('#qua_num').val(cont); 
        popola();
    });
    
      function list(){
      for (var i in quattro_val['titolo_post'])
        lista[i]=quattro_val['titolo_post'][i];
    }
    
    $("#qua_cerca").keyup(function(){
      list();
      var lung=lista.length;
      var cont=-1;
      var conta=-1;
      $('#test').children().remove();
      for(var x=0;x<lung;x++){
        conta++;
        if(ck[conta]=='ins'){
          lista.splice(x,1);
          x--;
          lung--;
        }
      }
      if($('#qua_cerca').val() == ""){
        popola();
      }
      lung=lista.length;
      for(var i=0;i<lung;i++){
              cont++;
              //console.log(i);
             if(lista[i].includes($('#qua_cerca').val()) && $('#qua_cerca').val() != ""){
               pos[i]=cont;
             }else {
               lista.splice(i,1);
               lung--;
               i--;
             }
      }
      if($('#qua_cerca').val() != "")  
      $('#test').append(
              '<table>'+
              '<tbody  id="qua_tab_list">'+
              '<tr>'+
              '<th>select'+
              '</th>'+
              '<th>post'+
              '</th>'+
              '</tr>'+
              '</tbody>'+
              '</table>');
      
     for(var i=0;i<lista.length;i++){
       if(ck[i]==undefined){
         ck[i]="";
       }else
        $('#qua_tab_list').append(
                '<tr>'+
                '<td>'+
                '<button type="button" id="qua_but" data-id="'+lista[i]+'">+</button> '+
                '</td>'+
                '<td>'+
                lista[i]+
                '</td>'+
                '</tr>'
                );
     }
     
    });

    function popola(){
      list();
      var lung=lista.length;
      var cont=-1;
      var conta=-1;
      $('#test').children().remove();
      for(var x=0;x<lung;x++){
        conta++;
        if(ck[conta]=='ins'){
          lista.splice(x,1);
          x--;
          lung--;
        }
      }
      var lung=lista.length;
      for(var i=0;i<lung;i++){
        cont++;
         pos[i]=cont;      
    }

    $('#test').append(
      '<table>'+
      '<tbody  id="qua_tab_list">'+
      '<tr>'+
      '<th>select'+
      '</th>'+
      '<th>post'+
      '</th>'+
      '</tr>'+
      '</tbody>'+
      '</table>');

for(var i=0;i<lista.length;i++){
if(ck[i]==undefined){
 ck[i]="";
}else 
  $('#qua_tab_list').append(
        '<tr>'+
        '<td>'+
        '<button type="button" id="qua_but" data-id="'+lista[i]+'">+</button> '+
        '</td>'+
        '<td>'+
        lista[i]+
        '</td>'+
        '</tr>'
        );
}
  }

  });	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
