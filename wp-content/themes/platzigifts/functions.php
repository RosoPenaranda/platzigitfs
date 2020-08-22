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
  wp_enqueue_style('estilos' , get_stylesheet_uri(), array('bootstrap', 'montserrat'), '1.2', 'all' );

  //realizamos la carga de los js

  wp_register_script( 'popper', "https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js", '', '1.16.1', true );

  wp_enqueue_script( 'bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js", array('jquery', 'popper'), '4.1.1', true );

  wp_enqueue_script( 'custom', get_template_directory_uri().'/assets/js/custom.js', '', '1.0', true );

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



