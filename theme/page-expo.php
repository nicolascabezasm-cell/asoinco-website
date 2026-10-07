<?php
/**
 * Plantilla: Expo ASOINCO (100% autoadministrable desde el panel).
 *
 * @package asoinco
 */
get_header();

$pid  = get_queried_object_id();
$em   = function ( $k ) use ( $pid ) { return get_post_meta( $pid, $k, true ); };
$show = function ( $k ) use ( $em ) { return 'no' !== $em( 'expo_show_' . $k ); };
$ext  = function ( $u ) { $u = trim( (string) $u ); return ( '' === $u || '#' === $u[0] || 0 === strpos( $u, home_url() ) ) ? '' : ' target="_blank" rel="noopener"'; };
$estado_stand = isset( $_GET['stand'] ) ? sanitize_key( $_GET['stand'] ) : '';

$wa      = preg_replace( '/[^0-9]/', '', (string) $em( 'expo_whatsapp' ) );
$wa_link = $wa ? 'https://wa.me/' . $wa . '?text=' . rawurlencode( 'Hola ASOINCO, quiero información sobre un stand en la Expo.' ) : '';
?>
<style>
.expo-hero{position:relative;min-height:82vh;display:flex;align-items:center;color:#fff;overflow:hidden;background:var(--oscuro)}
.expo-hero .bg{position:absolute;inset:0;background:url('https://www.asoinco.cl/wp-content/uploads/2025/05/EXPO-ASOINCO-2024-35-scaled.jpg') center/cover}
.expo-hero .ov{position:absolute;inset:0;background:linear-gradient(100deg,rgba(6,14,31,.95) 30%,rgba(27,58,107,.7) 100%)}
.expo-hero .wrap{position:relative;z-index:2;padding:70px 48px}
.expo-hero .badge{display:inline-flex;align-items:center;gap:9px;background:rgba(91,173,94,.16);border:1px solid rgba(91,173,94,.5);color:#8FE29A;font-size:.82rem;font-weight:700;padding:8px 16px;border-radius:30px;margin-bottom:22px}
.expo-hero h1{font-size:3.4rem;font-weight:800;max-width:19ch;line-height:1.05}
.expo-hero .tag{font-size:1.15rem;color:rgba(255,255,255,.9);max-width:46ch;margin:16px 0 22px}
.expo-hero .meta{display:flex;gap:26px;flex-wrap:wrap;margin-bottom:30px;color:rgba(255,255,255,.9);font-size:.98rem;font-weight:600}
.expo-hero .meta span{display:inline-flex;align-items:center;gap:8px}
.expo-hero .meta svg{width:19px;height:19px;stroke:var(--dorado);fill:none;stroke-width:2}
.expo-hero .ctas{display:flex;gap:12px;flex-wrap:wrap}
.wa-btn{background:#25D366;color:#fff}
.wa-btn:hover{background:#1fb457}
/* COUNTDOWN */
.countdown{background:var(--navy);color:#fff;padding:26px 48px}
.countdown .wrap{display:flex;align-items:center;justify-content:center;gap:14px;flex-wrap:wrap}
.cd-box{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px 20px;text-align:center;min-width:82px}
.cd-box .n{font-size:2rem;font-weight:800;color:var(--dorado);line-height:1}
.cd-box .l{font-size:.72rem;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.75);margin-top:4px}
.cd-lead{font-weight:700;font-size:1.05rem;margin-right:10px}
/* STATS */
.expo-stats{display:flex;flex-wrap:wrap;justify-content:center;gap:20px;position:relative;z-index:3}
.expo-stat{background:#fff;border-radius:16px;padding:26px 30px;box-shadow:0 18px 40px rgba(15,27,46,.14);text-align:center;min-width:170px}
.expo-stat .n{font-size:2.5rem;font-weight:800;color:var(--navy);line-height:1}
.expo-stat .l{font-size:.88rem;color:var(--texto);margin-top:6px}
/* BENEFICIOS */
.ben-head{text-align:center;margin-bottom:40px}
.ben2{display:grid;grid-template-columns:1fr 1fr;gap:30px}
.ben2 .col{background:#fff;border-radius:20px;padding:36px 32px;box-shadow:0 16px 40px rgba(15,27,46,.08);transition:.2s}
.ben2 .col:hover{transform:translateY(-4px);box-shadow:0 24px 50px rgba(15,27,46,.12)}
.ben2 .col.exp{border-top:5px solid var(--dorado)}
.ben2 .col.vis{border-top:5px solid var(--verde)}
.ben2 .col h3.tt{font-size:1.5rem;color:var(--navy);margin-bottom:24px;display:flex;align-items:center;gap:12px}
.ben2 .b{display:flex;gap:15px;margin-bottom:22px;padding-bottom:22px;border-bottom:1px dashed var(--borde)}
.ben2 .b:last-child{border-bottom:0;margin-bottom:0;padding-bottom:0}
.ben2 .b .ico{width:48px;height:48px;border-radius:13px;display:flex;align-items:center;justify-content:center;flex:none;font-size:1.35rem}
.ben2 .b h4{font-size:1.08rem;color:var(--navy);margin-bottom:4px}
.ben2 .b p{font-size:.93rem;color:var(--texto);line-height:1.55}
.ico-exp{background:rgba(245,166,35,.16)}
.ico-vis{background:rgba(91,173,94,.16)}
/* GALERÍA */
.expo-gal{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.expo-gal a{border-radius:12px;overflow:hidden;aspect-ratio:1}
.expo-gal img{width:100%;height:100%;object-fit:cover;transition:.4s}
.expo-gal a:hover img{transform:scale(1.05)}
.expo-gal .gal-hidden{display:none}
/* AUSPICIADORES */
.ausp-row{display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:18px}
.ausp{height:90px;min-width:150px;padding:0 26px;background:#fff;border:1px solid var(--borde);border-radius:12px;display:flex;align-items:center;justify-content:center}
.ausp img{max-height:66px;max-width:170px;object-fit:contain}
.ausp .txt{font-weight:800;color:var(--navy);font-size:1.05rem}
.ausp-section{background:var(--gris);padding:60px 0;overflow:hidden}
.ausp-marquee{display:flex;width:max-content;align-items:center;gap:22px;padding:0 11px;animation:scroll 22s linear infinite}
.ausp-marquee:hover{animation-play-state:paused}
.ausp-static{display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:22px}
/* PLANO / MAPA */
.expo-plano img{width:100%;border-radius:16px;border:1px solid var(--borde)}
.expo-map iframe{width:100%;height:380px;border:0;border-radius:16px}
/* FORM STAND */
.stand-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start}
.stand-cta h2{color:var(--navy);font-size:2rem;margin-bottom:14px}
.stand-cta p{color:var(--texto);margin-bottom:20px}
.stand-alt{margin-top:22px;padding-top:22px;border-top:1px solid var(--borde);font-size:.92rem;color:var(--texto)}
.stand-info{display:grid;grid-template-columns:1fr 1fr;gap:44px;align-items:center}
.stand-info .incluye h3{color:var(--navy);font-size:1.3rem;margin-bottom:18px}
.stand-info .incluye ul{list-style:none;padding:0;display:grid;gap:11px}
.stand-info .incluye li{position:relative;padding-left:28px;color:var(--tinta);font-size:.96rem}
.stand-info .incluye li::before{content:'✔';position:absolute;left:0;color:var(--verde);font-weight:800}
.precios{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.pcard{border:1px solid var(--borde);border-radius:16px;overflow:hidden;background:#fff;box-shadow:0 10px 26px rgba(15,27,46,.07)}
.pcard-h{background:var(--navy);color:#fff;font-weight:800;font-size:1.15rem;text-align:center;padding:16px}
.prow{padding:16px;text-align:center;border-bottom:1px solid var(--borde)}
.prow:last-child{border-bottom:0}
.prow span{display:block;font-size:.74rem;color:var(--texto);font-weight:700;text-transform:uppercase;letter-spacing:.05em}
.prow b{display:block;font-size:1.55rem;color:var(--navy);margin-top:4px}
.prow.socio b{color:var(--verde)}
.precio-nota{text-align:center;color:var(--texto);font-size:.9rem;margin-top:22px}
@media(max-width:820px){.stand-info{grid-template-columns:1fr;gap:30px}}
.alert{border-radius:12px;padding:16px 20px;margin-bottom:20px;font-size:.95rem}
.alert.ok{background:rgba(91,173,94,.12);border:1px solid rgba(91,173,94,.4);color:#2F6B33}
.alert.err{background:rgba(220,60,60,.1);border:1px solid rgba(220,60,60,.4);color:#9A2B2B}
/* CTAs marketing */
.btn-lg{padding:16px 30px;font-size:1rem}
.btn-crono{display:inline-flex;background:linear-gradient(90deg,var(--verde),var(--dorado));color:var(--navy);font-weight:800;font-size:1.08rem;padding:17px 34px;border-radius:40px;box-shadow:0 12px 30px rgba(91,173,94,.35);margin-bottom:20px}
.btn-crono:hover{filter:brightness(1.05);transform:translateY(-2px);opacity:1}
.btn-visita{background:linear-gradient(90deg,var(--navy),var(--verde));color:#fff}
.btn-visita:hover{filter:brightness(1.08);opacity:1}
/* Tarjetas Dónde/Cuándo/Horario */
.infocards{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.infocard{background:#fff;border:1px solid var(--borde);border-radius:18px;padding:32px 24px;text-align:center;box-shadow:0 12px 30px rgba(15,27,46,.08);transition:.2s}
.infocard:hover{transform:translateY(-4px);box-shadow:0 20px 44px rgba(15,27,46,.13)}
.infocard .ic{width:62px;height:62px;border-radius:50%;background:var(--navy);color:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
.infocard .ic svg{width:28px;height:28px;stroke:currentColor;fill:none;stroke-width:2}
.infocard h3{font-size:1.5rem;color:var(--navy);margin-bottom:8px}
.infocard p{color:var(--texto);font-size:1.02rem}
/* Capacitaciones */
.capacita{background:linear-gradient(120deg,rgba(19,41,77,.9),rgba(35,122,78,.86)),url('https://www.asoinco.cl/wp-content/uploads/2025/05/EXPO-ASOINCO-2024-73-scaled.jpg') center/cover;color:#fff;text-align:center;border-radius:20px;padding:64px 40px;position:relative;overflow:hidden}
.capacita .eyebrow{color:var(--dorado)}
.capacita h2{color:#fff;font-size:2.2rem;max-width:22ch;margin:0 auto 14px}
.capacita p{color:rgba(255,255,255,.9);max-width:62ch;margin:0 auto 26px;font-size:1.05rem}
.capacita .btn-ghost{background:#fff;border-color:#fff}
/* CTA en beneficios */
.ben-cta{text-align:center;margin-top:38px}
@media(max-width:820px){.infocards{grid-template-columns:1fr}}
@media(max-width:980px){.expo-hero h1{font-size:2.3rem}.ben2,.stand-grid{grid-template-columns:1fr}.expo-gal{grid-template-columns:repeat(2,1fr)}.expo-stats{margin-top:24px}}
</style>

<!-- HERO -->
<header class="expo-hero">
  <div class="bg"></div><div class="ov"></div>
  <div class="wrap">
    <?php if ( $em( 'expo_entrada' ) ) : ?><span class="badge">● <?php echo esc_html( $em( 'expo_entrada' ) ); ?></span><?php endif; ?>
    <h1><?php echo esc_html( $em( 'expo_titulo' ) ?: 'Expo ASOINCO' ); ?></h1>
    <?php if ( $em( 'expo_tagline' ) ) : ?><p class="tag"><?php echo esc_html( $em( 'expo_tagline' ) ); ?></p><?php endif; ?>
    <div class="meta">
      <?php if ( $em( 'expo_fechas_texto' ) ) : ?><span><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg> <?php echo esc_html( $em( 'expo_fechas_texto' ) ); ?></span><?php endif; ?>
      <?php if ( $em( 'expo_lugar' ) ) : ?><span><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg> <?php echo esc_html( $em( 'expo_lugar' ) ); ?></span><?php endif; ?>
      <?php if ( $em( 'expo_horario' ) ) : ?><span><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg> <?php echo esc_html( $em( 'expo_horario' ) ); ?></span><?php endif; ?>
    </div>
    <?php if ( $em( 'expo_programa_url' ) ) : ?>
    <div><a class="btn btn-crono" href="<?php echo esc_url( $em( 'expo_programa_url' ) ); ?>" target="_blank" rel="noopener">📅 Conoce el cronograma de la Expo →</a></div>
    <?php endif; ?>
    <div class="ctas">
      <a class="btn btn-dorado btn-lg" href="#stand">Adquiere tu stand →</a>
      <?php if ( $em( 'expo_registro_url' ) ) : ?><a class="btn btn-visita btn-lg" href="<?php echo esc_url( $em( 'expo_registro_url' ) ); ?>"<?php echo $ext( $em( 'expo_registro_url' ) ); ?>>Registra tu visita</a><?php endif; ?>
      <?php if ( $wa_link ) : ?><a class="btn wa-btn btn-lg" href="<?php echo esc_url( $wa_link ); ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" width="19" height="19" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2m0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24s8.24 3.7 8.24 8.24-3.7 8.24-8.24 8.24m4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.16.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.13-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.42-.14-.01-.31-.01-.48-.01s-.43.06-.66.31c-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.11-.22-.17-.47-.29"/></svg> Asesoría por WhatsApp</a><?php endif; ?>
    </div>
  </div>
</header>

<!-- INFO CARDS (¿Dónde / Cuándo / Horario?) -->
<?php if ( $show( 'infocards' ) && ( $em( 'expo_lugar' ) || $em( 'expo_fechas_texto' ) || $em( 'expo_horario' ) ) ) : ?>
<section class="sec" style="padding-top:50px;padding-bottom:10px">
  <div class="wrap"><div class="infocards">
    <?php if ( $em( 'expo_lugar' ) ) : ?><div class="infocard"><div class="ic"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg></div><h3>¿Dónde?</h3><p><?php echo esc_html( $em( 'expo_lugar' ) ); ?></p></div><?php endif; ?>
    <?php if ( $em( 'expo_fechas_texto' ) ) : ?><div class="infocard"><div class="ic"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div><h3>¿Cuándo?</h3><p><?php echo esc_html( $em( 'expo_fechas_texto' ) ); ?></p></div><?php endif; ?>
    <?php if ( $em( 'expo_horario' ) ) : ?><div class="infocard"><div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div><h3>Horario</h3><p><?php echo esc_html( $em( 'expo_horario' ) ); ?></p></div><?php endif; ?>
  </div></div>
</section>
<?php endif; ?>

<!-- COUNTDOWN -->
<?php if ( $show( 'countdown' ) && $em( 'expo_fecha_evento' ) ) : ?>
<section class="countdown">
  <div class="wrap" id="expoCountdown" data-fecha="<?php echo esc_attr( $em( 'expo_fecha_evento' ) ); ?>">
    <span class="cd-lead">Faltan para la Expo:</span>
    <div class="cd-box"><div class="n" data-d>–</div><div class="l">días</div></div>
    <div class="cd-box"><div class="n" data-h>–</div><div class="l">horas</div></div>
    <div class="cd-box"><div class="n" data-m>–</div><div class="l">min</div></div>
    <div class="cd-box"><div class="n" data-s>–</div><div class="l">seg</div></div>
  </div>
</section>
<?php endif; ?>

<!-- STATS -->
<?php if ( $show( 'stats' ) ) : ?>
<section class="sec" style="padding-top:54px">
  <div class="wrap">
    <div class="expo-stats">
      <?php for ( $i = 1; $i <= 5; $i++ ) :
        $n = $em( "expo_stat{$i}_num" ); $l = $em( "expo_stat{$i}_lbl" );
        if ( ! $n && ! $l ) { continue; } ?>
        <div class="expo-stat"><div class="n"><?php echo esc_html( $n ); ?></div><div class="l"><?php echo esc_html( $l ); ?></div></div>
      <?php endfor; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- QUÉ ES -->
<?php if ( $show( 'intro' ) && $em( 'expo_intro' ) ) : ?>
<section class="sec" style="padding-top:0">
  <div class="wrap center">
    <span class="eyebrow" style="color:var(--dorado)">Qué es la Expo ASOINCO</span>
    <h2 style="max-width:22ch;margin:0 auto 16px">El punto de encuentro de la industria de Coquimbo</h2>
    <p class="lead" style="margin:0 auto"><?php echo esc_html( $em( 'expo_intro' ) ); ?></p>
  </div>
</section>
<?php endif; ?>

<!-- VIDEO -->
<?php
$mp4 = $em( 'expo_video_mp4' );
$yt  = $em( 'expo_youtube' );
$vid = '';
if ( ! $mp4 && $yt && preg_match( '~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|v/|shorts/))([\w-]{11})~', $yt, $mm ) ) {
	$vid = $mm[1];
}
if ( $show( 'video' ) && ( $mp4 || $vid ) ) : ?>
<section class="sec" style="padding-top:0">
  <div class="wrap center" style="margin-bottom:26px"><span class="eyebrow" style="color:var(--dorado)">Vive la experiencia</span><h2>La Expo en video</h2></div>
  <div class="wrap" style="max-width:900px">
    <div style="position:relative;padding-bottom:56.25%;border-radius:16px;overflow:hidden;box-shadow:0 18px 40px rgba(15,27,46,.14)">
      <?php if ( $mp4 ) : ?>
        <video src="<?php echo esc_url( $mp4 ); ?>" controls playsinline style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;background:#000"></video>
      <?php else : ?>
        <iframe src="https://www.youtube.com/embed/<?php echo esc_attr( $vid ); ?>" style="position:absolute;inset:0;width:100%;height:100%;border:0" allowfullscreen loading="lazy"></iframe>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CAPACITACIONES -->
<?php if ( $show( 'capacitaciones' ) && $em( 'expo_capacitacion_url' ) ) : ?>
<section class="sec">
  <div class="wrap">
    <div class="capacita">
      <span class="eyebrow">Formación certificada</span>
      <h2>Inscríbete en nuestras capacitaciones certificadas</h2>
      <?php if ( $em( 'expo_capacitacion_txt' ) ) : ?><p><?php echo esc_html( $em( 'expo_capacitacion_txt' ) ); ?></p><?php endif; ?>
      <a class="btn btn-ghost" href="<?php echo esc_url( $em( 'expo_capacitacion_url' ) ); ?>"<?php echo $ext( $em( 'expo_capacitacion_url' ) ); ?>>Inscríbete aquí →</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- BENEFICIOS -->
<?php if ( $show( 'beneficios' ) ) : ?>
<section class="sec gris">
  <div class="wrap ben-head"><span class="eyebrow">Por qué participar</span><h2>Beneficios de ser parte de la Expo</h2></div>
  <div class="wrap ben2">
    <div class="col exp">
      <h3 class="tt">💼 Para el expositor</h3>
      <?php for ( $i = 1; $i <= 3; $i++ ) : $t = $em( "expo_benexp{$i}_t" ); if ( ! $t ) continue; ?>
        <div class="b"><div class="ico ico-exp">✔️</div><div><h4><?php echo esc_html( $t ); ?></h4><p><?php echo esc_html( $em( "expo_benexp{$i}_d" ) ); ?></p></div></div>
      <?php endfor; ?>
    </div>
    <div class="col vis">
      <h3 class="tt">🎟️ Para el visitante</h3>
      <?php for ( $i = 1; $i <= 3; $i++ ) : $t = $em( "expo_benvis{$i}_t" ); if ( ! $t ) continue; ?>
        <div class="b"><div class="ico ico-vis">✔️</div><div><h4><?php echo esc_html( $t ); ?></h4><p><?php echo esc_html( $em( "expo_benvis{$i}_d" ) ); ?></p></div></div>
      <?php endfor; ?>
    </div>
  </div>
  <div class="wrap ben-cta"><a class="btn btn-verde btn-lg" href="#stand">Quiero participar en la Expo →</a></div>
</section>
<?php endif; ?>

<!-- BROCHURE -->
<?php if ( $show( 'brochure' ) && $em( 'expo_brochure' ) ) : ?>
<section class="sec" style="padding-top:0">
  <div class="wrap">
    <div class="dir-banner">
      <div class="t">
        <span class="eyebrow">Toda la información</span>
        <h2>Descarga el brochure de la Expo</h2>
        <p>Revisa en detalle la participación, los valores y los beneficios de ser parte de la Expo ASOINCO.</p>
      </div>
      <a class="btn btn-dorado" href="<?php echo esc_url( $em( 'expo_brochure' ) ); ?>" target="_blank" rel="noopener">📄 Descargar brochure →</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- INFO DE STANDS + PRECIOS -->
<?php
$incluye = array_filter( array_map( 'trim', explode( "\n", (string) $em( 'expo_stand_incluye' ) ) ) );
$hay_precios = $em( 'expo_precio_int_socio' ) || $em( 'expo_precio_ext_socio' );
if ( $show( 'precios' ) && ( $incluye || $hay_precios ) ) : ?>
<section class="sec gris">
  <div class="wrap center" style="margin-bottom:34px"><span class="eyebrow">Info de stands</span><h2>Qué incluye tu stand y sus valores</h2></div>
  <div class="wrap stand-info">
    <?php if ( $incluye ) : ?>
    <div class="incluye">
      <h3>Los stands incluyen</h3>
      <ul><?php foreach ( $incluye as $it ) : ?><li><?php echo esc_html( $it ); ?></li><?php endforeach; ?></ul>
    </div>
    <?php endif; ?>
    <?php if ( $hay_precios ) : ?>
    <div class="precios">
      <div class="pcard">
        <div class="pcard-h">Stand Interior</div>
        <div class="prow socio"><span>Exclusivo Socios</span><b><?php echo esc_html( $em( 'expo_precio_int_socio' ) ); ?></b></div>
        <div class="prow"><span>Valor No Socios</span><b><?php echo esc_html( $em( 'expo_precio_int_nosocio' ) ); ?></b></div>
      </div>
      <div class="pcard">
        <div class="pcard-h">Stand Exterior</div>
        <div class="prow socio"><span>Exclusivo Socios</span><b><?php echo esc_html( $em( 'expo_precio_ext_socio' ) ); ?></b></div>
        <div class="prow"><span>Valor No Socios</span><b><?php echo esc_html( $em( 'expo_precio_ext_nosocio' ) ); ?></b></div>
      </div>
    </div>
    <?php endif; ?>
  </div>
  <?php if ( $em( 'expo_precio_nota' ) ) : ?><div class="wrap"><p class="precio-nota"><?php echo esc_html( $em( 'expo_precio_nota' ) ); ?></p></div><?php endif; ?>
</section>
<?php endif; ?>

<!-- FORMULARIO STAND -->
<?php if ( $show( 'stand' ) ) : ?>
<section class="sec" id="stand">
  <div class="wrap stand-grid">
    <div class="stand-cta">
      <span class="eyebrow">Sé parte de la Expo</span>
      <h2>¿Te interesa un stand o ser expositor?</h2>
      <p>Son dos formas de participar y ambas conectan a tu empresa con la industria, la minería y el sector público de la región. Cuéntanos qué te interesa y te contactamos.</p>
      <?php if ( $wa_link ) : ?><a class="btn wa-btn" href="<?php echo esc_url( $wa_link ); ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2m0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24s8.24 3.7 8.24 8.24-3.7 8.24-8.24 8.24m4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.16.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.13-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.42-.14-.01-.31-.01-.48-.01s-.43.06-.66.31c-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.11-.22-.17-.47-.29"/></svg> Habla con un asesor por WhatsApp</a><?php endif; ?>
      <?php if ( $em( 'expo_email' ) ) : ?><div class="stand-alt">También puedes escribirnos a <strong><?php echo esc_html( $em( 'expo_email' ) ); ?></strong></div><?php endif; ?>
    </div>
    <div>
      <?php if ( 'ok' === $estado_stand ) : ?>
        <div class="alert ok"><b>¡Gracias! Recibimos tu solicitud. ✅</b><br>Nuestro equipo de la Expo te contactará a la brevedad.</div>
      <?php elseif ( 'faltan' === $estado_stand ) : ?>
        <div class="alert err">Faltan datos: empresa y email son obligatorios.</div>
      <?php endif; ?>
      <form class="form" method="post" action="<?php echo esc_url( get_permalink( $pid ) ); ?>#stand">
        <?php wp_nonce_field( 'asoinco_stand_form', 'asoinco_stand_nonce' ); ?>
        <input type="hidden" name="asoinco_stand_submit" value="1">
        <div class="field"><label>¿Qué te interesa? *</label>
          <select name="interes" required>
            <option value="">Selecciona una opción…</option>
            <option>Adquirir un stand</option>
            <option>Ser expositor</option>
            <option>Registrar mi visita</option>
            <option>Inscribirme en capacitaciones</option>
            <option>Auspiciar la Expo</option>
            <option>Más información sobre la Expo</option>
          </select>
        </div>
        <div class="row">
          <div class="field"><label>Empresa *</label><input type="text" name="empresa" required></div>
          <div class="field"><label>Nombre de contacto</label><input type="text" name="nombre"></div>
        </div>
        <div class="row">
          <div class="field"><label>Email *</label><input type="email" name="email" required></div>
          <div class="field"><label>Teléfono</label><input type="tel" name="fono"></div>
        </div>
        <div class="field full"><label>Mensaje</label><textarea name="mensaje" placeholder="Cuéntanos qué necesitas (tamaño de stand, rubro, etc.)"></textarea></div>
        <button class="btn btn-verde" type="submit">Enviar solicitud →</button>
      </form>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- PLANO -->
<?php if ( $show( 'plano' ) && $em( 'expo_plano' ) ) : ?>
<section class="sec">
  <div class="wrap center" style="margin-bottom:30px"><span class="eyebrow">Plano del evento</span><h2>Distribución de stands</h2></div>
  <div class="wrap expo-plano"><img src="<?php echo esc_url( $em( 'expo_plano' ) ); ?>" alt="Plano de la Expo ASOINCO"></div>
</section>
<?php endif; ?>

<!-- AUSPICIADORES (carrusel) -->
<?php
$ausp = new WP_Query( array( 'post_type' => 'auspiciador', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'publish' ) );
if ( $show( 'auspiciadores' ) && $ausp->have_posts() ) :
	$ausp_items = '';
	while ( $ausp->have_posts() ) :
		$ausp->the_post();
		$aweb  = get_post_meta( get_the_ID(), 'ausp_web', true );
		$inner = has_post_thumbnail()
			? get_the_post_thumbnail( get_the_ID(), 'medium', array( 'alt' => esc_attr( get_the_title() ) ) )
			: '<span class="txt">' . esc_html( get_the_title() ) . '</span>';
		$ausp_items .= $aweb
			? '<a class="ausp" href="' . esc_url( $aweb ) . '" target="_blank" rel="noopener">' . $inner . '</a>'
			: '<div class="ausp">' . $inner . '</div>';
	endwhile;
	$ausp_count = $ausp->post_count;
	wp_reset_postdata();
	?>
<section class="ausp-section">
  <div class="head center" style="margin-bottom:34px;padding:0 24px"><span class="eyebrow">Auspician y colaboran</span><h2>Quienes hacen posible la Expo</h2></div>
  <?php if ( $ausp_count > 6 ) : ?>
  <div class="ausp-marquee"><?php echo $ausp_items . $ausp_items; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
  <?php else : ?>
  <div class="wrap"><div class="ausp-static"><?php echo $ausp_items; // phpcs:ignore WordPress.Security.EscapeOutput ?></div></div>
  <?php endif; ?>
</section>
<?php endif; ?>

<!-- CÓMO LLEGAR -->
<?php if ( $show( 'mapa' ) && ( $em( 'expo_mapa_embed' ) || $em( 'expo_direccion' ) ) ) : ?>
<section class="sec">
  <div class="wrap center" style="margin-bottom:28px"><span class="eyebrow">Cómo llegar</span><h2>Te esperamos en la feria más grande de la región</h2>
    <?php if ( $em( 'expo_direccion' ) ) : ?><p class="lead" style="margin:8px auto 0"><?php echo esc_html( $em( 'expo_direccion' ) ); ?></p><?php endif; ?>
  </div>
  <?php if ( $em( 'expo_mapa_embed' ) ) : ?><div class="wrap expo-map"><?php echo $em( 'expo_mapa_embed' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div><?php endif; ?>
</section>
<?php endif; ?>

<!-- GALERÍA (al final) -->
<?php
$gal_ids = array_filter( array_map( 'absint', explode( ',', (string) $em( 'expo_galeria' ) ) ) );
if ( $show( 'galeria' ) && $gal_ids ) : ?>
<section class="sec gris">
  <div class="wrap center" style="margin-bottom:34px"><span class="eyebrow">Así se vive</span><h2>Galería Expo ASOINCO</h2></div>
  <div class="wrap">
    <div class="expo-gal" id="expoGal" data-batch="8">
    <?php $gi = 0; foreach ( $gal_ids as $gid ) : $full = wp_get_attachment_image_url( $gid, 'large' ); if ( ! $full ) continue; ?>
      <a class="gal-item<?php echo $gi >= 8 ? ' gal-hidden' : ''; ?>" href="<?php echo esc_url( $full ); ?>" target="_blank" rel="noopener"><?php echo wp_get_attachment_image( $gid, 'medium_large' ); ?></a>
    <?php $gi++; endforeach; ?>
    </div>
    <?php if ( count( $gal_ids ) > 8 ) : ?>
    <div style="text-align:center;margin-top:32px"><button type="button" class="btn btn-ghost" id="expoGalMore">Ver más fotos →</button></div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<script>
(function () {
  var el = document.getElementById('expoCountdown');
  if (!el) return;
  var target = new Date(el.getAttribute('data-fecha') + 'T09:00:00').getTime();
  var d = el.querySelector('[data-d]'), h = el.querySelector('[data-h]'), m = el.querySelector('[data-m]'), s = el.querySelector('[data-s]');
  function tick() {
    var diff = target - Date.now();
    if (diff < 0) { d.textContent = h.textContent = m.textContent = s.textContent = '0'; return; }
    d.textContent = Math.floor(diff / 86400000);
    h.textContent = Math.floor(diff % 86400000 / 3600000);
    m.textContent = Math.floor(diff % 3600000 / 60000);
    s.textContent = Math.floor(diff % 60000 / 1000);
  }
  tick(); setInterval(tick, 1000);
})();

/* GALERÍA — Ver más fotos por tandas */
(function () {
  var grid = document.getElementById('expoGal'), btn = document.getElementById('expoGalMore');
  if (!grid || !btn) return;
  var batch = parseInt(grid.getAttribute('data-batch'), 10) || 8;
  btn.addEventListener('click', function () {
    var hidden = grid.querySelectorAll('.gal-hidden');
    for (var i = 0; i < batch && i < hidden.length; i++) { hidden[i].classList.remove('gal-hidden'); }
    if (grid.querySelectorAll('.gal-hidden').length === 0) { btn.style.display = 'none'; }
  });
})();
</script>

<?php
get_footer();
