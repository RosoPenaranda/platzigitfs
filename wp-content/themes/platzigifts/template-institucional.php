<?php 
//Template Name: Página Institucional
get_header(); 
$fields = get_fields();
?>
<main class="container">
  <?php 
    if(have_posts()){
      while(have_posts()){
        the_post();
  ?>
    <h1 class='my-3'><?php echo $fields['titulo'];?></h1>
    <img class="img-fluid my-4" src="<?php echo $fields['imagen'] ?>" >
    <hr>
    <?php the_content(); ?>
  <?php      
      }
    }
  ?>
</main>
<?php get_footer(); ?>