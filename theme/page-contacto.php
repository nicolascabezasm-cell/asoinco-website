<?php
/**
 * Plantilla: Contacto.
 *
 * @package asoinco
 */
get_header();
?>
<style>
.contact-grid{display:grid;grid-template-columns:.85fr 1.15fr;gap:48px;align-items:start}
.info .item{display:flex;gap:14px;margin-bottom:24px}
.info .item .ico{width:46px;height:46px;border-radius:12px;background:rgba(27,58,107,.08);color:var(--navy);display:flex;align-items:center;justify-content:center;flex:none}
.info .item .ico svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:2}
.info .item h4{font-size:1rem;color:var(--navy);margin-bottom:3px}
.info .item p{font-size:.92rem;color:var(--texto)}
.map{margin-top:8px;border-radius:14px;overflow:hidden;border:1px solid var(--borde)}
.map iframe{width:100%;height:220px;border:0;display:block}
@media(max-width:980px){.contact-grid{grid-template-columns:1fr}}
</style>

<header class="page-hero">
  <div class="glow" style="width:260px;height:260px;background:var(--dorado);top:-90px;right:-40px"></div>
  <div class="wrap">
    <div class="crumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a> / Contacto</div>
    <span class="eyebrow">Hablemos</span>
    <h1>Contáctanos</h1>
    <p>¿Tienes una consulta, quieres asociarte o participar en la Expo? Escríbenos y te respondemos a la brevedad.</p>
  </div>
</header>

<section class="sec">
  <div class="wrap contact-grid">
    <div class="info">
      <div class="item"><div class="ico"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg></div><div><h4>Dirección</h4><p>Coquimbo, Región de Coquimbo, Chile</p></div></div>
      <div class="item"><div class="ico"><svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg></div><div><h4>Email</h4><p>asistente@asoinco.cl</p></div></div>
      <div class="item"><div class="ico"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/></svg></div><div><h4>Teléfono</h4><p>+56 9 7979 8887</p></div></div>
      <div class="item"><div class="ico"><svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/></svg></div><div><h4>Redes</h4><p>@asoinco.ag</p></div></div>
      <div class="map"><iframe loading="lazy" src="https://www.google.com/maps?q=Coquimbo,Chile&output=embed" title="Mapa Coquimbo"></iframe></div>
    </div>
    <div>
      <?php $ok = isset( $_GET['enviado'] ) ? sanitize_key( $_GET['enviado'] ) : ''; ?>
      <?php if ( 'contacto' === $ok ) : ?>
        <div style="background:rgba(91,173,94,.12);border:1px solid rgba(91,173,94,.4);color:#2F6B33;border-radius:12px;padding:16px 20px;margin-bottom:20px"><b>¡Gracias por escribirnos! ✅</b><br>Recibimos tu mensaje y el equipo de ASOINCO te responderá a la brevedad.</div>
      <?php elseif ( 'faltan' === $ok ) : ?>
        <div style="background:rgba(220,60,60,.1);border:1px solid rgba(220,60,60,.4);color:#9A2B2B;border-radius:12px;padding:16px 20px;margin-bottom:20px">Faltan datos: nombre, email y mensaje son obligatorios.</div>
      <?php endif; ?>
      <form class="form" method="post" action="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">
        <?php wp_nonce_field( 'asoinco_contacto', 'asoinco_contacto_nonce' ); ?>
        <input type="hidden" name="asoinco_contacto_submit" value="1">
        <div class="row">
          <div class="field"><label>Nombre *</label><input type="text" name="nombre" required placeholder="Tu nombre"></div>
          <div class="field"><label>Empresa</label><input type="text" name="empresa" placeholder="Nombre de tu empresa"></div>
        </div>
        <div class="row">
          <div class="field"><label>Email *</label><input type="email" name="email" required placeholder="tu@empresa.cl"></div>
          <div class="field"><label>Teléfono</label><input type="tel" name="fono" placeholder="+56 9 ..."></div>
        </div>
        <div class="field"><label>Asunto</label>
          <select name="asunto"><option>Consulta general</option><option>Quiero asociarme</option><option>Participar en la Expo</option><option>Directorio de socios</option><option>Prensa / alianzas</option></select></div>
        <div class="field full"><label>Mensaje *</label><textarea name="mensaje" required placeholder="Cuéntanos en qué podemos ayudarte"></textarea></div>
        <div class="field check"><input type="checkbox" id="ok1" required><label for="ok1" style="font-weight:400">Autorizo a ASOINCO a contactarme respecto a esta consulta.</label></div>
        <button class="btn btn-verde" type="submit">Enviar mensaje →</button>
      </form>
    </div>
  </div>
</section>

<?php
get_footer();
