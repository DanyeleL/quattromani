<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Quattro_autori{
  
  private $prefix;
  
  function __construct() {
    
    $this->prefix = 'qua_';
  }
  
  public function lista_autori() {
    
    $prefix = $this->prefix;
    $countpost = count(get_posts(array('posts_per_page'=>-1,'post_type' => 'quattromani'),true));//conto numero cpt totali
    $aut = $prefix . 'auth';
    $lista = [];
    $listap = [];
  //$countp = count(get_posts());
    $id_presenti=[];
    $cont=0;
    $lista_id=[];
    $lista_name=[];
    $lista_auth=[];
    
    $listap = get_post_meta(get_the_ID(), $aut, true); //leggo valore salvato in qua_auth

    for ($i = 0; $i < $countpost; $i++) {
      if($countpost<=1){//serve per quando non ho cpt e ne aggiungo uno
        $lista_id[$i] = get_posts(array('posts_per_page'=>-1,'post_type' => 'quattromani'))[0]->ID;
        $lista_name[$i] = get_posts(array('posts_per_page'=>-1,'post_type' => 'quattromani'))[0]->post_title;
        $lista_auth[$i]=array($lista_name[$i]=>$lista_id[$i]);
      }else{
        $lista_id[] = get_posts(array('posts_per_page'=>-1,'post_type' => 'quattromani'))[$i]->ID; // cpt totali per id
        $lista_name[] = get_posts(array('posts_per_page'=>-1,'post_type' => 'quattromani'))[$i]->post_title; // cpt totali per title
        $lista_auth[]=array($lista_name[$i]=>$lista_id[$i]);
      }
    }
    if (isset($listap) && $listap != "") {
      foreach ($lista_id as $key) {
        $control = 0; //usato per flag di controllo
        if (is_array($listap)) {
          foreach ($listap as $keysel) {
            if ($keysel == $key || get_post_status($key) == 'trash') { //controllo se presente o in cestino
              $control = 1;
            }
          }
        } else if (!is_array($listap) && $listap == $key || get_post_status($key) == 'trash') {
          $control = 1;
        }
        if ($control == 0) {
          $id_presenti[$cont]=$key; //salvo id presenti
          $cont++;
        }
      }
    } else if(isset($listap)) {
      $countpost = count(get_posts(array('posts_per_page'=>-1,'post_type' => 'quattromani'))); //conto numero cpt
      for ($i = 0; $i < $countpost; $i++) {
        $key = (get_posts(array('posts_per_page'=>-1,'post_type' => 'quattromani'))[$i]->ID); //carico tutti i cpt
        $id_presenti[$cont]=$key;
        $cont++;
        }
      }
 //genero array con valiri che servono in panel e lista_auth_js     
 $ret=[$lista_id,$lista_name,$lista_auth,$id_presenti];
 return $ret;
}
 
}