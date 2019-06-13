<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Quattro_post{
  
  private $prefix;
  
  function __construct() {
    
    $this->prefix = 'qua_';
  }
  
  public function lista_post() {
    
    $countpost = count(get_posts(array('posts_per_page'=>-1,'post_type' => 'post')),true); //conto numero post totali presenti
    $prefix = $this->prefix;
   // $test=(get_posts(array('posts_per_page'=>-1,'post_type' => 'post')));
    $aut = $prefix . 'auth';
    $lista = [];
    $listap = "";
    $id_presenti=[];
    $cont=0;
    $auth_val=[];
    $titolo_post=[];
    $posts_id=[];
    $posts=[];
     
    for ($i = 0; $i < $countpost; $i++) {
      if($countpost<=1){ // serve per quando non ho ancora post e ne aggiungo uno
        $lista_id[$i] = get_posts(array('posts_per_page'=>-1,'post_type' => 'post'))[0]->ID;
        $lista_name[$i] = get_posts(array('posts_per_page'=>-1,'post_type' => 'post'))[0]->post_title;
        $lista_auth[$i]=array($lista_name[$i]=>$lista_id[$i]);
      }else{
      $lista_id[$i] = get_posts(array('posts_per_page'=>-1,'post_type' => 'post'))[$i]->ID; //post totali per id
      $lista_name[$i] = get_posts(array('posts_per_page'=>-1,'post_type' => 'post'))[$i]->post_title;//post totali per titolo
      $lista_auth[$i]=array($lista_name[$i]=>$lista_id[$i]);//array associativo nome=>id
    }
  }
    if (isset($lista_id) && $lista_id != "") { //verifico presenza di post
      foreach ($lista_id as $key) {
        $control = 0; // usato come flag di controllo
        $listap = get_post_meta($key, $aut, true);//carico valori presenti in wp_postmeta con id post
        if (is_array($listap)) { //verifico se array per foreach
          foreach ($listap as $keysel) {
            if ($keysel == get_the_ID() || get_post_status($key) == 'trash') { // controllo se id corrisponde o in cestino
              $control = 1;
            }
          }        
        }else if(isset($lista_id) && $lista_id != "" &&  $listap==get_the_ID()){
          $control = 1;
        }
        if ($control == 1) { 
          $id_presenti[$cont]=$key; //se id non corrisponde lo salvo
          $cont++;
        }
      }
    } else if(isset($lista_id)) { //controllo se lista è settata
      for ($i = 0; $i < $countpost; $i++) {
        $key = (get_posts(array('posts_per_page'=>-1,'post_type' => 'post'))[$i]->ID); //carico tutti gli id post
        $id_presenti[$cont]=$key; //salvo gli id post caricati
        $cont++;
        }
      }
      // verifico se e quali id sono selezionati e  popolo il valore $auth_val che uso nel lista_js
   if(isset($lista_id)) {
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
   }
   // carico numero totale di post con titolo per lista_js
    for ($i = 0; $i < $countpost; $i++) {
      $titolo_post[] = get_posts(array('posts_per_page'=>-1))[$i]->post_title;
      $posts_id[] = get_posts(array('posts_per_page'=>-1))[$i]->ID;
      $posts[] = array($titolo_post[$i] => $posts_id[$i]);
    }
    //genero un return con un array di tutte i dati che servono in panel e lista_js
     $ret=[$auth_val,$titolo_post,$countpost,$posts_id,$posts,$id_presenti];
     return $ret;
  }
}