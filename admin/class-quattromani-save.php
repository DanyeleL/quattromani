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
    add_action("save_post", array($this, 'save_quattro_meta_box'));
    add_action("save_post", array($this, 'save_quattro_meta_box_panel'));
    $this->prefix = 'qua_';
  }

  function save_quattro_meta_box($font) {

    if (!isset($_POST['callback_quattromani']))
        {
            $this->save_quattro_meta_box_panel($font);
            
            return;
        }
        // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['callback_quattromani'], 'quattro_custom_post_type'))
        {              
          return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        {
            return;
        }


    $post_id = get_the_ID();
    $countpost = count(get_posts());
    $prefix = $this->prefix;
    $meta_box_num_auth_value = '';
    $pref = $prefix . 'auth';
    $contr = $prefix . 'art';
    $temp;


    for ($num = 0; $num < $countpost; $num++) {
      $post_test = [];
      $temp;
      $pre = 'qua_post_' . $num;
      $t=$_POST[$pre];
      if (isset($_POST[$pre]) && $_POST[$pre]!="") {
          $meta_list_value_post=explode(',',$_POST[$pre]);     
        if (isset(get_post_meta($meta_list_value_post[1], $pref)[0])) {
          if ($meta_list_value_post[0] == "ins") {
            if (is_array(get_post_meta($meta_list_value_post[1], $pref, true)))
              $lung = count(get_post_meta($meta_list_value_post[1], $pref, true));
            else
              $lung = count(get_post_meta($meta_list_value_post[1], $pref));
            $temp = get_post_meta($meta_list_value_post[1], $pref, true);
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
              $post_un=array_unique($post_test);
              update_post_meta($meta_list_value_post[1], $pref, $post_un);
            }
          } else if ($meta_list_value_post[0] == "rem") {
            if (is_array(get_post_meta($meta_list_value_post[1], $pref, true))) {
              $lung = count(get_post_meta($meta_list_value_post[1], $pref, true));
            }
           else {
            $lung = count(get_post_meta($meta_list_value_post[1], $pref));
          }
          
          $temp = get_post_meta($meta_list_value_post[1], $pref, true);

          for ($i = 0; $i <= $lung + 1; $i++) {
            if (isset($temp[$i]) && $temp[$i] == $post_id || isset($temp) && $temp == $post_id) {
              if(isset($temp[$i]) && $temp[$i] == $post_id)
                       unset($temp[$i]); 
              else $temp="";
              $meta_list_value_reset = [];
              update_post_meta($meta_list_value_post[1], $pref, $temp);
              update_post_meta($post_id, $pre, $meta_list_value_reset);
            }
          }

        }
      }else update_post_meta($meta_list_value_post[1], $pref, $post_id);
     } else
        $meta_list_value_post = "";
      update_post_meta($post_id, $pre, $meta_list_value_post);
    }

  }
  
   function save_quattro_meta_box_panel($font) {

    if (!isset($_POST['qua_art']))
        {            
            return;
        }
        // Verify that the nonce is valid.
   /* if (!wp_verify_nonce($_POST['callback_quattromani1'], 'metabox_post_panel'))
        {              
          return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        {
            return;
        }*/

    $post_id = get_the_ID();
    $countpost = count(get_posts(array('post_type'=> 'quattromani')));
    $prefix = $this->prefix;
    $meta_box_num_auth_value = '';
    $pref = $prefix . 'auth';
    $contr = $prefix . 'art';
   // $temp;
    $meta_list_value="";    

    for ($num = 0; $num < $countpost; $num++) {
      $post_test = [];
      $temp;
      $pre = 'qua_post_' . $num;
     // $t=$_POST[$pre];
      if (isset($_POST[$pre]) && $_POST[$pre]!="") {
          $meta_list_value=explode(',',$_POST[$pre]); 
        //  if ($_POST[$contr]!="yes"){
            $post_id=$meta_list_value[1];
            $meta_list_value[1]=get_the_ID();
        //  }          
        if (isset(get_post_meta($meta_list_value[1], $pref)[0])) {
          if ($meta_list_value[0] == "ins") {
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
              $post_un=array_unique($post_test);
              update_post_meta($meta_list_value[1], $pref, $post_un);
            }
          } else if ($meta_list_value[0] == "rem") {
            if (is_array(get_post_meta($meta_list_value[1], $pref, true))) {
              $lung = count(get_post_meta($meta_list_value[1], $pref, true));
            }
           else {
            $lung = count(get_post_meta($meta_list_value[1], $pref));
          }
          
          $temp = get_post_meta($meta_list_value[1], $pref, true);

          for ($i = 0; $i <= $lung + 1; $i++) {
            if (isset($temp[$i]) && $temp[$i] == $post_id) {
              unset($temp[$i]); 
              $meta_list_value_reset = [];
              update_post_meta($meta_list_value[1], $pref, $temp);
              update_post_meta($post_id, $pre, $meta_list_value_reset);
            }
          }

        }
      }else update_post_meta($meta_list_value[1], $pref, $post_id);
     } else
        $meta_list_value = "";
      update_post_meta($post_id, $pre, $meta_list_value);
    }
  }


}
