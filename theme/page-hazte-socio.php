<?php
/**
 * Plantilla: Hazte socio.
 *
 * @package asoinco
 */
get_header();
?>
<style>
.join-grid{display:grid;grid-template-columns:.8fr 1.2fr;gap:48px;align-items:start}
.benefits{position:sticky;top:90px}
.benefits .b{display:flex;gap:13px;margin-bottom:20px}
.benefits .b .ico{width:42px;height:42px;border-radius:11px;background:rgba(91,173,94,.14);color:var(--verde);display:flex;align-items:center;justify-content:center;flex:none}
.benefits .b .ico svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:2}
.benefits .b h4{font-size:1rem;color:var(--navy);margin-bottom:3px}
.benefits .b p{font-size:.88rem;color:var(--texto)}
.tiers{display:flex;gap:8px;margin-top:8px}
.tiers label{flex:1;border:1.4px solid var(--borde);border-radius:11px;padding:14px 12px;text-align:center;cursor:pointer;transition:.2s}
.tiers label:hover{border-color:var(--navy)}
.tiers input{display:none}
.tiers input:checked + .tt{color:var(--verde)}
.tiers label:has(input:checked){border-color:var(--verde);background:rgba(91,173,94,.06);box-shadow:0 0 0 2px rgba(91,173,94,.18)}
.tiers .tt{display:block;font-weight:700;color:var(--navy);font-size:.92rem}
.tiers .ts{display:block;font-size:.74rem;color:var(--texto);margin-top:3px}
@media(max-width:980px){.join-grid{grid-template-columns:1fr}.benefits{position:static}}
</style>

<header class="page-hero">
  <div class="glow" style="width:280px;height:280px;background:var(--verde);top:-90px;right:-40px"></div>
  <div class="wrap">
    <div class="crumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a> / Hazte socio</div>
    <span class="eyebrow">Súmate al gremio</span>
    <h1>Haz que tu empresa sea parte de ASOINCO</h1>
    <p>Únete a las 133 empresas que ya representan a la industria de la Región de Coquimbo. Completa el formulario y te contactamos.</p>
  </div>
</header>

<section class="sec">
  <div class="wrap join-grid">
    <div class="benefits">
      <span class="eyebrow">Lo que obtienes</span>
      <h2 style="font-size:1.5rem;margin-bottom:22px">Beneficios de ser socio</h2>
      <div class="b"><div class="ico"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div><div><h4>Acceso a negocios</h4><p>Conexión con grandes compradores. La Alianza CMP destina ~US$441M/año a proveedores regionales.</p></div></div>
      <div class="b"><div class="ico"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg></div><div><h4>Representación gremial</h4><p>Una voz ante el Estado, la minería, CORFO y la academia.</p></div></div>
      <div class="b"><div class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div><div><h4>Presencia en el directorio</h4><p>Visibilidad ante quienes buscan proveedores en la región.</p></div></div>
      <div class="b"><div class="ico"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div><div><h4>Expo y comunidad</h4><p>Acceso preferente a la Expo ASOINCO y a las 7 comisiones de trabajo.</p></div></div>
    </div>

    <div>
      <?php $ok = isset( $_GET['enviado'] ) ? sanitize_key( $_GET['enviado'] ) : ''; ?>
      <?php if ( 'socio' === $ok ) : ?>
        <div style="background:rgba(91,173,94,.12);border:1px solid rgba(91,173,94,.4);color:#2F6B33;border-radius:12px;padding:16px 20px;margin-bottom:20px"><b>¡Gracias por tu interés en ASOINCO! ✅</b><br>Recibimos tu solicitud. Nuestro equipo te contactará para completar el proceso.</div>
      <?php elseif ( 'faltan' === $ok ) : ?>
        <div style="background:rgba(220,60,60,.1);border:1px solid rgba(220,60,60,.4);color:#9A2B2B;border-radius:12px;padding:16px 20px;margin-bottom:20px">Faltan datos obligatorios (empresa, rubro, comuna, contacto, email y teléfono).</div>
      <?php endif; ?>
      <form class="form" method="post" action="<?php echo esc_url( home_url( '/hazte-socio/' ) ); ?>">
        <?php wp_nonce_field( 'asoinco_socio_form', 'asoinco_socio_form_nonce' ); ?>
        <input type="hidden" name="asoinco_socio_submit" value="1">
        <h3 style="font-size:1.3rem;color:var(--navy);margin-bottom:6px">Solicitud de inscripción</h3>
        <p style="font-size:.9rem;color:var(--texto);margin-bottom:24px">Cuéntanos de tu empresa. Los campos con * son obligatorios.</p>
        <div class="row">
          <div class="field"><label>Nombre de la empresa *</label><input type="text" name="empresa" required placeholder="Razón social o nombre comercial"></div>
          <div class="field"><label>RUT de la empresa</label><input type="text" name="rut" placeholder="76.123.456-7"></div>
        </div>
        <div class="row">
          <div class="field"><label>Rubro *</label>
            <select name="rubro" required><option value="">Selecciona…</option><option>Metalmecánica</option><option>Logística y transporte</option><option>Energía</option><option>Construcción</option><option>Minería y servicios</option><option>Pesca industrial</option><option>Agricultura</option><option>Ingeniería</option><option>Otro</option></select></div>
          <div class="field"><label>Comuna *</label>
            <select name="comuna" required><option value="">Selecciona…</option><option>Coquimbo</option><option>La Serena</option><option>Ovalle</option><option>Illapel</option><option>Vicuña</option><option>Andacollo</option><option>Otra</option></select></div>
        </div>
        <div class="row">
          <div class="field"><label>Nombre de contacto *</label><input type="text" name="nombre" required placeholder="Tu nombre"></div>
          <div class="field"><label>Cargo</label><input type="text" name="cargo" placeholder="Gerente, dueño, etc."></div>
        </div>
        <div class="row">
          <div class="field"><label>Email *</label><input type="email" name="email" required placeholder="contacto@empresa.cl"></div>
          <div class="field"><label>Teléfono *</label><input type="tel" name="fono" required placeholder="+56 9 ..."></div>
        </div>
        <div class="field"><label>Sitio web</label><input type="url" name="web" placeholder="https://www.empresa.cl"></div>
        <div class="field"><label>Nivel de membresía de interés</label>
          <div class="tiers">
            <label><input type="radio" name="tier" value="Socio" checked><span class="tt">Socio</span><span class="ts">Directorio + Expo</span></label>
            <label><input type="radio" name="tier" value="Socio Activo"><span class="tt">Socio Activo</span><span class="ts">+ Oportunidades</span></label>
            <label><input type="radio" name="tier" value="Estratégico"><span class="tt">Estratégico</span><span class="ts">+ Vocería</span></label>
          </div>
          <div class="hint">Sin compromiso: el equipo te explicará cada nivel.</div>
        </div>
        <div class="field full"><label>Cuéntanos sobre tu empresa</label><textarea name="mensaje" placeholder="A qué se dedica, capacidades, certificaciones…"></textarea></div>
        <div class="field check"><input type="checkbox" id="ok1" required><label for="ok1" style="font-weight:400">Autorizo a ASOINCO a contactarme y tratar mis datos para gestionar la inscripción.</label></div>
        <button class="btn btn-verde" type="submit">Enviar solicitud →</button>
      </form>
    </div>
  </div>
</section>

<?php
get_footer();
