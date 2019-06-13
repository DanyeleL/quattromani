<?php

class Quattro_client{

	public function quattro_short(){
		ob_start();
		$aut='qua_auth';
		$lista=[];
		$countpost=count(get_posts(array('post_type'=> 'quattromani')));
		$lista=get_post_meta(get_the_ID(), $aut,true);
		echo '<div class="autori">';
		if(isset($lista) && is_array($lista) && $lista!= ""){
		foreach ($lista as $key) {
			for($i=0; $i<$countpost; $i++){
				if(((get_posts(array('post_type'=> 'quattromani'))[$i])->ID) == $key)
				echo '<span>'. get_the_title( $key ).' </span>';
			  }
		}
		}else if($lista!= ""){
			for($i=0; $i<$countpost; $i++){
				if(((get_posts(array('post_type'=> 'quattromani'))[$i])->ID) == $lista)
				echo '<span>'.get_the_title( $lista ).' </span>';
			  }
		}
		echo '</div>';
		
	  return ob_get_clean();
	  }
}