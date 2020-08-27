<?php get_header(); ?>
<main class="container my-3">
  <?php 
    if(have_posts()){
      while(have_posts()){
        the_post();
        $taxonomy= get_the_terms(get_the_ID(), 'categoria-productos');
  ?>
    <h1 class='my-3'><?php the_title();?></h1>
    
    <div class="row">
        <div class="col-md-6 col-12">
          <?php the_post_thumbnail( 'large'); ?>
        </div>
        <div class="col-md-6 col-12">
          <?php echo do_shortcode( '[contact-form-7 id="49" title="Formulario de contacto 1"]' ); ?>
        </div>
        <div class="col-12">
          <?php the_content(); ?>
        </div>
    </div>
  
  <?php //generar una lista de productos relacionados, para ello creamos un loop personalizado
    $args = array(
      'post_type' => 'producto',
      'post_per_page' => '6',
      'order' => 'ASC',
      'orderby' => 'title',
      'post__not_in' => array($post->ID),
      'tax_query' => array(
        array(
          'taxonomy' => 'categoria-productos',
          'field' => 'slug',
          'terms' => $taxonomy[0]->slug
        )
      )
    );

    $productos = new WP_Query($args);

    if($productos->have_posts()){
  ?>
  <div class="row text-center justify-content-around productos-relacionados">
  <div class="col-12"><h3>Productos Relacionados </h3></div>
      <?php while($productos->have_posts()){ 
        $productos->the_post();  
      ?>
        <div class="col-3 my-3">
          <?php the_post_thumbnail('thumbnail') ?>
          <h4 class="text-center"> 
            <a class="text-decorator-none text-color-primary" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h4>
        </div>
      <?php } //fin while productos ?>
  </div>
  <?php }//fin if productos ?>    

  <?php      
      }//fin while
    }//fin if
  ?>
</main>
<?php get_footer();
/*
thumbnail ----- 150 x 150 PX
medium -------- 300 x 300 PX
large ------- 1024 x 1024 PX
full ----- La resolución original de la imagen cuando la subimos
*/
?>


