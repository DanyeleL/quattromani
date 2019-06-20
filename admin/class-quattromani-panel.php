<?php

if (!defined('WPINC')) {
  die;
}
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Quattro_panel {

  private $prefix;

  function __construct() {

    add_action('add_meta_boxes', array($this, 'metabox'));

    add_action('init', array($this, 'quattro_custom_post_type'));

    add_action('add_meta_boxes', array($this, 'shortbox'));

    add_action('add_meta_boxes', array($this, 'metabox_post'));

    add_filter('manage_quattromani_posts_columns', array($this, 'set_custom_edit_quattromani_columns'));

    add_action('manage_quattromani_posts_custom_column', array($this, 'custom_quattromani_column'));

    add_filter('manage_posts_columns', array($this, 'set_custom_edit_posts_columns'));

    add_action('manage_posts_custom_column', array($this, 'custom_posts_column'));

    $this->prefix = 'qua_';
  }

  /* -----------Inizio custom-------------------- */

  /* ------quattromani_custom_post_type--------------- */
  /* aggiungo voce in barra laterale admin, creo parte di info post presenti e aggiungi post */

  public function quattro_custom_post_type() {
    register_post_type('quattromani',
            array(
                'labels' => array(
                    'name' => __('Autori', 'quattromani'),
                    'menu_name' => __('Quattromani', 'quattromani'),
                    'singular_name' => __('quattromani', 'quattromani'),
                    'all_items' => __('Autori', 'quattromani'),
                    'add_new' => __('Aggiungi Autori', 'quattromani'),
                    'add_new_item' => __('Aggiungi nuovo Autore', 'quattromani'),
                    'edit_item' => __('Modifica Autore', 'quattromani'),
                    'search_items' => __('Cerca Autore', 'quattromani'),
                    'not_found' => __('Nessun Autore trovato', 'quattromani'),
                    'not_found_in_trash' => __('Nessun Autore trovato nel cestino', 'quattromani'),
                    'view_items' => __('', 'quattromani'), // cancello voce visualizza autori
                    'view_item' => __('', 'quattromani') // cancello voce visualizza autori
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'quattromani'),
            )
    );
  }

  /* ---------- creo sezione tabella in cpt quattromani----------- */

  public function metabox() {
    $id = 'boxid';
    $title = 'Aggiungi a lista';
    $callback = array($this, 'callback_quattromani');
    $page = 'quattromani';
    add_meta_box($id, $title, $callback, $page);
  }

  /* -------creo contenuto sezione Aggiungi a lista---------- */

  public function callback_quattromani() {
    wp_nonce_field('quattro_custom_post_type', 'callback_quattromani');
    $prefix = $this->prefix;
    $num_auth = $prefix . 'num';
    $reg = $prefix . 'lista_js';
    $auth_val[] = "";
    $countpost = count(get_posts());

    if (get_the_ID() !== null) {

      if (!isset(get_post_meta(get_the_ID(), $num_auth)[0]) || get_post_meta(get_the_ID(), $num_auth)[0] == "")
        $val = 0;
      else
        $val = get_post_meta(get_the_ID(), $num_auth)[0];

      for ($ind = 0; $ind < $countpost; $ind++) {
        $aut = $prefix . 'post_' . $ind;
      }
    }


    $prefix = $this->prefix;
    $aut = $prefix . 'auth';

    $ret = new Quattro_post();
    $post = $ret->lista_post(); //carico funzione che controlla post inseriti e post presenti

    $auth_val = $post[0];       /*  carico valori   */
    $titolo_post = $post[1];    /*  da funzione     */
    $countpost = $post[2];      /*  lista_post()    */
    $posts_id = $post[3];       /*  per passarli    */
    $posts = $post[4];          /*  a file lista_js */

    //genero html per visualizzare la lista dei post selezionati e da selezionare
    echo '<input type="text" name="' . $prefix . 'num' . '" id="' . $prefix . 'num' . '" value="' . $val . '" hidden>';
    echo '<div style="display:inline-block; width:100%;">';
    echo '<div id="qua_1" style="float:left; margin:0"></div>';
    echo '<div style="float:left; width:30%;"><span>lista</span>';
    echo '<ul id="' . $prefix . 'lista' . '" class="qua_lista" style="float:left; width:100%;"></ul></div>';
    echo '<div style="float:left; width:30%;"><input type="text" name="qua_cerca" id="qua_cerca" style="width:90%;paddin-bottom:10px;" placeholder="Cerca">lista post:<div id="test" class="qua_lista" style="width:100%;"></div></div>';
    echo '</div>';

    //passo le variabili con i valori presi da lista_post() a lista_js
    wp_register_script($reg, plugin_dir_url(__FILE__) . 'js/lista_js.js');
    $quattro_val = array(
        'auth_val' => $auth_val,
        'titolo_post' => $titolo_post,
        'countpost' => $countpost,
        'posts_id' => $posts_id,
        'posts' => $posts
    );
    wp_localize_script($reg, 'quattro_val', $quattro_val);
    wp_enqueue_script($reg);
  }

//funzione per generare metabox in cpt con nome Shortcode  
  public function shortbox() {
    add_meta_box(
            "shortbox",
            "shortcode",
            array($this, 'shortbox_metabox_callback'), //richiamo la funzione che genera contenuto metabox in cpt
            'quattromani',
            'side'
    );
  }

//funzione che genera contenuto metabox in cpt
  public function shortbox_metabox_callback() {
    $prefix = $this->prefix;
    $test = '[quattromani]';
    echo '<input type="text" id="' . $prefix . 'short" name="' . $prefix . 'short" value="' . $test . '" readonly/>';
  }

  //funzione per generare metabox in Articoli con nome Quattromani 
  public function metabox_post() {
    add_meta_box(
            "metabox_post",
            "Quattromani",
            array($this, 'metabox_post_panel'),
            'post',
            'side'
    );
  }

  //funzione che genera contenuto metabox in Articoli
  public function metabox_post_panel() {
    wp_nonce_field('metabox_post', 'metabox_post_panel');
    $prefix = $this->prefix;
    $reg = $prefix . 'lista_auth_js';
    $short = '[quattromani]';

    //genero html per visualizzare la lista dei cpt selezionati e da selezionare
    echo '<p style="margin:0;">Shortcode</p>';
    echo '<div id="qua_1" style="float:left; margin:0"></div>';
    echo '<input type="text" id="' . $prefix . 'short" name="' . $prefix . 'short" value="' . $short . '" readonly/>';
    echo '<div style="width:100%;"><input type="text" name="qua_cerca_auth" id="qua_cerca_auth" style="width:90%;paddin-bottom:10px;" placeholder="Cerca">' . __('lista autori:', 'quattromani') . '<div id="qua_list_auth" class="qua_lista" style="width:100%;"></div>';
    echo 'lista selezionati:<ul id="' . $prefix . 'lista_auth' . '" class="qua_lista" style=" width:100%;"></ul></div>';
    echo '<input type="text" name="qua_art" id="qua_art" value="no" hidden>';

    $ret = new Quattro_autori();
    $autori = $ret->lista_autori(); //carico funzione che controlla cpt inseriti e cpt presenti

    $lista_auth = $autori[2];   /*    carico valori da      */
    $id_presenti = $autori[3];  /* funzione lista_autori()  */
    $lista_id = $autori[0];     /*    per passarli a        */
    $lista_name = $autori[1];   /*     lista_auth_js        */

    //passo le variabili con i valori presi da lista_post() a lista_auth_js 
    wp_register_script($reg, plugin_dir_url(__FILE__) . 'js/lista_auth_js.js');
    $quattro_val_auth = array(
        'auth_compl' => $lista_auth,
        'id_presenti' => $id_presenti,
        'lista_id' => $lista_id,
        'lista_name' => $lista_name,
    );
    wp_localize_script($reg, 'quattro_val_auth', $quattro_val_auth);
    wp_enqueue_script($reg);
  }

  /* Aggiungo colonna in cpt per visualizzare lista Articoli */

  function set_custom_edit_quattromani_columns($columns) {
     $rim=$columns;
    foreach ($rim as $type => $val) {
      if($type!='date' &&  $type!='title' && $type!="cb")
        unset($columns[$type]);
    }
    $temp = "";
    //unset($columns['Autori']);
    $columns['Articoli'] = __('Articoli', 'your_text_domain');
    foreach ($columns as $type => $val) {
      if ($type == 'date') {
        $col['Articoli'] = $temp;
      }
      $col[$type] = $val;
    }

    return $col;
  }

  /* Popolo colonna in cpt per visualizzare lista Articoli selezionati */

  function custom_quattromani_column() {
    $ret = new Quattro_post();
    $post = $ret->lista_post();

    $cont = 0;
    $id_presenti = $post[5];
    foreach ($id_presenti as $idp) {
      $cont++;
      if ($cont == 1)
        echo get_the_title($idp);
      elseif ($cont > 1 && $cont < 3)
        echo ', ' . get_the_title($idp);
      elseif ($cont == 3)
        echo ', ...';
    }
  }

  /* Aggiungo colonna in Articoli (post) per visualizzare lista Autori */

  function set_custom_edit_posts_columns($columns) {
    $temp = "";
    unset($columns['Articoli']); // disabilito colonna Articoli fuori da cpt
    $columns['Autori'] = __('Autori', 'your_text_domain');
    foreach ($columns as $type => $val) {
      if ($type == 'categories') {
        $col['Autori'] = $temp;
      }
      $col[$type] = $val;
    }

    return $col;
  }

  /* Popolo colonna in Articoli (post) per visualizzare lista Autori selezionati */

  function custom_posts_column($col) {
    $ret = new Quattro_autori();
    $autori = $ret->lista_autori();
    if ($col == 'Autori') {
      $cont = 0;
      $id_presenti = $autori[3];
      $lista_id = $autori[0];
      foreach ($lista_id as $id) {
        $ok = 0;
        foreach ($id_presenti as $idp) {
          if ($idp == $id)
            $ok = 1;
        }
        if ($ok == 0) {
          $cont++;
          if ($cont == 1)
            echo get_the_title($id);
          elseif ($cont > 1 && $cont < 3)
            echo ', ' . get_the_title($id);
          elseif ($cont == 3)
            echo ', ...';
        }
      }
    }
  }

}
