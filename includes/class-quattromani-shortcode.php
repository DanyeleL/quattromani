<?php

class Quattro_short {

  function __construct() {

    $client = new Quattro_client();

    add_shortcode("quattromani", array($client, "quattro_short"));
  }

}
