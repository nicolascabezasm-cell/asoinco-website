<?php
/**
 * Plantilla: Quiénes somos.
 *
 * @package asoinco
 */
get_header();
?>
<style>
.qs-intro{display:grid;grid-template-columns:1.1fr .9fr;gap:48px;align-items:center}
.qs-intro img{border-radius:16px;width:100%;height:100%;object-fit:cover;max-height:420px}
.qs-mv{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:36px}
.qs-mv .card h3{display:flex;align-items:center;gap:10px}
.qs-plan{background:linear-gradient(135deg,var(--navy),var(--oscuro));color:#fff;border-radius:18px;padding:48px;position:relative;overflow:hidden}
.qs-plan .eyebrow{color:var(--dorado)}.qs-plan h2{color:#fff;margin-bottom:10px}
.qs-plan p{color:rgba(255,255,255,.85);max-width:70ch}
@media(max-width:980px){.qs-intro{grid-template-columns:1fr}.qs-mv{grid-template-columns:1fr}.qs-plan{padding:32px}}
</style>

<header class="page-hero img" style="background-image:url('https://www.asoinco.cl/wp-content/uploads/2025/05/Terminal-puerto-coquimbo.jpg')">
  <div class="ov"></div>
  <div class="wrap">
    <div class="crumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a> / Quiénes somos</div>
    <span class="eyebrow">Gremio Industrial · Desde 2006</span>
    <h1>Representamos a la industria de la Región de Coquimbo</h1>
    <p>Somos la asociación gremial que une, representa y proyecta a las empresas industriales y proveedoras de la región.</p>
  </div>
</header>

<section class="sec">
  <div class="wrap qs-intro">
    <div>
      <span class="eyebrow" id="historia">Nuestra historia</span>
      <h2>Más de 20 años impulsando la industria regional</h2>
      <p class="lead" style="margin:16px 0">ASOINCO nació en 2006 para dar una voz común a las empresas industriales y proveedoras de Coquimbo. Hoy reúne a <b>133 empresas socias</b> y actúa como puente entre el sector productivo, el Estado, la gran minería y la academia.</p>
      <p class="lead">Representamos al Barrio Industrial de Coquimbo —más de 550 empresas y 20.000 empleos— y trabajamos por un desarrollo industrial regional moderno, sostenible y conectado.</p>
    </div>
    <img src="<?php echo esc_url( home_url( '/wp-content/uploads/logos-import/asoinco-equipo.jpg' ) ); ?>" alt="Equipo ASOINCO">
  </div>

  <div class="wrap qs-mv">
    <div class="card" id="mision"><h3><span class="ico ico-a" style="width:40px;height:40px;margin:0"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg></span> NUESTRA MISIÓN</h3><p>Representar, articular y fortalecer a las empresas socias, promoviendo una <b>gestión gremial moderna,</b> el desarrollo de programas estratégicos, la articulación empresarial y territorial, y la entrega de servicios de <b>alto valor,</b> contribuyendo activamente a un desarrollo sostenible de la región de Coquimbo.</p></div>
    <div class="card" id="vision"><h3><span class="ico ico-v" style="width:40px;height:40px;margin:0"><svg viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></span> NUESTRA VISIÓN</h3><p>Consolidar a ASOINCO como gremio multisectorial líder de la región de Coquimbo, reconocido por su capacidad de <b>generar impacto positivo</b> en el territorio y por su aporte <b>efectivo</b> al desarrollo productivo, social y ambiental de la región.</p></div>
  </div>
</section>

<section class="sec gris">
  <div class="wrap">
    <div class="center" style="margin-bottom:28px">
      <span class="eyebrow">Cifras que nos respaldan</span>
      <h2>NUESTRO IMPACTO TERRITORIAL</h2>
    </div>
    <div class="statbar statbar-5">
      <div><div class="n">133<em>+</em></div><div class="l">empresas socias</div></div>
      <div><div class="n">836<em>+</em></div><div class="l">empresas en alcance indirecto</div></div>
      <div><div class="n">36.000</div><div class="l">personas impactadas</div></div>
      <div><div class="n">140K</div><div class="l">visualizaciones</div></div>
      <div><div class="n">2.299</div><div class="l">seguidores activos</div></div>
    </div>
  </div>
</section>

<section class="sec">
  <div class="wrap center" style="margin-bottom:40px">
    <span class="eyebrow">Directorio ejecutivo</span>
    <h2>Quiénes lideran el gremio</h2>
  </div>
  <div class="wrap team">
    <div class="member"><div class="av"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/foto-buzeta.jpg' ); ?>" alt="Fernando Buzeta Salazar"></div><div class="role">Presidente</div><h4>Fernando Buzeta Salazar</h4></div>
    <div class="member"><div class="av"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/foto-javier.jpg' ); ?>" alt="Javier Julio"></div><div class="role">Gerente General</div><h4>Javier Julio</h4></div>
    <div class="member"><div class="av" style="background:var(--dorado);color:var(--navy)">CD</div><h4>Consejo Directivo</h4><div class="role">Empresas socias que guían la estrategia gremial</div></div>
  </div>
</section>

<section class="sec gris">
  <div class="wrap center" style="margin-bottom:40px">
    <span class="eyebrow">Cómo trabajamos</span>
    <h2>7 comisiones de trabajo activas</h2>
    <p class="lead">El gremio en acción, organizado por los temas que mueven a la industria regional.</p>
  </div>
  <div class="wrap comgrid">
    <div class="comm"><div class="ico"><svg viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></div><span>Barrio Industrial y Des. Productivo</span></div>
    <div class="comm"><div class="ico"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><span>Capital Humano y Formación</span></div>
    <div class="comm"><div class="ico"><svg viewBox="0 0 24 24"><path d="M12 2.69 17 8a7 7 0 1 1-10 0z"/></svg></div><span>Sostenibilidad y Gestión Hídrica</span></div>
    <div class="comm"><div class="ico"><svg viewBox="0 0 24 24"><path d="M12 3v18M3 12h18"/></svg></div><span>Innovación y Vinculación Acad.</span></div>
    <div class="comm"><div class="ico"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div><span>Comercial y Posicionamiento</span></div>
    <div class="comm"><div class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20Z"/></svg></div><span>Internacionalización y Mercados Globales</span></div>
    <div class="comm"><div class="ico"><svg viewBox="0 0 24 24"><path d="M12 3v18M5 7l7-2 7 2M5 7 2 14h6zM19 7l-3 7h6z"/></svg></div><span>Mujeres en la Industria y la Empresa</span></div>
    <div class="comm"><div class="ico" style="background:rgba(245,166,35,.16);color:#B9760C"><svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div><span><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>" style="color:var(--navy)">Súmate a una comisión</a></span></div>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <div class="qs-plan">
      <div class="glow" style="width:240px;height:240px;background:var(--verde);top:-80px;right:-60px"></div>
      <span class="eyebrow">Mirando al futuro</span>
      <h2>Plan de Modernización 2030</h2>
      <p>Una hoja de ruta para transformar a ASOINCO en una plataforma gremial moderna: digitalización del directorio, fortalecimiento de la Expo, nuevas alianzas con la minería y el Estado, y un gremio más conectado con sus socios. Las 7 comisiones son el motor de este plan.</p>
      <div style="margin-top:24px"><a class="btn btn-dorado" href="<?php echo esc_url( home_url( '/hazte-socio/' ) ); ?>">Sé parte del cambio →</a></div>
    </div>
  </div>
</section>

<section class="sec ctaband">
  <div class="wrap">
    <h2>¿Tu empresa aún no es parte de <em>ASOINCO</em>?</h2>
    <div class="right"><p>Súmate a las 133 empresas que ya representan a la industria de Coquimbo.</p><a class="btn btn-verde" href="<?php echo esc_url( home_url( '/hazte-socio/' ) ); ?>">Hazte socio →</a></div>
  </div>
</section>

<?php
get_footer();
