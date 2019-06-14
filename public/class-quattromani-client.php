<?php

if (!defined('WPINC')) {
  die;
}

class Quattro_client {

  public function quattro_short() {
    ob_start();
    $aut = 'qua_auth';
    $lista = [];
    $countpost = count(get_posts(array('posts_per_page' => -1, 'post_type' => 'quattromani'))); //conto numeo cpt
    $lista = get_post_meta(get_the_ID(array('posts_per_page' => -1)), $aut, true); //creo lista id post

    echo '<div class="autori">';
    if (isset($lista) && is_array($lista) && $lista != "") { // controllo se lista settata, array e non vuota
      foreach ($lista as $key) {
        for ($i = 0; $i < $countpost; $i++) {
          if (((get_posts(array('posts_per_page' => -1, 'post_type' => 'quattromani'))[$i])->ID) == $key) //verifico id
            echo '<span> ' . get_the_title($key) . ' </span>'; //spazio prima valore che stampo dall'id
        }
      }
    } else if ($lista != "") {
      for ($i = 0; $i < $countpost; $i++) {
        if (((get_posts(array('posts_per_page' => -1, 'post_type' => 'quattromani'))[$i])->ID) == $lista)
          echo '<span> ' . get_the_title($lista) . ' </span>'; //spazio prima valore che stampo dall'id
      }
    }
    echo '</div>';

    return ob_get_clean();
  }

}
