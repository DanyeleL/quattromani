<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* Salvo i dati presenti negli input del cpt */

class Quattro_save {

  private $prefix;

  public function __construct() {
    add_action("save_post", array($this, 'save_quattro_meta_box')); //per funzione save cpt
    add_action("save_post", array($this, 'save_quattro_meta_box_panel')); //per funzione save metabox Articoli
    $this->prefix = 'qua_';
  }

// fuzione save cpt
  function save_quattro_meta_box($font) {

    //controlli per far salvare solo se arriva dal mio cpt

    if (!isset($_POST['callback_quattromani'])) {
      $this->save_quattro_meta_box_panel($font); //chiamo la funzione che salva il metabox di Articoli

      return;
    }
    // Verifico che il nonce sia valido.
    if (!wp_verify_nonce($_POST['callback_quattromani'], 'quattro_custom_post_type')) {
      return;
    }

    // Se è un autosalvataggio o non è stato premuto un submit non deve proseguire
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }


    $post_id = get_the_ID(); //carico l' ID del cpt dove mi trovo
    $countpost = count(get_posts(array('posts_per_page' => -1))); //conto numero cpt
    $prefix = $this->prefix;
    $meta_box_num_auth_value = '';
    $pref = $prefix . 'auth';
    $contr = $prefix . 'art';
    $temp;


    for ($num = 0; $num < $countpost; $num++) {
      $post_test = [];
      $temp;
      $prov;
      $pre = 'qua_post_' . $num;
      //$t=$_POST[$pre];
      if (isset($_POST[$pre]) && $_POST[$pre] != "") {
        $meta_list_value_post = explode(',', $_POST[$pre]);   // creo array del contenuto del POST  
        if (isset(get_post_meta($meta_list_value_post[1], $pref)[0])) {
          if ($meta_list_value_post[0] == "ins") { // controllo se presente il flag 'ins' per aggiungere
            if (is_array(get_post_meta($meta_list_value_post[1], $pref, true)))
              $lung = count(get_post_meta($meta_list_value_post[1], $pref, true)); //numero di valori se array
            else
              $lung = count(get_post_meta($meta_list_value_post[1], $pref)); // numero di valori se non array
            $temp = get_post_meta($meta_list_value_post[1], $pref, true); // copio valori in $temp
            $pres = 1; // flag controllo
            for ($i = 0; $i <= $lung + 1; $i++) {
              if (isset($temp[$i]) && $temp[$i] == $post_id) { //controllo se corrisponde all'id del cpt
                $pres = 0;
              }
            }
            if ($pres == 1) {
              if (!is_array($temp))
                $post_test[] = $temp; //creo copia di $temp
              else
                $post_test = $temp;
              array_push($post_test, $post_id); // aggiungo l'id del cpt 
              $post_un = array_unique($post_test); // assicuro che non sia duplicato
              update_post_meta($meta_list_value_post[1], $pref, $post_un); // aggiorno db
            }
          } else if ($meta_list_value_post[0] == "rem") { // controllo se presente il flag 'rem' pre rimuovere
            if (is_array(get_post_meta($meta_list_value_post[1], $pref, true))) {
              $lung = count(get_post_meta($meta_list_value_post[1], $pref, true)); //numero di valori se array
            } else {
              $lung = count(get_post_meta($meta_list_value_post[1], $pref)); // numero di valori se non array
            }

            $temp = get_post_meta($meta_list_value_post[1], $pref, true); // copio valori in $temp
            if (is_array($temp))
              $provv = get_post_meta($meta_list_value_post[1], $pref, true); // copio valori in $provv
            else { // se $temp non è array lo trasformo in array
              $provv[0] = $temp;
              $temp = $provv;
            }
            foreach ($provv as $key) { //carico un id alla volta
              $ind = array_search($key, $temp); //cerco la posizione nell'array
              if (isset($temp[$ind]) && $temp[$ind] == $post_id || isset($temp) && $temp == $post_id) {
                if (isset($temp[$ind]) && $temp[$ind] == $post_id)
                  unset($temp[$ind]); //se presente rimuovo dall'array
                else
                  $temp = "";
                $meta_list_value_reset = []; // creo array vuoto da mettere al posto di array rimosso
                update_post_meta($meta_list_value_post[1], $pref, $temp); //aggiorno db valore post
                update_post_meta($post_id, $pre, $meta_list_value_reset); //aggiorno db valore cpt
              }
            }
          }
        } else
          update_post_meta($meta_list_value_post[1], $pref, $post_id); //aggiorno db valore post
      } else
        $meta_list_value_post = ""; // creo array vuoto da mettere al posto di valore rimosso
      update_post_meta($post_id, $pre, $meta_list_value_post); //aggiorno db valore cpt
    }
  }

  //funzione save metabox Articoli
  function save_quattro_meta_box_panel($font) {

    if (!isset($_POST['metabox_post_panel'])) {
      return;
    }
    // Verifico che il nonce sia valido.
    if (!wp_verify_nonce($_POST['metabox_post_panel'], 'metabox_post')) {
      return;
    }

    // Se è un autosalvataggio o non è stato premuto un submit non deve proseguire
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (!isset($_POST['qua_art'])) {
      return;
    }

    //se nessuna valida torna su save cpt e quindi esce dal save

    $post_id = get_the_ID(); //prendo id del post
    $countpost = count(get_posts(array('posts_per_page' => -1, 'post_type' => 'quattromani'))); //conto cpt
    $prefix = $this->prefix;
    $meta_box_num_auth_value = '';
    $pref = $prefix . 'auth';
    $contr = $prefix . 'art';
    $temp;
    $provv;
    $meta_list_value = "";

    for ($num = 0; $num < $countpost; $num++) {
      $post_test = [];
      $temp;
      $pre = 'qua_post_' . $num;
      // $t=$_POST[$pre];
      if (isset($_POST[$pre]) && $_POST[$pre] != "") {
        $meta_list_value = explode(',', $_POST[$pre]); //creo array
        //  if ($_POST[$contr]!="yes"){
        $post_id = $meta_list_value[1]; //metto come post_id il vaolre in del cpt
        $meta_list_value[1] = get_the_ID(); // scambio il valore dell'id cpt con id post
        //  }          
        if (isset(get_post_meta($meta_list_value[1], $pref)[0])) {
          if ($meta_list_value[0] == "ins") { //verifico se devo inserire ( come nel save cpt )
            if (is_array(get_post_meta($meta_list_value[1], $pref, true)))
              $lung = count(get_post_meta($meta_list_value[1], $pref, true));
            else
              $lung = count(get_post_meta($meta_list_value[1], $pref));
            $temp = get_post_meta($meta_list_value[1], $pref, true);
            $pres = 1;
            for ($i = 0; $i <= $lung + 1; $i++) {
              if (isset($temp[$i]) && $temp[$i] == $post_id) {
                $pres = 0;
              }
            }
            if ($pres == 1) {
              if (!is_array($temp))
                $post_test[] = $temp;
              else
                $post_test = $temp;
              array_push($post_test, $post_id);
              $post_un = array_unique($post_test);
              update_post_meta($meta_list_value[1], $pref, $post_un);
            }
          } else if ($meta_list_value[0] == "rem") { // verifico se devo rimuovere ( come nel save cpt )
            if (is_array(get_post_meta($meta_list_value[1], $pref, true))) {
              $lung = count(get_post_meta($meta_list_value[1], $pref, true));
            } else {
              $lung = count(get_post_meta($meta_list_value[1], $pref));
            }

            $temp = get_post_meta($meta_list_value[1], $pref, true);
            if (is_array($temp)) //controllo se $temp è un array
              $provv = get_post_meta($meta_list_value[1], $pref, true); // copio valori in $provv
            else { // se $temp non è array lo trasformo in array
              $provv[0] = $temp;
              $temp = $provv;
            }
            foreach ($provv as $key) {
              $ind = array_search($key, $temp);
              if (isset($temp[$ind]) && $temp[$ind] == $post_id || isset($temp) && $temp == $post_id) {
                if (isset($temp[$ind]) && $temp[$ind] == $post_id)
                  unset($temp[$ind]);
                else
                  $temp = "";
                $meta_list_value_reset = [];
                update_post_meta($meta_list_value[1], $pref, $temp);
                update_post_meta($post_id, $pre, $meta_list_value_reset);
              }
            }
          }
        } else
          update_post_meta($meta_list_value[1], $pref, $post_id);
      } else
        $meta_list_value = "";
      update_post_meta($post_id, $pre, $meta_list_value);
    }
  }

}
