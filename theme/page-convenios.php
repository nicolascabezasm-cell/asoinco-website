<?php
/**
 * Plantilla: Convenios (Club de Beneficios).
 *
 * @package asoinco
 */
get_header();
?>
<style>
.conv-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.conv-card{border:1px solid var(--borde);border-radius:16px;background:#fff;overflow:hidden;transition:.2s;cursor:pointer}
.conv-card:hover{transform:translateY(-4px);box-shadow:0 18px 40px rgba(15,27,46,.1);border-color:var(--verde)}
.conv-card img{width:100%;height:auto;display:block}
@media(max-width:980px){.conv-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.conv-grid{grid-template-columns:1fr}}
/* Lightbox */
.conv-lightbox{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(10,18,38,.85);backdrop-filter:blur(6px);opacity:0;visibility:hidden;transition:opacity .25s,visibility .25s}
.conv-lightbox.open{opacity:1;visibility:visible}
.conv-lightbox-inner{position:relative;max-width:min(860px,92vw);max-height:90vh;display:flex;align-items:center;justify-content:center}
.conv-lightbox-inner img{max-width:100%;max-height:88vh;border-radius:16px;box-shadow:0 32px 80px rgba(0,0,0,.5);transform:scale(.92);transition:transform .25s;display:block}
.conv-lightbox.open .conv-lightbox-inner img{transform:scale(1)}
.conv-lightbox-close{position:absolute;top:-18px;right:-18px;width:40px;height:40px;border-radius:50%;background:var(--verde);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;line-height:1;transition:.15s}
.conv-lightbox-close:hover{background:var(--dorado)}
</style>

<header class="page-hero">
  <div class="glow" style="width:260px;height:260px;background:var(--dorado);top:-90px;right:-40px"></div>
  <div class="wrap">
    <div class="crumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a> / Convenios</div>
    <span class="eyebrow">Club de Beneficios</span>
    <h1>Convenios para nuestros socios</h1>
    <p>Descuentos y beneficios exclusivos que ASOINCO negocia para las empresas socias y sus equipos. Un beneficio más de ser parte del gremio.</p>
  </div>
</header>

<section class="sec">
  <div class="wrap">
    <?php
    $convenios = new WP_Query( array(
      'post_type'      => 'convenio',
      'post_status'    => 'publish',
      'posts_per_page' => -1,
      'orderby'        => 'menu_order title',
      'order'          => 'ASC',
    ) );
    ?>
    <?php if ( $convenios->have_posts() ) : ?>
    <div class="conv-grid">
      <?php while ( $convenios->have_posts() ) : $convenios->the_post(); ?>
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="conv-card">
          <img src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'large' ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
        </div>
        <?php endif; ?>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<section class="sec ctaband">
  <div class="wrap">
    <h2>¿Tu empresa aún no es <em>socia</em>?</h2>
    <div class="right"><p>Accede a todos estos convenios y beneficios sumándote a ASOINCO.</p><a class="btn btn-verde" href="<?php echo esc_url( home_url( '/hazte-socio/' ) ); ?>">Hazte socio →</a></div>
  </div>
</section>


<!-- Lightbox -->
<div class="conv-lightbox" id="convLightbox" role="dialog" aria-modal="true">
  <div class="conv-lightbox-inner">
    <img src="" alt="" id="convLightboxImg">
    <button class="conv-lightbox-close" id="convLightboxClose" aria-label="Cerrar">&#x2715;</button>
  </div>
</div>
<script>
(function(){
  var lb = document.getElementById('convLightbox');
  var img = document.getElementById('convLightboxImg');
  var btn = document.getElementById('convLightboxClose');
  function open(src, alt){ img.src = src; img.alt = alt; lb.classList.add('open'); document.body.style.overflow='hidden'; }
  function close(){ lb.classList.remove('open'); document.body.style.overflow=''; }
  document.querySelectorAll('.conv-card').forEach(function(card){
    card.addEventListener('click', function(){
      var i = card.querySelector('img');
      if(i) open(i.src, i.alt);
    });
  });
  btn.addEventListener('click', close);
  lb.addEventListener('click', function(e){ if(e.target===lb) close(); });
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') close(); });
})();
</script>
<?php
get_footer();
