(function ($) {
  'use strict';

  $(function () {
    var pre=0;
    var lista = [];
    var io = [];
    var ck = [];
    var num = 0;
    var pos = [];
    var lista_ass = quattro_val_auth['auth_compl'];
    var presenti= quattro_val_auth['id_presenti'];
    var cont=presenti.length;
    $('#qua_art').val('no');
    list();
    
    for(var x in quattro_val_auth['lista_id']) {
      pre=0;
       for(var y in presenti){
         if(quattro_val_auth['lista_id'][x]==presenti[y]){
           pre=1;
         }
       }if(pre==0) ck[x]="ins";
        else ck[x]="";
      }

    
    var lung_ass=lista_ass.length;
    var conta=-1;
    for(var x=0;x<lung_ass;x++){
      conta++;
      if(ck[conta]!='ins'){
        if(ck[conta]!='rem') ck[conta]="";
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
          var y=[t,quattro_val_auth['lista_id'][ix]];
        $("#1").append('<input type="text" name="qua_post_'+ix+'" id="qua_post_'+ix+'" value="'+y+'" hidden>');
      }else {
           $("#1").append('<input type="text" name="qua_post_'+ix+'" id="qua_post_'+ix+'" value="" hidden>');     
        }
       }
    }

    function list() {
        for (var i in quattro_val_auth['lista_name'])
              lista[i]=quattro_val_auth['lista_name'][i];
    }

    inizializza();
    
    $("#qua_cerca_auth").keyup(function () {
      list();
      var lung = lista.length;
      var cont = -1;
      //console.log(lung);
      var conta = -1;
      $('#qua_list_auth').children().remove();
      for (var x = 0; x < lung; x++) {
        conta++;
        if (ck[conta] == 'ins') {
          lista.splice(x, 1);
          x--;
          lung--;
        }
      }
      if ($('#qua_cerca_auth').val() == "") {
        popola();
      }
      lung = lista.length;
      for (var i = 0; i < lung; i++) {
        cont++;
        //console.log(i);
        if (lista[i].includes($('#qua_cerca_auth').val()) && $('#qua_cerca_auth').val() != "") {
          pos[i] = cont;
          // console.log($('#qua_cerca').val());
        } else {
          //   console.log('no-',lista[i]);
          lista.splice(i, 1);
          lung--;
          i--;
          //  console.log(lista.length);
        }
      }
      if ($('#qua_cerca_auth').val() != "")
        $('#qua_list_auth').append(
                '<table>' +
                '<tbody  id="qua_tab_list_auth">' +
                '<tr>' +
                '<th>select' +
                '</th>' +
                '<th>post' +
                '</th>' +
                '</tr>' +
                '</tbody>' +
                '</table>');

      for (var i = 0; i < lista.length; i++) {
        if(ck[i]==undefined){
         ck[i]="";
         }else //console.log(ck[i],i);
        // console.log(pos[i],i);
        $('#qua_tab_list_auth').append(
                '<tr>' +
                '<td>' +
                '<button type="button" id="qua_but" data-id="' + lista[i] + '">+</button> ' +
                '</td>' +
                '<td>' +
                lista[i] +
                '</td>' +
                '</tr>'
                );
      }

    });

    function popola() {
      list();
      var lung = lista.length;
      var cont = -1;
      //console.log(lung);
      var conta = -1;
      $('#qua_list_auth').children().remove();
      for (var x = 0; x < lung; x++) {
        conta++;
        if (ck[conta] == 'ins') {
          lista.splice(x, 1);
          x--;
          lung--;
        }
      }
      var lung = lista.length;
      for (var i = 0; i < lung; i++) {
        cont++;
        pos[i] = cont;
      }

      $('#qua_list_auth').append(
              '<table>' +
              '<tbody  id="qua_tab_list_auth">' +
              '<tr>' +
              '<th>seleziona' +
              '</th>' +
              '<th>autore' +
              '</th>' +
              '</tr>' +
              '</tbody>' +
              '</table>');

      for (var i = 0; i < lista.length; i++) {
        if(ck[i]==undefined){
         ck[i]="";
         }else //console.log(ck[i],i);
         // console.log(pos[i],i);
        $('#qua_tab_list_auth').append(
                '<tr>' +
                '<td>' +
                '<button type="button" id="qua_but" data-id="' + lista[i]+ '">+</button> ' +
                '</td>' +
                '<td>' +
                lista[i] +
                '</td>' +
                '</tr>'
                );
      }
    }
    
    
     
     function genera(){
     console.log(lista);
     for(var test in lista){
     console.log(test);
     $("#qua_lista_auth").append(
     '<li>'+
     '<button type="button" id="qua_rem" data-id="'+lista[test]+'">-</button> '+
     lista[test]+
     '</li>');
     $("#qua_cerca_auth").val("");
     $('#qua_list_auth').children().remove();
     cont++;
     for(var i in lista_ass){
    if(lista_ass[i][lista[test]]){
     ck[i]='ins';
     console.log(i);
     }
   }
    }
    if($("#qua_cerca_auth").val("")!="")
                                    list();
      $('#1').children().remove();
      inizializza();
   }
     
     
     $("#qua_lista_auth").on('click','#qua_rem',function(){
       $('#qua_art').val('yes');
     var inizio=$(this).attr("data-id");
     for(var i in lista_ass){
       console.log(i,' ', inizio,' ',lista_ass[i][inizio], ' ',lista_ass);
     if(lista_ass[i][inizio]){  
     if(ck[i]!="")
     ck[i]='rem';
     }  
     }
     list();
     inizializza();
     $(this).parent().remove();
     popola();
     });
     
     $("#qua_list_auth").on('click','#qua_but',function(){
     $('#qua_art').val('yes');
     $("#qua_cerca_auth").val($(this).attr('data-id'));
     lista=[$(this).attr('data-id')];
     console.log(lista);
     genera();
     popola();
     $(this).parent().parent().remove();
     
     });

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

})(jQuery);
