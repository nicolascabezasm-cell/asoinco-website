<?php
/**
 * Plantilla: Directorio de socios.
 * Muestra solo logo + rubro + web (pedido de Javier Julio).
 *
 * @package asoinco
 */
get_header();

// Socios publicados.
$socios = new WP_Query( array(
	'post_type'      => 'socio',
	'posts_per_page' => -1,
	'orderby'        => 'title',
	'order'          => 'ASC',
	'post_status'    => 'publish',
) );

// Rubros con socios (para el filtro).
$rubros = get_terms( array( 'taxonomy' => 'rubro', 'hide_empty' => true ) );
?>
<style>
.dir-searchbar{background:#fff;border:1px solid var(--borde);border-radius:16px;padding:18px;display:grid;grid-template-columns:1fr auto;gap:14px;box-shadow:0 8px 26px rgba(15,27,46,.06);margin-bottom:14px}
.dir-searchbar .q{display:flex;align-items:center;gap:10px;border:1.4px solid var(--borde);border-radius:10px;padding:0 14px}
.dir-searchbar .q svg{width:18px;height:18px;stroke:#94A3B8;fill:none;stroke-width:2;flex:none}
.dir-searchbar .q input{border:0;outline:none;font-family:inherit;font-size:.95rem;padding:13px 0;width:100%;color:var(--tinta)}
.dir-searchbar select{font-family:inherit;font-size:.92rem;color:var(--navy);font-weight:600;border:1.4px solid var(--borde);border-radius:10px;padding:13px 16px;background:#fff;cursor:pointer;min-width:190px}
.dir-count{font-size:.88rem;color:var(--texto);margin-bottom:24px}
.dir-count b{color:var(--navy)}
.dir-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.empcard{border:1px solid var(--borde);border-radius:14px;padding:24px;background:#fff;transition:.2s;display:flex;flex-direction:column;align-items:flex-start;min-height:250px}
.empcard:hover{transform:translateY(-3px);box-shadow:0 14px 30px rgba(15,27,46,.1);border-color:var(--navy)}
.empcard .logobox{width:100%;height:100px;display:flex;align-items:center;justify-content:flex-start;margin-bottom:8px}
.empcard .logobox img{max-height:96px;max-width:90%;object-fit:contain}
.empcard .mono{width:56px;height:56px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:1.1rem;background:var(--navy)}
.empcard .empname{font-weight:700;color:var(--navy);font-size:1rem;line-height:1.25;margin:8px 0 10px}
.empcard .pill{display:inline-block;font-size:.72rem;font-weight:700;padding:5px 12px;border-radius:20px;background:rgba(27,58,107,.08);color:var(--navy);margin-bottom:14px}
.empcard .weblink{margin-top:auto;font-size:.85rem;font-weight:700;color:var(--verde);display:inline-flex;align-items:center;gap:6px}
.empcard .weblink:hover{color:var(--navy)}
.empcard .nolink{margin-top:auto;font-size:.82rem;color:var(--texto)}
.dir-empty{display:none;text-align:center;padding:60px 20px;color:var(--texto)}
.dir-empty.show{display:block}
@media(max-width:980px){.dir-searchbar{grid-template-columns:1fr}.dir-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.dir-grid{grid-template-columns:1fr}}
</style>

<header class="page-hero">
  <div class="glow" style="width:260px;height:260px;background:var(--verde);top:-90px;right:-40px"></div>
  <div class="wrap">
    <div class="crumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a> / Directorio de socios</div>
    <span class="eyebrow">Nuestra red empresarial</span>
    <h1>Directorio de socios</h1>
    <p>Las empresas industriales y proveedoras de la Región de Coquimbo que forman parte de ASOINCO. Encuentra proveedores y aliados por rubro.</p>
  </div>
</header>

<section class="sec">
  <div class="wrap">
    <div class="dir-searchbar">
      <div class="q"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg><input type="text" id="dq" placeholder="Buscar empresa…"></div>
      <select id="drubro">
        <option value="">Todos los rubros</option>
        <?php foreach ( $rubros as $r ) : ?>
          <option value="<?php echo esc_attr( $r->name ); ?>"><?php echo esc_html( $r->name ); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="dir-count" id="dcount"></div>

    <div class="dir-grid" id="dgrid">
      <?php
      if ( $socios->have_posts() ) :
        while ( $socios->have_posts() ) : $socios->the_post();
          $nombre = get_the_title();
          $web    = get_post_meta( get_the_ID(), 'socio_web', true );
          $terms  = get_the_terms( get_the_ID(), 'rubro' );
          $rubro  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
          // Monograma para fallback sin logo.
          $palabras = array_filter( explode( ' ', $nombre ), function ( $w ) { return mb_strlen( $w ) > 2; } );
          $ini = '';
          foreach ( array_slice( $palabras, 0, 2 ) as $w ) { $ini .= mb_substr( $w, 0, 1 ); }
          $ini = mb_strtoupper( $ini );
          ?>
          <div class="empcard" data-name="<?php echo esc_attr( mb_strtolower( $nombre ) ); ?>" data-rubro="<?php echo esc_attr( $rubro ); ?>">
            <div class="logobox">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium', array( 'alt' => esc_attr( $nombre ) ) ); ?>
              <?php else : ?>
                <div class="mono"><?php echo esc_html( $ini ); ?></div>
              <?php endif; ?>
            </div>
            <div class="empname"><?php echo esc_html( $nombre ); ?></div>
            <?php if ( $rubro ) : ?><span class="pill"><?php echo esc_html( $rubro ); ?></span><?php endif; ?>
            <?php if ( $web ) : ?>
              <a class="weblink" href="<?php echo esc_url( $web ); ?>" target="_blank" rel="noopener">Visitar sitio →</a>
            <?php else : ?>
              <span class="nolink">Sitio web no disponible</span>
            <?php endif; ?>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <p>Aún no hay socios cargados.</p>
      <?php endif; ?>
    </div>

    <div class="dir-empty" id="dempty"><h3 style="color:var(--navy);margin-bottom:6px">Sin resultados</h3><p>No encontramos empresas con esos filtros. Prueba con otros términos.</p></div>
  </div>
</section>

<section class="sec ctaband">
  <div class="wrap">
    <h2>¿Tu empresa debería estar en este <em>directorio</em>?</h2>
    <div class="right"><p>Súmate a ASOINCO y gana visibilidad ante la minería, el Estado y los grandes compradores de la región.</p><a class="btn btn-verde" href="<?php echo esc_url( home_url( '/hazte-socio/' ) ); ?>">Hazte socio →</a></div>
  </div>
</section>

<script>
(function () {
  var q = document.getElementById('dq'),
      sel = document.getElementById('drubro'),
      grid = document.getElementById('dgrid'),
      count = document.getElementById('dcount'),
      empty = document.getElementById('dempty'),
      cards = [].slice.call(grid.querySelectorAll('.empcard'));
  function apply() {
    var t = q.value.trim().toLowerCase(), r = sel.value, shown = 0;
    cards.forEach(function (c) {
      var okQ = !t || c.getAttribute('data-name').indexOf(t) > -1;
      var okR = !r || c.getAttribute('data-rubro') === r;
      var vis = okQ && okR; c.style.display = vis ? '' : 'none'; if (vis) shown++;
    });
    count.innerHTML = 'Mostrando <b>' + shown + '</b> de <b>' + cards.length + '</b> empresas socias';
    empty.classList.toggle('show', shown === 0);
  }
  q.addEventListener('input', apply);
  sel.addEventListener('change', apply);
  apply();
})();
</script>

<?php
get_footer();
