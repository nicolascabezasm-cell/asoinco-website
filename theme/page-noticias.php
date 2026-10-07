<?php
/**
 * Plantilla: Noticias (grid dinámico + filtro por categoría + paginación server-side).
 *
 * @package asoinco
 */
get_header();

$por_pagina = 15;
$paged      = isset( $_GET['pg'] ) ? max( 1, (int) $_GET['pg'] ) : 1;
$cat_filter = isset( $_GET['categoria'] ) ? sanitize_title( wp_unslash( $_GET['categoria'] ) ) : '';

$args = array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => $por_pagina,
	'paged'          => $paged,
);
if ( $cat_filter ) {
	$args['category_name'] = $cat_filter;
}
$noticias    = new WP_Query( $args );
$total_pages = $noticias->max_num_pages;

$cats     = get_categories( array( 'hide_empty' => true ) );
$base_url = home_url( '/noticias/' );
?>
<style>
.filters{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:32px}
.filters a{font-family:inherit;font-size:.85rem;font-weight:600;color:var(--navy);background:#fff;border:1.4px solid var(--borde);padding:9px 18px;border-radius:30px;transition:.2s}
.filters a:hover{border-color:var(--navy)}
.filters a.on{background:var(--navy);color:#fff;border-color:var(--navy)}
.ncard .cat.socio{color:#B9760C}
.ncard .socio-badge{display:inline-block;font-size:.68rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#B9760C;background:rgba(245,166,35,.14);border-radius:20px;padding:3px 10px;margin-top:10px;align-self:flex-start}
.ncard .imgph{height:200px;background:linear-gradient(135deg,var(--navy),var(--oscuro));display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.5);font-weight:700;font-size:.9rem}
.pager{display:flex;justify-content:center;gap:8px;margin-top:46px;flex-wrap:wrap}
.pager .page-numbers{display:inline-flex;align-items:center;justify-content:center;min-width:42px;height:42px;padding:0 14px;border:1.4px solid var(--borde);border-radius:10px;font-weight:700;font-size:.9rem;color:var(--navy);background:#fff;transition:.2s}
.pager a.page-numbers:hover{border-color:var(--navy)}
.pager .page-numbers.current{background:var(--navy);color:#fff;border-color:var(--navy)}
.pager .page-numbers.dots{border:0;background:transparent}
.hero-cta{margin-top:22px}
</style>

<header class="page-hero">
  <div class="glow" style="width:260px;height:260px;background:var(--verde);top:-90px;right:-40px"></div>
  <div class="wrap">
    <div class="crumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a> / Noticias</div>
    <span class="eyebrow">El gremio en acción</span>
    <h1>Noticias y actividades</h1>
    <p>Todo lo que pasa en ASOINCO: alianzas, gremio, formación, Expo y las novedades de nuestras empresas socias.</p>
    <div class="hero-cta"><a class="btn btn-dorado" href="<?php echo esc_url( home_url( '/publica-noticia/' ) ); ?>">¿Eres socio? Publica tu noticia →</a></div>
  </div>
</header>

<section class="sec">
  <div class="wrap">
    <div class="filters">
      <a class="<?php echo '' === $cat_filter ? 'on' : ''; ?>" href="<?php echo esc_url( $base_url ); ?>">Todas</a>
      <?php foreach ( $cats as $c ) : ?>
        <a class="<?php echo $cat_filter === $c->slug ? 'on' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'categoria', $c->slug, $base_url ) ); ?>"><?php echo esc_html( $c->name ); ?></a>
      <?php endforeach; ?>
    </div>

    <div class="news-grid">
      <?php
      if ( $noticias->have_posts() ) :
        while ( $noticias->have_posts() ) : $noticias->the_post();
          $empresa = get_post_meta( get_the_ID(), 'noticia_socio_empresa', true );
          $cat_obj = get_the_category();
          $cat     = ! empty( $cat_obj ) ? $cat_obj[0]->name : '';
          ?>
          <article class="ncard" onclick="location.href='<?php echo esc_url( get_permalink() ); ?>'">
            <div class="imgbox">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
              <?php else : ?>
                <div class="imgph">ASOINCO</div>
              <?php endif; ?>
            </div>
            <div class="body">
              <div class="cat<?php echo $empresa ? ' socio' : ''; ?>"><?php echo esc_html( $cat ); ?></div>
              <h3><?php the_title(); ?></h3>
              <?php if ( $empresa ) : ?>
                <span class="socio-badge">Publicado por <?php echo esc_html( $empresa ); ?></span>
              <?php endif; ?>
              <div class="date"><?php echo esc_html( ucfirst( get_the_date( 'F Y' ) ) ); ?></div>
            </div>
          </article>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <p>No hay noticias en esta categoría todavía.</p>
      <?php endif; ?>
    </div>

    <?php
    if ( $total_pages > 1 ) {
      echo '<div class="pager">';
      echo paginate_links( array( // phpcs:ignore WordPress.Security.EscapeOutput
        'base'      => $base_url . '%_%',
        'format'    => '?pg=%#%',
        'current'   => $paged,
        'total'     => $total_pages,
        'mid_size'  => 1,
        'add_args'  => $cat_filter ? array( 'categoria' => $cat_filter ) : false,
        'prev_text' => '← Anteriores',
        'next_text' => 'Siguientes →',
      ) );
      echo '</div>';
    }
    ?>
  </div>
</section>

<section class="sec ctaband">
  <div class="wrap">
    <h2>¿Tu empresa socia tiene una <em>novedad</em>?</h2>
    <div class="right"><p>Comparte los hitos y actividades de tu empresa con toda la red de ASOINCO.</p><a class="btn btn-verde" href="<?php echo esc_url( home_url( '/publica-noticia/' ) ); ?>">Publica tu noticia →</a></div>
  </div>
</section>

<?php
get_footer();
