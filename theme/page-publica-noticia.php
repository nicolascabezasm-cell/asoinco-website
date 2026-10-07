<?php
/**
 * Plantilla: Publica tu noticia (portal de socios, sin cuentas).
 *
 * @package asoinco
 */
get_header();

$estado = isset( $_GET['noticia'] ) ? sanitize_key( $_GET['noticia'] ) : '';

$socios = get_posts( array(
	'post_type'   => 'socio',
	'numberposts' => -1,
	'orderby'     => 'title',
	'order'       => 'ASC',
	'post_status' => 'publish',
) );
?>
<style>
.pn-grid{display:grid;grid-template-columns:.8fr 1.2fr;gap:48px;align-items:start}
.pn-info .step{display:flex;gap:13px;margin-bottom:20px}
.pn-info .step .num{width:34px;height:34px;border-radius:50%;background:var(--navy);color:#fff;font-weight:800;display:flex;align-items:center;justify-content:center;flex:none;font-size:.9rem}
.pn-info .step h4{font-size:1rem;color:var(--navy);margin-bottom:3px}
.pn-info .step p{font-size:.88rem;color:var(--texto)}
.pn-note{background:rgba(91,173,94,.1);border:1px solid rgba(91,173,94,.35);border-radius:12px;padding:16px 18px;font-size:.88rem;color:#2F6B33;margin-top:8px}
.alert{border-radius:12px;padding:16px 20px;margin-bottom:24px;font-size:.95rem}
.alert.ok{background:rgba(91,173,94,.12);border:1px solid rgba(91,173,94,.4);color:#2F6B33}
.alert.err{background:rgba(220,60,60,.1);border:1px solid rgba(220,60,60,.4);color:#9A2B2B}
@media(max-width:980px){.pn-grid{grid-template-columns:1fr}}
</style>

<header class="page-hero">
  <div class="glow" style="width:280px;height:280px;background:var(--verde);top:-90px;right:-40px"></div>
  <div class="wrap">
    <div class="crumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a> / <a href="<?php echo esc_url( home_url( '/noticias/' ) ); ?>">Noticias</a> / Publica tu noticia</div>
    <span class="eyebrow">Portal de socios</span>
    <h1>Publica tu noticia</h1>
    <p>¿Tu empresa socia tiene un hito, lanzamiento o actividad que compartir? Envíanoslo y lo difundimos en las noticias de ASOINCO.</p>
  </div>
</header>

<section class="sec">
  <div class="wrap pn-grid">
    <div class="pn-info">
      <span class="eyebrow">Cómo funciona</span>
      <h2 style="font-size:1.5rem;margin-bottom:22px">Simple y sin claves</h2>
      <div class="step"><div class="num">1</div><div><h4>Completa el formulario</h4><p>Elige tu empresa, escribe la noticia y adjunta una foto si quieres.</p></div></div>
      <div class="step"><div class="num">2</div><div><h4>Revisamos tu envío</h4><p>El equipo de prensa de ASOINCO revisa que todo esté en orden.</p></div></div>
      <div class="step"><div class="num">3</div><div><h4>Se publica</h4><p>Tu noticia aparece en la sección Noticias, identificada con el nombre de tu empresa.</p></div></div>
      <div class="pn-note"><b>Sin cuentas ni contraseñas.</b> No necesitas registrarte: solo identifícate con tu empresa y correo. Toda publicación pasa por revisión antes de salir.</div>
    </div>

    <div>
      <?php if ( 'ok' === $estado ) : ?>
        <div class="alert ok"><b>¡Gracias! Recibimos tu noticia. ✅</b><br>Quedó enviada y pasará por revisión del equipo de prensa antes de publicarse.</div>
      <?php elseif ( 'faltan' === $estado ) : ?>
        <div class="alert err">Faltan datos obligatorios. Revisa empresa, título y contenido.</div>
      <?php elseif ( 'error' === $estado ) : ?>
        <div class="alert err">Hubo un problema al enviar. Intenta nuevamente.</div>
      <?php endif; ?>

      <form class="form" method="post" enctype="multipart/form-data" action="<?php echo esc_url( home_url( '/publica-noticia/' ) ); ?>">
        <?php wp_nonce_field( 'asoinco_noticia_form', 'asoinco_noticia_nonce' ); ?>
        <input type="hidden" name="asoinco_noticia_submit" value="1">

        <div class="field"><label>Tu empresa socia *</label>
          <input type="text" name="empresa" list="socios-datalist" required autocomplete="off" placeholder="Escribe para buscar tu empresa…">
          <datalist id="socios-datalist">
            <?php foreach ( $socios as $s ) : ?>
              <option value="<?php echo esc_attr( $s->post_title ); ?>"></option>
            <?php endforeach; ?>
            <option value="Otra (aún no soy socio)"></option>
          </datalist>
        </div>
        <div class="row">
          <div class="field"><label>Email de contacto *</label><input type="email" name="email" required placeholder="tu@empresa.cl"></div>
          <div class="field"><label>Tipo de publicación</label>
            <select name="tipo">
              <option>Noticia</option>
              <option>Artículo</option>
              <option>Comunicado</option>
              <option>Evento</option>
            </select>
          </div>
        </div>
        <div class="field"><label>Título de la noticia *</label><input type="text" name="titulo" required placeholder="Ej: Nuestra empresa inaugura nueva planta"></div>
        <div class="field full"><label>Contenido *</label><textarea name="contenido" required placeholder="Cuéntanos la noticia con el mayor detalle posible…"></textarea></div>
        <div class="field"><label>Imagen (opcional)</label><input type="file" name="imagen" accept="image/*"></div>
        <div class="field check"><input type="checkbox" id="ok1" required><label for="ok1" style="font-weight:400">Autorizo a ASOINCO a revisar y publicar esta información.</label></div>
        <button class="btn btn-verde" type="submit">Enviar noticia →</button>
      </form>
    </div>
  </div>
</section>

<?php
get_footer();
