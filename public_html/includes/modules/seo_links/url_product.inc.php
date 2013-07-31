<?php
  
  class url_product {

  	function __construct($system) {
      $this->system = &$system;
    }
    
    function process($parsed_link, $language_code) {
      
      if (!isset($parsed_link['query']['product_id'])) return false;
      
      /*
      $product_query = $this->system->database->query(
        "select product_id, name from ". DB_TABLE_PRODUCTS_INFO ."
        where product_id = '". (int)$parsed_link['query']['product_id'] ."'
        and language_code = '". $this->system->database->input($language_code) ."'
        limit 1;"
      );
      $product = $this->system->database->fetch($product_query);
      */
      
      $product = new ref_product($parsed_link['query']['product_id'], $language_code);
      
      if (!$product->id) die($product->id);
      
      $parsed_link['path'] = '';
      if (!empty($parsed_link['query']['category_id']) && !empty($product->categories)) {
        $parsed_link['path'] = WS_DIR_HTTP_HOME;
        foreach ($product->categories as $category_id => $category_name) $parsed_link['path'] .= $this->system->functions->general_path_friendly($category_name[$language_code]) .'-c-'. $category_id .'/';
      } else if (!empty($product->manufacturer)) {
        $parsed_link['path'] = WS_DIR_HTTP_HOME . $this->system->functions->general_path_friendly($product->manufacturer['name']) .'-m-'. $product->manufacturer['id'] .'/';
      } else {
        $parsed_link['path'] = WS_DIR_HTTP_HOME;
      }
      $parsed_link['path'] .= $this->system->functions->general_path_friendly($product->name[$language_code]) .'-p-'. $product->id;
      
      unset($parsed_link['query']['product_id']);
      
      return $parsed_link;
    }
  }
  
?>