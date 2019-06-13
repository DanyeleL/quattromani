<?php

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
    
    add_filter('manage_quattromani_posts_columns', array($this,'set_custom_edit_quattromani_columns'));

    add_action('manage_quattromani_posts_custom_column', array($this,'custom_quattromani_column'));

    $this->prefix = 'qua_';
  }

  /* -----------Inizio custom-------------------- */

  /* ------quattromani_custom_post_type--------------- */
  /* aggiungo voce in barra laterale admin, creo parte di info post presenti e aggiungi post */

  public function quattro_custom_post_type() {
    register_post_type('quattromani',
            array(
                'labels' => array(
                    'name' => __('Autori','quattromani'),
                    'menu_name'=> __('Quattromani','quattromani'),
                    'singular_name' => __('quattromani','quattromani'),
                    'all_items'=> __('Autori','quattromani'),
                    'add_new'=> __('Aggiungi Autori','quattromani'),
                    'add_new_item'=>__('Aggiungi nuovo Autore','quattromani'),
                    'edit_item'=>__('Modifica Autore','quattromani'),
                    'search_items' => __('Cerca Autore','quattromani'),
                    'not_found' =>  __('Nessun Autore trovato','quattromani'),
                    'not_found_in_trash' => __('Nessun Autore trovato nel cestino','quattromani'),
                    'view_items' => __('','quattromani'),
                    'view_item' => __('','quattromani')
                    
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

  /* -------creo contenuto sezione tabella (metabox)---------- */

  public function callback_quattromani() {
    wp_nonce_field( 'quattro_custom_post_type', 'callback_quattromani' );
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
    //var_dump(get_posts());

    $countpost = count(get_posts(array('post_type' => 'post')));
    $prefix = $this->prefix;
    $aut = $prefix . 'auth';
    $lista = [];
    $listap = [];
    $countp = count(get_posts());
    $id_presenti=[];
    $cont=0;
     
    for ($i = 0; $i < $countpost; $i++) {
      $lista_id[] = get_posts(array('post_type' => 'post'))[$i]->ID;
      $lista_name[] = get_posts(array('post_type' => 'post'))[$i]->post_title;
      $lista_auth[]=array($lista_name[$i]=>$lista_id[$i]);
    }
    if (isset($lista_id) && $lista_id != "") {
      foreach ($lista_id as $key) {
        $control = 0;
        $listap = get_post_meta($key, $aut, true);
        if (is_array($listap)) {
          foreach ($listap as $keysel) {
            if ($keysel == get_the_ID() || get_post_status($key) == 'trash') {
              $control = 1;
            }
          }        
        }else if(isset($lista_id) && $lista_id != "" &&  $listap==get_the_ID()){
          $control = 1;
        }
        if ($control == 1) {
          $id_presenti[$cont]=$key;
          $cont++;
        }
      }
    } else {
      $countpost = count(get_posts(array('post_type' => 'quattromani')));
      for ($i = 0; $i < $countpost; $i++) {
        $key = (get_posts(array('post_type' => 'quattromani'))[$i]->ID);
        $id_presenti[$cont]=$key;
        $cont++;
        }
      }
    
    $contat=0;
    foreach($lista_id as $indice){
       $auth_val[$contat]="";
      foreach($id_presenti as $idp){
        if($lista_id[$contat]==$idp){
          $auth_val[$contat]='ins';
        }
      }
      $contat++;
    }

    for ($i = 0; $i < $countpost; $i++) {
      $titolo_post[] = get_posts()[$i]->post_title;
      $posts_id[] = get_posts()[$i]->ID;
      $posts[] = array($titolo_post[$i] => $posts_id[$i]);
    }
    echo '<input type="text" name="' . $prefix . 'num' . '" id="' . $prefix . 'num' . '" value="' . $val . '" hidden>';
    echo '<div style="display:inline-block; width:100%;">';
    echo '<div id="1" style="float:left; margin:0"></div>';
    echo '<div style="float:left; width:30%;"><span>lista</span>';
    echo '<ul id="' . $prefix . 'lista' . '" class="qua_lista" style="float:left; width:100%;"></ul></div>';
    echo '<div style="float:left; width:30%;"><input type="text" name="qua_cerca" id="qua_cerca" style="width:90%;paddin-bottom:10px;">lista post:<div id="test" class="qua_lista" style="width:100%;"></div></div>';
    echo '</div>';

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
    
  public function shortbox() {
    add_meta_box(
            "shortbox",
            "shortcode",
            array($this, 'shortbox_metabox_callback'),
            'quattromani',
            'side'
    );
  }

  public function shortbox_metabox_callback() {
    $prefix = $this->prefix;
    $test = '[quattromani]';
    echo '<input type="text" id="' . $prefix . 'short" name="' . $prefix . 'short" value="' . $test . '" readonly/>';
  }

  public function metabox_post() {
    add_meta_box(
            "metabox_post",
            "Quattromani",
            array($this, 'metabox_post_panel'),
            'post',
            'side'
    );
  }

  public function metabox_post_panel() {
    
    $countpost = count(get_posts(array('post_type' => 'quattromani')));
    $prefix = $this->prefix;
    $reg = $prefix . 'lista_auth_js';
    $aut = $prefix . 'auth';
    $lista = [];
    $listap = [];
    $short = '[quattromani]';
    $countp = count(get_posts());
    $id_presenti=[];
    $cont=0;
    
    echo '<p style="margin:0;">Shortcode</p>';
    echo '<div id="1" style="float:left; margin:0"></div>';
    echo '<input type="text" id="' . $prefix . 'short" name="' . $prefix . 'short" value="' . $short . '" readonly/>';
    echo '<div style="width:100%;"><input type="text" name="qua_cerca_auth" id="qua_cerca_auth" style="width:90%;paddin-bottom:10px;">lista post:<div id="qua_list_auth" class="qua_lista" style="width:100%;"></div>';
    echo '<ul id="' . $prefix . 'lista_auth' . '" class="qua_lista" style=" width:100%;"></ul></div>';
    echo '<input type="text" name="qua_art" id="qua_art" value="no" hidden>';
    
    $listap = get_post_meta(get_the_ID(), $aut, true);

    for ($i = 0; $i < $countpost; $i++) {
      $lista_id[] = get_posts(array('post_type' => 'quattromani'))[$i]->ID;
      $lista_name[] = get_posts(array('post_type' => 'quattromani'))[$i]->post_title;
      $lista_auth[]=array($lista_name[$i]=>$lista_id[$i]);
    }
    if (isset($listap) && $listap != "") {
      foreach ($lista_id as $key) {
        $control = 0;
        if (is_array($listap)) {
          foreach ($listap as $keysel) {
            if ($keysel == $key || get_post_status($key) == 'trash') {
              $control = 1;
            }
          }
        } else if (!is_array($listap) && $listap == $key || get_post_status($key) == 'trash') {
          $control = 1;
        }
        if ($control == 0) {

          $id_presenti[$cont]=$key;
          $cont++;
        }
      }
    } else {
      $countpost = count(get_posts(array('post_type' => 'quattromani')));
      for ($i = 0; $i < $countpost; $i++) {
        $key = (get_posts(array('post_type' => 'quattromani'))[$i]->ID);
        $id_presenti[$cont]=$key;
        $cont++;
        }
      }
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

    /* Aggiungo colonna in cpt per visualizzare lo shortcode */
  function set_custom_edit_quattromani_columns($columns) {
    //unset( $columns['date'] );
    $columns['Articoli'] = __('Articoli', 'your_text_domain');
    foreach ($columns as $type => $val) {
      if ($type == 'date') {
        $col['Articoli'] = $temp;
      }
      $col[$type] = $val;
    }

    return $col;
  }

  function custom_quattromani_column() {
      
    echo 'work in progress';
  }
}
