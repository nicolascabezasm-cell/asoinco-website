<?php
/**
 * Plantilla: noticia individual.
 *
 * @package asoinco
 */
get_header();
?>
<style>
.single-hero .cat{display:inline-block;font-size:.74rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--dorado);margin-bottom:12px}
.single-wrap{max-width:820px;margin:0 auto;padding:0 24px}
.article-body{font-size:1.06rem;color:var(--tinta);line-height:1.8;text-align:justify}
.article-body p{margin-bottom:20px}
.article-meta{color:var(--texto);font-size:.9rem;margin-bottom:8px;opacity:.85}
.socio-badge-lg{display:inline-block;font-size:.8rem;font-weight:700;color:#B9760C;background:rgba(245,166,35,.14);border-radius:20px;padding:6px 14px;margin-bottom:20px}
.back-link{display:inline-flex;align-items:center;gap:6px;font-weight:700;color:var(--verde);margin-top:40px}
/* Hero con imagen */
.single-hero-inner{display:flex;align-items:center;gap:48px}
.single-hero-text{flex:1;min-width:0}
.single-hero-img{flex:0 0 380px;max-width:380px}
.single-hero-img img{width:100%;border-radius:16px;box-shadow:0 16px 36px rgba(0,0,0,.3);object-fit:cover;max-height:280px;display:block}
@media(max-width:900px){.single-hero-inner{flex-direction:column}.single-hero-img{flex:none;max-width:100%;width:100%}.single-hero-img img{max-height:220px}}
</style>

<?php while ( have_posts() ) : the_post();
  $empresa = get_post_meta( get_the_ID(), 'noticia_socio_empresa', true );
  $cat_obj = get_the_category();
  $cat     = ! empty( $cat_obj ) ? $cat_obj[0]->name : '';
  $tipo    = get_post_meta( get_the_ID(), 'noticia_tipo', true );

  // Obtener imagen: primero imagen destacada, si no por ID de adjunto en el contenido
  $hero_img_url = '';
  $hero_img_alt = esc_attr( get_the_title() );
  if ( has_post_thumbnail() ) {
    $hero_img_url = get_the_post_thumbnail_url( null, 'large' );
  } else {
    $raw = get_the_content();
    // Extraer ID de adjunto del class wp-image-NNN y usar URL de WordPress
    if ( preg_match( '/wp-image-(\d+)/', $raw, $m ) ) {
      $hero_img_url = wp_get_attachment_image_url( (int) $m[1], 'large' );
    }
    // Fallback: buscar src directamente en el HTML crudo
    if ( ! $hero_img_url && preg_match( '/src=["\']([^"\']+)["\']/', $raw, $m ) ) {
      $hero_img_url = $m[1];
    }
    // Fallback: imágenes adjuntas al post en la base de datos
    if ( ! $hero_img_url ) {
      $attached = get_attached_media( 'image', get_the_ID() );
      if ( $attached ) {
        $first        = reset( $attached );
        $hero_img_url = wp_get_attachment_image_url( $first->ID, 'large' );
      }
    }
  }
?>
<header class="page-hero single-hero">
  <div class="glow" style="width:260px;height:260px;background:var(--verde);top:-90px;right:-40px"></div>
  <div class="wrap">
    <div class="crumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a> / <a href="<?php echo esc_url( home_url( '/noticias/' ) ); ?>">Noticias</a></div>
    <?php if ( $hero_img_url ) : ?>
    <div class="single-hero-inner">
      <div class="single-hero-text">
        <?php if ( $cat ) : ?><span class="cat"><?php echo esc_html( $cat ); ?></span><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <div class="article-meta"><?php echo esc_html( ucfirst( get_the_date( 'j \d\e F, Y' ) ) ); echo $tipo ? ' · ' . esc_html( $tipo ) : ''; ?></div>
      </div>
      <div class="single-hero-img"><img src="<?php echo esc_url( $hero_img_url ); ?>" alt="<?php echo $hero_img_alt; ?>"></div>
    </div>
    <?php else : ?>
      <?php if ( $cat ) : ?><span class="cat"><?php echo esc_html( $cat ); ?></span><?php endif; ?>
      <h1><?php the_title(); ?></h1>
      <div class="article-meta"><?php echo esc_html( ucfirst( get_the_date( 'j \d\e F, Y' ) ) ); echo $tipo ? ' · ' . esc_html( $tipo ) : ''; ?></div>
    <?php endif; ?>
  </div>
</header>

<section class="sec" style="padding-top:60px">
  <div class="single-wrap">
    <?php if ( $empresa ) : ?>
      <div class="socio-badge-lg">📢 Publicado por <?php echo esc_html( $empresa ); ?> (empresa socia)</div>
    <?php endif; ?>
    <div class="article-body"><?php the_content(); ?></div>
    <a class="back-link" href="<?php echo esc_url( home_url( '/noticias/' ) ); ?>">← Volver a Noticias</a>
  </div>
</section>

<?php endwhile; ?>

<?php
get_footer();
