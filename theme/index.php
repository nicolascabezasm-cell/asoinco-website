<?php
/**
 * Plantilla de respaldo (fallback).
 *
 * @package asoinco
 */
get_header();
?>
<main class="sec">
  <div class="wrap">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <article style="margin-bottom:40px">
          <span class="eyebrow">ASOINCO</span>
          <h2 style="margin-bottom:16px"><?php the_title(); ?></h2>
          <div class="lead"><?php the_content(); ?></div>
        </article>
      <?php endwhile; ?>
    <?php else : ?>
      <span class="eyebrow">ASOINCO</span>
      <h2>No hay contenido para mostrar todavía.</h2>
    <?php endif; ?>
  </div>
</main>
<?php
get_footer();
