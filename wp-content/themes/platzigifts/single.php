<?php get_header(); ?>
<main class="container my-3">
  <?php 
    if(have_posts()){
      while(have_posts()){
        the_post();
  ?>
    <h1 class='my-3'><?php the_title();?></h1>
    
    <div class="row">
        <div class="col-6">
          <?php the_post_thumbnail( 'large'); ?>
        </div>
        <div class="col-6">
          <?php the_content(); ?>
        </div>
    </div>
  <?php get_template_part( 'template-parts/post', 'navigation'); ?>
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


