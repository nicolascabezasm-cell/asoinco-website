<?php
/**
 * Portada (Home) de ASOINCO.
 *
 * @package asoinco
 */
get_header();
?>
<style>
.hero-anim .ha-title{display:block;font-size:clamp(3rem,8vw,6rem);font-weight:800;letter-spacing:-.02em;line-height:1;animation:heroSlideUp .7s cubic-bezier(.22,1,.36,1) .2s both}
.hero-anim .ha-sub{display:block;font-size:clamp(1.1rem,2.5vw,1.6rem);font-weight:400;letter-spacing:.04em;margin-top:12px;text-transform:uppercase;color:rgba(255,255,255,.85);animation:heroSlideUp .7s cubic-bezier(.22,1,.36,1) .75s both}
.hero-anim{margin:0;padding:0}
@keyframes heroSlideUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:none}}
</style>
<?php

// Banner promocional de la Expo (se activa desde el panel de la Expo).
$expo_pg = get_page_by_path( 'expo' );
$expo_id = $expo_pg ? $expo_pg->ID : 0;
if ( $expo_id && 'si' === get_post_meta( $expo_id, 'expo_banner_activo', true ) ) :
	$b_txt = get_post_meta( $expo_id, 'expo_banner_texto', true );
	$b_cta = get_post_meta( $expo_id, 'expo_banner_cta_texto', true );
	$b_lnk = get_post_meta( $expo_id, 'expo_banner_cta_link', true );
	?>
<div class="expo-banner">
  <div class="wrap">
    <span class="eb-txt"><?php echo esc_html( $b_txt ); ?></span>
    <?php if ( $b_cta && $b_lnk ) : ?><a class="eb-cta" href="<?php echo esc_url( $b_lnk ); ?>"><?php echo esc_html( $b_cta ); ?></a><?php endif; ?>
  </div>
</div>
<?php endif; ?>

<!-- 3 HERO -->
<header class="hero">
  <div class="hero-slide active" style="background-image:url('https://www.asoinco.cl/wp-content/uploads/2025/05/Terminal-puerto-coquimbo.jpg')"></div>
  <div class="hero-slide" style="background-image:url('https://www.asoinco.cl/wp-content/uploads/2025/05/EXPO-ASOINCO-2024-PT3-130-scaled.jpg')"></div>
  <div class="hero-slide" style="background-image:url('https://www.asoinco.cl/wp-content/uploads/2025/05/EXPO-ASOINCO-2024-PT3-53-scaled.jpg')"></div>
  <div class="hero-overlay"></div>
  <div class="hero-inner">
    <span class="hero-badge"><span class="dot"></span> Gremio Industrial · Región de Coquimbo · Desde 2006</span>
    <h1 class="hero-anim">
      <span class="ha-title">ASOINCO</span>
      <span class="ha-sub">Impulsando Empresas, Conectando el Futuro</span>
    </h1>
    <p>En ASOINCO somos el puente entre el sector empresarial y el desarrollo regional. Conectamos oportunidades, representamos al gremio y fomentamos el trabajo en red.</p>
    <div class="hero-btns">
      <a class="btn btn-verde" href="<?php echo esc_url( home_url( '/hazte-socio/' ) ); ?>">Hazte socio →</a>
      <a class="btn btn-outline" href="#socios">Conoce a nuestros socios</a>
    </div>
    <div class="hero-stats">
      <div class="hstat"><div class="n">133</div><div class="l">Empresas Socias</div></div>
      <div class="hstat"><div class="n">17</div><div class="l">Rubros Distintos</div></div>
      <div class="hstat"><div class="n">9.000</div><div class="l">Empleos Directos</div></div>
      <div class="hstat"><div class="n">$250 MM</div><div class="l">Ventas Estimadas</div></div>
    </div>
  </div>
  <div class="hero-nav">
    <button class="hero-arrow" id="prev" aria-label="Anterior"><svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg></button>
    <button class="hero-arrow" id="next" aria-label="Siguiente"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></button>
  </div>
  <div class="hero-dots" id="dots"></div>
</header>

<!-- 4 SOCIOS (prioridad) -->
<section class="socios" id="socios">
  <div class="head">
    <span class="eyebrow">Nuestros socios</span>
    <h3><em>133 empresas</em> ya son parte de ASOINCO</h3>
    <p>La industria de Coquimbo, unida en un solo gremio.</p>
  </div>
  <?php
  $home_socios = new WP_Query( array( 'post_type' => 'socio', 'posts_per_page' => 20, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'publish' ) );
  $cols  = array( '#1B3A6B', '#5BAD5E', '#F5A623', '#2D6CB3' );
  $chips = '';
  if ( $home_socios->have_posts() ) {
    $i = 0;
    while ( $home_socios->have_posts() ) {
      $home_socios->the_post();
      $nombre   = get_the_title();
      $palabras = array_filter( explode( ' ', $nombre ), function ( $w ) { return mb_strlen( $w ) > 2; } );
      $ini = '';
      foreach ( array_slice( $palabras, 0, 2 ) as $w ) { $ini .= mb_substr( $w, 0, 1 ); }
      $ini = mb_strtoupper( $ini );
      if ( has_post_thumbnail() ) {
        $logo   = get_the_post_thumbnail( get_the_ID(), 'medium', array( 'style' => 'max-height:92px;max-width:240px;width:auto;object-fit:contain', 'alt' => esc_attr( $nombre ) ) );
        $chips .= '<div class="schip only-logo">' . $logo . '</div>';
      } else {
        $col    = $cols[ $i % count( $cols ) ];
        $chips .= '<div class="schip"><span class="mono" style="background:' . esc_attr( $col ) . '">' . esc_html( $ini ) . '</span><span class="nm">' . esc_html( $nombre ) . '</span></div>';
      }
      $i++;
    }
    wp_reset_postdata();
  }
  if ( '' === $chips ) {
    $chips = '<div class="schip"><span class="mono" style="background:#1B3A6B">A</span><span class="nm">Próximamente</span></div>';
  }
  // Se duplica para el desplazamiento continuo del carrusel.
  echo '<div class="marquee">' . $chips . $chips . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
  ?>
</section>

<!-- 5 ¿POR QUÉ ASOINCO? -->
<section class="sec">
  <div class="wrap">
    <div class="why-head">
      <span class="eyebrow">Por qué existimos</span>
      <h2>Una sola voz para la industria de Coquimbo</h2>
    </div>
    <div class="why-grid">
      <div class="why"><a href="<?php echo esc_url( home_url( '/quienes-somos/#mision' ) ); ?>" style="text-decoration:none;color:inherit"><div class="ico ico-a"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg></div><h3>Representación Estratégica</h3><p>Articulamos una voz colectiva que influye en decisiones para el desarrollo del sector productivo, con el respaldo de más de 150 empresas.</p></a></div>
      <div class="why"><a href="<?php echo esc_url( home_url( '/quienes-somos/#vision' ) ); ?>" style="text-decoration:none;color:inherit"><div class="ico ico-v"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div><h3>Desarrollo Productivo</h3><p>Promovemos el crecimiento de la industria a través de la articulación, oportunidades y fortalecimiento del ecosistema empresarial.</p></a></div>
      <div class="why"><a href="<?php echo esc_url( home_url( '/quienes-somos/#historia' ) ); ?>" style="text-decoration:none;color:inherit"><div class="ico ico-d"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><h3>Comunidad Industrial</h3><p>Construimos una red de empresas que colaboran y crecen juntas, fortaleciendo la industria local.</p></a></div>
    </div>
  </div>
</section>

<!-- 6 BENEFICIOS -->
<section class="sec ben">
  <div class="wrap">
    <div class="ben-head"><span class="eyebrow">Por qué asociarse</span><h2>IMPULSAMOS TU EMPRESA</h2></div>
    <div class="ben-grid">
      <div class="bcard"><div class="emoji">💼</div><h3>OPORTUNIDADES DE NEGOCIO</h3><p>Conectamos tu empresa con oportunidades reales mediante redes de proveedores, alianzas estratégicas y acceso a nuevos mercados.</p></div>
      <div class="bcard"><div class="emoji">🛡️</div><h3>LA VOZ DE LA INDUSTRIA</h3><p>Somos la voz de la industria, representando y defendiendo los intereses del sector ante el sector público-privado, académico y actores relevantes en el desarrollo regional.</p></div>
      <div class="bcard"><div class="emoji">🎟️</div><h3>BENEFICIOS EXCLUSIVOS</h3><p>Accede a convenios exclusivos en salud, educación, servicios y más, pensados para tu empresa y su equipo.</p></div>
      <div class="bcard"><div class="emoji">👥</div><h3>RED Y COMUNIDAD</h3><p>Sé parte de una comunidad activa que comparte, colabora y crece junta a través de encuentros, eventos y espacios de trabajo.</p></div>
    </div>
  </div>
</section>

<!-- 7 DIRECTORIO TEASER -->
<section class="sec" id="directorio">
  <div class="wrap">
    <div class="dir-banner">
      <div class="t">
        <span class="eyebrow">ECOSISTEMA DE OPORTUNIDADES</span>
        <h2>RED DE EMPRESAS</h2>
        <p><b>133 empresas industriales</b> de Coquimbo en un solo lugar. Busca por rubro, comuna y capacidades.</p>
        <div class="dir-rubros">
          <span>⚙️ Metalmecánica</span><span>🚚 Logística</span><span>⚡ Energía</span><span>🏗️ Construcción</span><span>⛏️ Minería</span>
        </div>
      </div>
      <a class="btn btn-dorado" href="<?php echo esc_url( home_url( '/directorio/' ) ); ?>">EXPLORAR EMPRESAS →</a>
    </div>
  </div>
</section>

<!-- 7.5 CONVENIOS TEASER -->
<section class="sec" style="padding-top:0">
  <div class="wrap">
    <div class="dir-banner" style="background:linear-gradient(120deg,var(--verde),#237A4E)">
      <div class="t">
        <span class="eyebrow" style="color:#FFFFFF">GENERA AHORRO PARA TU EMPRESA Y BENEFICIOS PARA TUS COLABORADORES</span>
        <h2>BENEFICIOS PARA SOCIOS</h2>
        <p>Descuentos en salud, educación, combustible, seguros y más para las empresas socias y sus equipos.</p>
      </div>
      <a class="btn btn-dorado" href="<?php echo esc_url( home_url( '/convenios/' ) ); ?>">Ver convenios →</a>
    </div>
  </div>
</section>

<!-- 8 EXPO -->
<section class="expo" id="expo">
  <div class="ov"></div>
  <div class="expo-inner">
    <span class="eyebrow">EL PRINCIPAL ENCUENTRO DE NEGOCIOS DE LA REGIÓN</span>
    <h2>Expo ASOINCO — donde la industria hace negocios</h2>
    <div class="expo-stats">
      <div><div class="n">80</div><div class="l">empresas participantes</div></div>
      <div><div class="n">5.000</div><div class="l">visitantes</div></div>
      <div><div class="n">US$1M</div><div class="l">en negocios concretados</div></div>
    </div>
    <a class="btn btn-dorado" href="<?php echo esc_url( home_url( '/expo/' ) ); ?>">Conoce la Expo ASOINCO →</a>
  </div>
</section>

<!-- 9 NOTICIAS -->
<section class="sec" id="noticias">
  <div class="wrap">
    <span class="eyebrow">Noticias y actividades</span>
    <h2 style="margin-bottom:28px">El gremio en acción</h2>
    <div class="news-grid">
      <?php
      $home_news = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3, 'post_status' => 'publish' ) );
      if ( $home_news->have_posts() ) :
        while ( $home_news->have_posts() ) : $home_news->the_post();
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
          <?php
        endwhile;
        wp_reset_postdata();
      else :
        echo '<p>Pronto publicaremos noticias.</p>';
      endif;
      ?>
    </div>
    <div style="text-align:center;margin-top:36px"><a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/noticias/' ) ); ?>">Ver todas las noticias →</a></div>
  </div>
</section>

<!-- 10 INSTAGRAM -->
<section class="sec" style="padding-top:0">
  <div class="wrap">
    <div class="ig-head"><span class="eyebrow">Comunidad</span><h2>@asoinco.ag</h2></div>
    <?php
    // Feed de Instagram en vivo (Smash Balloon). Feed ID=2 conectado a @asoinco.ag.
    if ( shortcode_exists( 'instagram-feed' ) ) {
      echo do_shortcode( '[instagram-feed feed=2 num=6 cols=6 showheader=false showbutton=false imagepadding=8]' ); // phpcs:ignore WordPress.Security.EscapeOutput
    } else {
      echo '<div class="ig-grid" id="igGrid"></div>'; // fallback: main.js lo rellena con las fotos reales.
    }
    ?>
    <div class="ig-more"><a class="btn btn-ghost" href="https://instagram.com/asoinco.ag">Ver más en @asoinco.ag →</a></div>
  </div>
</section>

<!-- 11 CTA -->
<section class="sec cta" id="cta">
  <div class="wrap">
    <h2>¿Tu empresa aún no es parte de <em>ASOINCO</em>?</h2>
    <div class="right">
      <p>Súmate a las 133 empresas que ya representan a la industria de Coquimbo. Accede a negocios, representación y comunidad.</p>
      <a class="btn btn-verde" href="<?php echo esc_url( home_url( '/hazte-socio/' ) ); ?>">Hazte socio →</a>
    </div>
  </div>
</section>

<?php
get_footer();
