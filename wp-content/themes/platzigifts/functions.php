<?php

function  init_template(){ //el nombre lo decidimos nosotros

  add_theme_support('post-thumbnails');
  add_theme_support( 'title-tag');

  register_nav_menus( 
    array(
      'top_menu' => 'Menu Principal'
    )
    );
  
}

add_action( 'after_setup_theme', 'init_template');

function assets(){
  //registramos bootstrap para usarlo como framework css
  wp_register_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', '', '4.5.2', 'all' );

  wp_register_style( 'montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap', '', '1.0', 'all' );

  //se carga la liberia en el navegador
  wp_enqueue_style('estilos' , get_stylesheet_uri(), array('bootstrap', 'montserrat'), '1.3', 'all' );

  //realizamos la carga de los js

  wp_register_script( 'popper', "https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js", '', '1.16.1', true );

  wp_enqueue_script( 'bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js", array('jquery', 'popper'), '4.1.1', true );

  wp_enqueue_script( 'custom', get_template_directory_uri().'/assets/js/custom.js', '', '1.0', true );

  wp_localize_script( 'custom', 'pg', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'apiurl' => home_url('wp-json/pg/v1/')
  ) );

}
//  el hoock wp_enqueue se ejecuta al inicio de la carga de la pagina
add_action( 'wp_enqueue_scripts', 'assets');

function sidebar(){
  register_sidebar(
    array(
      'name' => 'Pie de página',
      'id' => 'footer',
      'description'=> 'Zona de Widgets para pie de página',
      'before_title' => '<p>',
      'after_title' => '</p>',
      'before_widget'=> '<div id="%1$s" class="%2$s">',
      'after_widget' => '</div>'
    )
    );
}

add_action( 'widgets_init', 'sidebar' );

function productos_type(){
  $labels = array(
    'name' => 'Productos',
    'singular_name' => 'Producto',
    'menu_name' =>  'Productos',
  );
  $args = array(
    'label' => 'Productos',
    'descriptions' => 'Productos de Platzi',
    'labels' => $labels,
    'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
    'public' => true,
    'show_in_menu'=> true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-cart',
    'can_export' => true,
    'publicly_queryable' => true,
    'rewrite' => true,
    'show_in_rest' => true,

  );
  register_post_type( 'producto', $args);
}

add_action( 'init', 'productos_type');

function pgRegisterTax(){
  $args = array(
    'hierarchical' => true,
    'labels' => array(
      'name' => 'Categorías de Productos',
      'singular_name' => 'Categoría de Productos'
    ),
    'show_in_nav_menu' => true, 
    'show_admin_column' => true,
    'rewrite' => array('slug' => 'categoria-productos')
  );
  register_taxonomy( 'categoria-productos', array('producto'), $args );
}

add_action( 'init', 'pgRegisterTax');

function pgFiltroProductos(){
  $args = array(
    'post_type' => 'producto',
    'post_per_page' => -1,
    'order' => 'ASC',
    'orderby' => 'title',
    'post__not_in' => array($post->ID)
  );

  if($_POST['categoria']){
    $args['tax_query'] = array(
      array(
        'taxonomy' => 'categoria-productos',
        'field' => 'slug',
        'terms' => $_POST['categoria']
      )
    );
  }

  $productos = new WP_Query($args);

  if($productos->have_posts()){
    $return = array();
    while($productos->have_posts()){
      $productos->the_post();
      $return[] = array(
        'imagen' => get_the_post_thumbnail( get_the_id(), 'large' ),
        'link' => get_the_permalink(),
        'titulo' => get_the_title()
      );
    }

    wp_send_json($return);
  }
}

add_action( "wp_ajax_nopriv_pgFiltroProductos", "pgFiltroProductos");
add_action( "wp_ajax_pgFiltroProductos", "pgFiltroProductos");


function novedadesAPI(){
  register_rest_route( 
    'pg/v1',
    '/novedades/(?P<cantidad>\d+)', 
    array(
      'methods' => 'GET',
      'callback' => 'pedidoNovedades'
    )
  );
}

function pedidoNovedades($data){
  $args = array(
    'post_type' => 'post',
    'post_per_page' => $data['cantidad'],
    'order' => 'ASC',
    'orderby' => 'title'
  );

  $novedades = new WP_Query($args);

  if($novedades->have_posts()){
    $return = array();
    while($novedades->have_posts()){
      $novedades->the_post();
      $return[] = array(
        'imagen' => get_the_post_thumbnail( get_the_id(), 'large' ),
        'link' => get_the_permalink(),
        'titulo' => get_the_title()
      );
    }

   return $return;
  }
}

add_action('rest_api_init', 'novedadesAPI');


function pgRegisterBlock(){
  $assets = include_once get_template_directory().'/blocks/build/index.asset.php';
  
  wp_register_script( 
    'pg-block', 
    get_template_directory_uri().'/blocks/build/index.js', 
    $assets['dependencies'], 
    $assets['version'] 
  );

  register_block_type(
    'pg/basic',
    array(
      'editor_script' => 'pg-block'
    )
    );
    
  }

  add_action('init', 'pgRegisterBlock');

  