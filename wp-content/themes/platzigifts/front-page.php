<?php get_header(); ?>
<main class="container my-3">
  <?php 
    if(have_posts()){
      while(have_posts()){ 
        the_post();
  ?>
    <h1 class='my-3 text-center'><?php the_title();?>!!</h1>
    <?php the_content(); ?>

    <?php      
      } //fin while
    } //fin if
  ?>
    
    <div class="lista-productos my-5 ">
        <h2 class="text-center">Productos</h2>
        <div class="row">
        <div class="col-12">
          <select class="form-control my-5" name="categorias-productos" id="categorias-productos">
            <option value="">Todas las categorias</option>
            <?php 
              $terms = get_terms('categoria-productos', array('hide_empty'=> true));
              foreach($terms as $term){
                echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
              }
            ?>
          </select>
        </div>
        <div  id="resultado-productos" class="row">
        <?php 
          $args = array(
            'post_type' => 'producto',
            'post_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'title'
          );
          $productos = new WP_Query($args);

          if($productos->have_posts()){
            while($productos->have_posts()){
              $productos->the_post();
        ?>
          <div class="col-12 col-md-6 col-lg-4">
              <figure>
                <?php the_post_thumbnail( 'large');?>
              </figure>
              <h4 class="my-3 text-center">
                <a href="<?php the_permalink()?>">
                  <?php the_title()?>
                </a>
              </h4>
          </div>

        <?php      
            } //fin while
          } //fin if
        ?>
        </div>
        </div>
    </div>    
    <div class="row">
      <div class="col-12">
          <h1>Novedades</h1>
      </div>
    </div>
    <div id="resultado-novedades" class="row">
      
    </div>
</main>
<?php get_footer(); ?>
