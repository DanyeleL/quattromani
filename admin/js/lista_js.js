(function ($) {
  'use strict';

  $(function () {
    var cont = $('#qua_num').val();
    var lista = [];
    var io = [];
    var ck = [];
    var num = 0;
    var pos = [];
    var lista_ass = quattro_val['posts'];

    list(); //carico la lista id dei post

    var name_id = [];
    for (var name in quattro_val['posts_id']) { // creo array dei title
      name_id[quattro_val['posts_id'][name]] = lista_ass[name][quattro_val['posts_id'][name]];
    }

    for (var i = 0; i < lista.length; i++) { // creo array flaf controllo (ins , "" , rem)
      ck[i] = quattro_val['auth_val'][i]; //carico con valori salvati
    }

    inizializza(); //inizializzo valori flag

    var lung_ass = lista_ass.length;
    var conta = -1;
    for (var x = 0; x < lung_ass; x++) {
      conta++;
      if (ck[conta] != 'ins') {
        lista.splice(x, 1); //genero lista di post con flag ins
        x--;
        lung_ass--;
      }
    }

    genera();//genero la lista front-end dei valori con flag ins
    popola();//genero lista fornt-end dei valori non ins

    //funzione che genera input nascosti per salvare flag e id post
    function inizializza() {
      // console.log('ini ',ck);
      for (var ix = 0; ix < lista.length; ix++) {
        // console.log(ck[ix]);
        if (ck[ix] != "") {
          var t = ck[ix];
          var y = [t, quattro_val['posts_id'][ix]]; //creo array con flag e id da passare al save con POST
          $("#1").append('<input type="text" name="qua_post_' + ix + '" id="qua_post_' + ix + '" value="' + y + '" hidden>');
        } else {
          $("#1").append('<input type="text" name="qua_post_' + ix + '" id="qua_post_' + ix + '" value="" hidden>');
        }
      }
    }

    //funzione che genera lista front-end dei valori selezionati
    function genera() {
      //console.log(lista);
      for (var test in lista) {
        $("#qua_lista").append(
                '<li>' +
                '<button type="button" id="qua_rem" data-id="' + lista[test] + '">-</button> ' +
                name_id[lista[test]] +
                '</li>');
        $("#qua_cerca").val(""); // azzero input cerca
        $('#qua_num').val(cont);
        cont++;
        for (var i in lista_ass) {
          if (lista_ass[i][lista[test]]) { //genero flag
            ck[i] = 'ins';
            // console.log(i);
          }
        }
      }
      if ($("#qua_cerca").val("") != "")
        list();
      $('#1').children().remove(); //rimuovo vecchia lista
      inizializza();//genero nuova lista
    }

    //gestisco evento aggiungi
    $("#test").on('click', '#qua_but', function () {
      $("#qua_cerca").val($(this).attr('data-id')); // carico in cerca valore id selezionato
      lista = [$(this).attr('data-id')]; // restringo lista a id selezionato
      //console.log();
      genera(); //genero nuova lista aggunti front-end
      $(this).parent().parent().remove();//rimuovo valore selezionato da selezionabili

    });

    //gestisco evento rimuovi
    $("#qua_lista").on('click', '#qua_rem', function () {
      var inizio = $(this).attr("data-id"); //carico valore id selezionato
      for (var i = 0; i < lista_ass.length; i++) {
        if (lista_ass[i][inizio]) { //cerco in lista il valore selezionato
          if (quattro_val['auth_val'][i] != "")
            ck[i] = 'rem'; //imposto flag su rem per rimozione
          else
            ck[i] = "";
        }
      }
      list(); //rigenero lista
      $('#1').children().remove();//rimuovo vecchi front-end
      inizializza(); //genero nuovo front-end
      $(this).parent().remove(); //rimuovo voce selezionata
      $('#qua_num').val(cont);
      popola();//rigenero front-end selezionabili
    });

    //funzione genera lista id completa
    function list() {
      /* for (var i in quattro_val['titolo_post'])
       lista[i]=quattro_val['titolo_post'][i];*/
      for (var i in quattro_val['posts_id'])
        lista[i] = quattro_val['posts_id'][i];
    }

    //gestisco evento su input cerca
    $("#qua_cerca").keyup(function () {
      list();//genero lista
      var lung = lista.length;
      var cont = -1;
      var conta = -1;
      $('#test').children().remove(); //rimuovo vecchio front-end
      for (var x = 0; x < lung; x++) {
        conta++;
        if (ck[conta] == 'ins') {
          lista.splice(x, 1);//genero lista aggiunti
          x--;
          lung--;
        }
      }
      if ($('#qua_cerca').val() == "") {
        popola();//genero lista se input vuoto
      }
      lung = lista.length;
      for (var i = 0; i < lung; i++) {
        cont++;
        //console.log(i);
        if (name_id[lista[i]].includes($('#qua_cerca').val()) && $('#qua_cerca').val() != "") { //controllo presenza valore cercato
          pos[i] = cont; //se trovato salvo posizione
        } else {
          lista.splice(i, 1); //se non trovato elimino da lista
          lung--;
          i--;
        }
      }
      //se input cerca non vuoto creo tabella con valori trovati
      if ($('#qua_cerca').val() != "")
        $('#test').append(
                '<table>' +
                '<tbody  id="qua_tab_list">' +
                '<tr>' +
                '<th>select' +
                '</th>' +
                '<th>post' +
                '</th>' +
                '</tr>' +
                '</tbody>' +
                '</table>');

      for (var i = 0; i < lista.length; i++) {
        if (ck[i] == undefined) {
          ck[i] = ""; //se senza flag lo creo
        } else
          $('#qua_tab_list').append(
                  '<tr>' +
                  '<td>' +
                  '<button type="button" id="qua_but" data-id="' + lista[i] + '">+</button> ' +
                  '</td>' +
                  '<td>' +
                  name_id[lista[i]] +
                  '</td>' +
                  '</tr>'
                  );
      }

    });

    //genero lista selezionabili
    function popola() {
      list();
      var lung = lista.length;
      var cont = -1;
      var conta = -1;
      $('#test').children().remove(); // rimuovo vecchia lista front-end
      for (var x = 0; x < lung; x++) {
        conta++;
        if (ck[conta] == 'ins') { //controllo se inseriti e rimuovo da lista
          lista.splice(x, 1);
          x--;
          lung--;
        }
      }
      var lung = lista.length;
      for (var i = 0; i < lung; i++) { //segno posizione valori lista restante
        cont++;
        pos[i] = cont;
      }
      //genero nuova lista front-end valori selezionabili
      $('#test').append(
              '<table>' +
              '<tbody  id="qua_tab_list">' +
              '<tr>' +
              '<th>select' +
              '</th>' +
              '<th>post' +
              '</th>' +
              '</tr>' +
              '</tbody>' +
              '</table>');

      for (var i = 0; i < lista.length; i++) {
        if (ck[i] == undefined) {
          ck[i] = "";
        } else
          $('#qua_tab_list').append(
                  '<tr>' +
                  '<td>' +
                  '<button type="button" id="qua_but" data-id="' + lista[i] + '">+</button> ' +
                  '</td>' +
                  '<td>' +
                  name_id[lista[i]] +
                  '</td>' +
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

})(jQuery);
