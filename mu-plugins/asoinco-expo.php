<?php
/**
 * ASOINCO — Expo (100% autoadministrable).
 * Panel de edición completo en la página Expo + CPT Auspiciador + solicitudes de stand.
 *
 * @package asoinco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Slug de la página Expo (genérico, sirve todos los años). */
const ASOINCO_EXPO_SLUG = 'expo';

/**
 * Migrar la página de slug "expo-2024" a "expo" (genérico) — una sola vez, temprano.
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_expo_slug' ) ) {
		return;
	}
	$page = get_page_by_path( 'expo-2024' );
	if ( ! $page ) {
		$page = get_page_by_path( 'expo' );
	}
	if ( $page ) {
		if ( 'expo' !== $page->post_name ) {
			wp_update_post( array( 'ID' => $page->ID, 'post_name' => 'expo', 'post_title' => 'Expo ASOINCO' ) );
		}
		update_post_meta( $page->ID, 'expo_banner_cta_link', home_url( '/expo/#stand' ) );
	}
	update_option( 'asoinco_expo_slug', 1 );
}, 1 );

/**
 * Campos editables de la Expo (clave => tipo de sanitización).
 */
function asoinco_expo_fields() {
	return array(
		'expo_estado'          => 'text',   // "activa" o "pasada".
		'expo_titulo'          => 'text',
		'expo_tagline'         => 'text',
		'expo_fecha_evento'    => 'text',   // YYYY-MM-DD para el countdown.
		'expo_fechas_texto'    => 'text',
		'expo_lugar'           => 'text',
		'expo_horario'         => 'text',
		'expo_entrada'         => 'text',
		'expo_intro'           => 'textarea',
		'expo_brochure'        => 'url',
		'expo_youtube'         => 'url',
		'expo_video_mp4'       => 'url',
		'expo_registro_url'    => 'url',
		'expo_capacitacion_url'=> 'url',
		'expo_programa_url'    => 'url',
		'expo_capacitacion_txt'=> 'text',
		'expo_whatsapp'        => 'text',
		'expo_email'           => 'text',
		'expo_direccion'       => 'text',
		'expo_mapa_embed'      => 'textarea', // iframe de Google Maps.
		'expo_plano'           => 'url',      // imagen del plano.
		'expo_galeria'         => 'text',     // IDs de adjuntos separados por coma.
		// Precios de stands.
		'expo_stand_incluye'   => 'textarea', // un ítem por línea.
		'expo_precio_int_socio'   => 'text',
		'expo_precio_int_nosocio' => 'text',
		'expo_precio_ext_socio'   => 'text',
		'expo_precio_ext_nosocio' => 'text',
		'expo_precio_nota'     => 'text',
		// Banner promocional en el home.
		'expo_banner_activo'   => 'text',  // "si" para mostrarlo.
		'expo_banner_texto'    => 'text',
		'expo_banner_cta_texto'=> 'text',
		'expo_banner_cta_link' => 'url',
	);
}
/** Estadísticas: 5 pares número + etiqueta. */
function asoinco_expo_stat_fields() {
	$f = array();
	for ( $i = 1; $i <= 5; $i++ ) {
		$f[ "expo_stat{$i}_num" ] = 'text';
		$f[ "expo_stat{$i}_lbl" ] = 'text';
	}
	return $f;
}
/** Beneficios: 3 expositor + 3 visitante (título + texto). */
function asoinco_expo_benefit_fields() {
	$f = array();
	foreach ( array( 'exp', 'vis' ) as $t ) {
		for ( $i = 1; $i <= 3; $i++ ) {
			$f[ "expo_ben{$t}{$i}_t" ] = 'text';
			$f[ "expo_ben{$t}{$i}_d" ] = 'textarea';
		}
	}
	return $f;
}
function asoinco_expo_all_fields() {
	return array_merge( asoinco_expo_fields(), asoinco_expo_stat_fields(), asoinco_expo_benefit_fields() );
}
/** Secciones de la página que se pueden mostrar/ocultar. */
function asoinco_expo_secciones() {
	return array(
		'infocards'     => 'Tarjetas ¿Dónde / Cuándo / Horario?',
		'countdown'     => 'Contador regresivo',
		'stats'         => 'Cifras / estadísticas',
		'intro'         => '¿Qué es la Expo?',
		'video'         => 'Video',
		'capacitaciones'=> 'Capacitaciones certificadas',
		'beneficios'    => 'Beneficios (expositor y visitante)',
		'precios'       => 'Info de stands y precios',
		'stand'         => 'Formulario (¿stand o expositor?)',
		'brochure'      => 'Banda de descarga del brochure',
		'galeria'       => 'Galería de fotos',
		'plano'         => 'Plano del evento',
		'auspiciadores' => 'Auspiciadores',
		'mapa'          => 'Cómo llegar (mapa)',
	);
}

/**
 * CPT "Auspiciador" (logos de auspiciadores/colaboradores).
 */
add_action( 'init', function () {
	register_post_type( 'auspiciador', array(
		'labels' => array(
			'name'          => 'Auspiciadores',
			'singular_name' => 'Auspiciador',
			'add_new_item'  => 'Añadir auspiciador',
			'edit_item'     => 'Editar auspiciador',
			'all_items'     => 'Todos los auspiciadores',
			'menu_name'     => 'Auspiciadores',
		),
		'public'       => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-awards',
		'menu_position'=> 26,
		'supports'     => array( 'title', 'thumbnail' ), // título = nombre; thumbnail = logo.
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'auspiciador' ),
	) );

	// CPT interno para guardar solicitudes de stand.
	register_post_type( 'solicitud_stand', array(
		'labels' => array(
			'name'          => 'Solicitudes de stand',
			'singular_name' => 'Solicitud de stand',
			'all_items'     => 'Solicitudes de stand',
			'menu_name'     => 'Solicitudes stand',
		),
		'public'       => false,
		'show_ui'      => true,
		'menu_icon'    => 'dashicons-tickets-alt',
		'menu_position'=> 27,
		'supports'     => array( 'title' ),
		'capability_type' => 'post',
	) );
} );

/**
 * Redirigir la URL antigua /expo-2024/ a la genérica /expo/ (301, para SEO/enlaces).
 */
add_action( 'template_redirect', function () {
	$uri = $_SERVER['REQUEST_URI'] ?? '';
	if ( is_404() && preg_match( '~/expo-2024/?~', $uri ) ) {
		wp_safe_redirect( home_url( '/expo/' ), 301 );
		exit;
	}
} );

/**
 * Auspiciador: instrucción clara para subir el logo + sitio web opcional.
 */
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'ausp_datos', 'Logo y datos del auspiciador', function ( $post ) {
		wp_nonce_field( 'ausp_save', 'ausp_nonce' );
		$web = get_post_meta( $post->ID, 'ausp_web', true );
		echo '<p style="background:#eef6ff;border:1px solid #cfe2ff;border-radius:6px;padding:12px 14px;font-size:13px;color:#1B3A6B">📷 <strong>El logo se sube en la caja «Imagen destacada»</strong> (columna derecha). Recórtalo ajustado, sin mucho espacio en blanco, para que se vea bien.</p>';
		echo '<p><label for="ausp_web"><strong>Sitio web (opcional)</strong></label><br><input type="text" id="ausp_web" name="ausp_web" value="' . esc_attr( $web ) . '" placeholder="https://www.auspiciador.cl" style="width:100%;max-width:480px"><br><span style="color:#666;font-size:12px">Si lo pones, el logo será un enlace al sitio del auspiciador.</span></p>';
	}, 'auspiciador', 'normal', 'high' );
} );
add_action( 'save_post_auspiciador', function ( $post_id ) {
	if ( ! isset( $_POST['ausp_nonce'] ) || ! wp_verify_nonce( $_POST['ausp_nonce'], 'ausp_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['ausp_web'] ) ) {
		update_post_meta( $post_id, 'ausp_web', esc_url_raw( wp_unslash( $_POST['ausp_web'] ) ) );
	}
} );

/**
 * Meta box de la Expo (solo en la página Expo).
 */
add_action( 'add_meta_boxes', function () {
	global $post;
	if ( ! $post || 'page' !== $post->post_type || ASOINCO_EXPO_SLUG !== $post->post_name ) {
		return;
	}
	add_meta_box( 'asoinco_expo_box', '🎪 Configuración de la Expo ASOINCO', 'asoinco_expo_metabox', 'page', 'normal', 'high' );
} );

function asoinco_expo_field_input( $id, $label, $type = 'text', $help = '' ) {
	$val = get_post_meta( get_the_ID(), $id, true );
	echo '<p style="margin:0 0 14px"><label for="' . esc_attr( $id ) . '" style="display:block;font-weight:600;margin-bottom:3px">' . esc_html( $label ) . '</label>';
	if ( 'textarea' === $type ) {
		echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" rows="3" style="width:100%;max-width:640px">' . esc_textarea( $val ) . '</textarea>';
	} else {
		// 'url' se renderiza como texto para no bloquear rutas relativas (/wp-content/...); igual se sanitiza al guardar.
		$input_type = ( 'date' === $type ) ? 'date' : 'text';
		echo '<input type="' . esc_attr( $input_type ) . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="' . esc_attr( $val ) . '" style="width:100%;max-width:640px">';
	}
	if ( $help ) {
		echo '<br><span style="color:#666;font-size:12px">' . esc_html( $help ) . '</span>';
	}
	echo '</p>';
}

function asoinco_expo_metabox( $post ) {
	wp_nonce_field( 'asoinco_expo_save', 'asoinco_expo_nonce' );
	echo '<style>.expo-adm h3{border-bottom:2px solid #eee;padding-bottom:6px;margin:22px 0 12px;color:#1B3A6B}.expo-adm .cols{display:grid;grid-template-columns:1fr 1fr;gap:0 24px}</style>';
	echo '<div class="expo-adm">';

	echo '<h3>👁️ Secciones visibles en la página</h3>';
	echo '<p style="color:#666;font-size:12px;margin-top:-4px">Desmarca las secciones que quieras <strong>ocultar</strong> durante el año (para no confundir cuando la Expo está lejos). Márcalas todas cuando empiece la difusión fuerte.</p>';
	echo '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px 24px;background:#f6f7f7;border:1px solid #e2e4e7;border-radius:8px;padding:14px 16px">';
	foreach ( asoinco_expo_secciones() as $k => $lbl ) {
		$v       = get_post_meta( $post->ID, 'expo_show_' . $k, true );
		$checked = ( 'no' !== $v ) ? ' checked' : '';
		echo '<label style="display:flex;align-items:center;gap:8px;font-weight:600"><input type="checkbox" name="expo_show_' . esc_attr( $k ) . '" value="si"' . $checked . '> ' . esc_html( $lbl ) . '</label>';
	}
	echo '</div>';

	echo '<h3>Datos principales</h3><div class="cols">';
	asoinco_expo_field_input( 'expo_titulo', 'Título del evento', 'text' );
	asoinco_expo_field_input( 'expo_tagline', 'Frase (tagline) del hero', 'text' );
	asoinco_expo_field_input( 'expo_fechas_texto', 'Fechas (texto visible)', 'text' );
	asoinco_expo_field_input( 'expo_fecha_evento', 'Fecha del evento (para el contador)', 'date' );
	asoinco_expo_field_input( 'expo_lugar', 'Lugar', 'text' );
	asoinco_expo_field_input( 'expo_horario', 'Horario', 'text' );
	asoinco_expo_field_input( 'expo_entrada', 'Entrada (ej: Entrada liberada)', 'text' );
	asoinco_expo_field_input( 'expo_estado', 'Estado (activa / pasada)', 'text', 'Escribe "activa" mientras la Expo esté vigente.' );
	echo '</div>';
	asoinco_expo_field_input( 'expo_intro', '¿Qué es la Expo? (texto)', 'textarea' );

	echo '<h3>Estadísticas (5 cifras)</h3><div class="cols">';
	for ( $i = 1; $i <= 5; $i++ ) {
		asoinco_expo_field_input( "expo_stat{$i}_num", "Cifra {$i} — número", 'text' );
		asoinco_expo_field_input( "expo_stat{$i}_lbl", "Cifra {$i} — etiqueta", 'text' );
	}
	echo '</div>';

	echo '<h3>Beneficios para el EXPOSITOR</h3><div class="cols">';
	for ( $i = 1; $i <= 3; $i++ ) {
		asoinco_expo_field_input( "expo_benexp{$i}_t", "Beneficio {$i} — título", 'text' );
		asoinco_expo_field_input( "expo_benexp{$i}_d", "Beneficio {$i} — texto", 'textarea' );
	}
	echo '</div>';

	echo '<h3>Beneficios para el VISITANTE</h3><div class="cols">';
	for ( $i = 1; $i <= 3; $i++ ) {
		asoinco_expo_field_input( "expo_benvis{$i}_t", "Beneficio {$i} — título", 'text' );
		asoinco_expo_field_input( "expo_benvis{$i}_d", "Beneficio {$i} — texto", 'textarea' );
	}
	echo '</div>';

	echo '<h3>Acción y contacto</h3><div class="cols">';
	asoinco_expo_field_input( 'expo_whatsapp', 'WhatsApp (ej: 56997998887)', 'text' );
	asoinco_expo_field_input( 'expo_email', 'Email de contacto Expo', 'text' );
	asoinco_expo_field_input( 'expo_registro_url', 'Link "Registra tu visita" ( #stand = va al formulario )', 'text' );
	asoinco_expo_field_input( 'expo_capacitacion_url', 'Link capacitaciones ( #stand = va al formulario )', 'text' );
	asoinco_expo_field_input( 'expo_capacitacion_txt', 'Texto de capacitaciones (opcional)', 'text' );
	echo '</div>';
	// Cronograma como archivo adjunto (PDF o imagen).
	$prog = get_post_meta( get_the_ID(), 'expo_programa_url', true );
	echo '<p style="margin:14px 0 0"><label style="display:block;font-weight:600;margin-bottom:3px">Cronograma de la Expo (archivo PDF o imagen)</label>';
	echo '<input type="text" id="expo_programa_url" name="expo_programa_url" value="' . esc_attr( $prog ) . '" style="width:100%;max-width:500px"> ';
	echo '<button type="button" class="button" id="expo_programa_btn">📎 Subir / elegir cronograma</button>';
	echo '<br><span style="color:#666;font-size:12px">Sube el archivo; el botón «Conoce el cronograma» lo abrirá. Si está vacío, el botón no aparece.</span></p>';

	echo '<h3>Multimedia</h3>';
	$vmp4 = get_post_meta( get_the_ID(), 'expo_video_mp4', true );
	echo '<p style="margin:0 0 14px"><label style="display:block;font-weight:600;margin-bottom:3px">Video propio (MP4)</label>';
	echo '<input type="text" id="expo_video_mp4" name="expo_video_mp4" value="' . esc_attr( $vmp4 ) . '" style="width:100%;max-width:500px"> ';
	echo '<button type="button" class="button" id="expo_video_btn">📎 Subir / elegir video</button>';
	echo '<br><span style="color:#666;font-size:12px">Si subes un video propio, tiene prioridad sobre el de YouTube.</span></p>';
	asoinco_expo_field_input( 'expo_youtube', 'Video de YouTube (link) — alternativa', 'url', 'Se usa solo si no hay video propio.' );
	$broch = get_post_meta( get_the_ID(), 'expo_brochure', true );
	echo '<p style="margin:0 0 14px"><label style="display:block;font-weight:600;margin-bottom:3px">Brochure (PDF)</label>';
	echo '<input type="text" id="expo_brochure" name="expo_brochure" value="' . esc_attr( $broch ) . '" style="width:100%;max-width:500px"> ';
	echo '<button type="button" class="button" id="expo_brochure_btn">📎 Subir / elegir archivo</button>';
	echo '<br><span style="color:#666;font-size:12px">Sube el PDF y su enlace se completa solo.</span></p>';

	echo '<h3>Ubicación y plano</h3>';
	asoinco_expo_field_input( 'expo_direccion', 'Dirección / cómo llegar', 'text' );
	asoinco_expo_field_input( 'expo_mapa_embed', 'Mapa (código iframe de Google Maps)', 'textarea' );
	asoinco_expo_field_input( 'expo_plano', 'Imagen del plano del evento (URL)', 'url', 'Sube la imagen en Medios y pega aquí su URL.' );

	echo '<h3>Galería de fotos</h3>';
	$gal = get_post_meta( $post->ID, 'expo_galeria', true );
	echo '<input type="hidden" id="expo_galeria" name="expo_galeria" value="' . esc_attr( $gal ) . '">';
	echo '<button type="button" class="button" id="expo_galeria_btn">📷 Seleccionar imágenes de la galería</button> ';
	echo '<button type="button" class="button" id="expo_galeria_clear">Limpiar</button>';
	echo '<div id="expo_galeria_preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:12px"></div>';

	echo '<h3>Precios de stands</h3>';
	asoinco_expo_field_input( 'expo_stand_incluye', 'Qué incluye el stand (un ítem por línea)', 'textarea' );
	echo '<div class="cols">';
	asoinco_expo_field_input( 'expo_precio_int_socio', 'Stand Interior — Socio', 'text' );
	asoinco_expo_field_input( 'expo_precio_int_nosocio', 'Stand Interior — No socio', 'text' );
	asoinco_expo_field_input( 'expo_precio_ext_socio', 'Stand Exterior — Socio', 'text' );
	asoinco_expo_field_input( 'expo_precio_ext_nosocio', 'Stand Exterior — No socio', 'text' );
	echo '</div>';
	asoinco_expo_field_input( 'expo_precio_nota', 'Nota de precios (ej: Valores + IVA)', 'text' );

	echo '<h3>📢 Banner promocional en el home</h3>';
	$ban = get_post_meta( get_the_ID(), 'expo_banner_activo', true );
	echo '<p style="background:#fff8e6;border:1px solid #f5d78a;border-radius:6px;padding:12px 14px"><label style="font-weight:600"><input type="checkbox" name="expo_banner_activo" value="si" ' . checked( $ban, 'si', false ) . '> Mostrar el banner de la Expo en la página de inicio</label><br><span style="color:#666;font-size:12px">Actívalo cuando empiece la difusión. Aparece arriba del home, sin quitar el resto del contenido.</span></p>';
	asoinco_expo_field_input( 'expo_banner_texto', 'Texto del banner', 'text' );
	echo '<div class="cols">';
	asoinco_expo_field_input( 'expo_banner_cta_texto', 'Texto del botón', 'text' );
	asoinco_expo_field_input( 'expo_banner_cta_link', 'Link del botón', 'url' );
	echo '</div>';

	echo '<p style="color:#666;font-size:12px;border-top:1px solid #eee;padding-top:10px;margin-top:20px"><strong>Auspiciadores:</strong> se gestionan en el menú «Auspiciadores» (cada uno con su logo). Aparecen automáticamente en la Expo.</p>';
	echo '</div>';
}

/**
 * Selector de galería (medios) en el editor de la Expo.
 */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	global $post;
	if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && $post && ASOINCO_EXPO_SLUG === $post->post_name ) {
		wp_enqueue_media();
	}
} );
add_action( 'admin_footer', function () {
	global $post;
	if ( ! $post || ASOINCO_EXPO_SLUG !== $post->post_name ) {
		return;
	}
	?>
	<script>
	jQuery(function ($) {
		var input = $('#expo_galeria'), preview = $('#expo_galeria_preview');
		function render() {
			preview.empty();
			(input.val() ? input.val().split(',') : []).forEach(function (id) {
				id = id.trim(); if (!id) return;
				preview.append('<img data-id="' + id + '" src="/wp-content/uploads/" style="width:70px;height:70px;object-fit:cover;border-radius:6px;border:1px solid #ddd" class="expo-gal-thumb">');
			});
			// Cargar miniaturas reales vía REST.
			preview.find('.expo-gal-thumb').each(function () {
				var el = $(this);
				$.get('/wp-json/wp/v2/media/' + el.data('id'), function (m) {
					if (m && m.media_details && m.media_details.sizes && m.media_details.sizes.thumbnail) {
						el.attr('src', m.media_details.sizes.thumbnail.source_url);
					} else if (m && m.source_url) { el.attr('src', m.source_url); }
				});
			});
		}
		$('#expo_galeria_btn').on('click', function (e) {
			e.preventDefault();
			var frame = wp.media({ title: 'Galería de la Expo', multiple: true, library: { type: 'image' }, button: { text: 'Usar estas imágenes' } });
			frame.on('select', function () {
				var ids = frame.state().get('selection').map(function (a) { return a.id; });
				input.val(ids.join(','));
				render();
			});
			frame.open();
		});
		$('#expo_galeria_clear').on('click', function (e) { e.preventDefault(); input.val(''); render(); });
		$('#expo_brochure_btn').on('click', function (e) {
			e.preventDefault();
			var bf = wp.media({ title: 'Brochure de la Expo', multiple: false, button: { text: 'Usar este archivo' } });
			bf.on('select', function () { $('#expo_brochure').val(bf.state().get('selection').first().toJSON().url); });
			bf.open();
		});
		$('#expo_video_btn').on('click', function (e) {
			e.preventDefault();
			var vf = wp.media({ title: 'Video de la Expo', multiple: false, library: { type: 'video' }, button: { text: 'Usar este video' } });
			vf.on('select', function () { $('#expo_video_mp4').val(vf.state().get('selection').first().toJSON().url); });
			vf.open();
		});
		$('#expo_programa_btn').on('click', function (e) {
			e.preventDefault();
			var pf = wp.media({ title: 'Cronograma de la Expo', multiple: false, button: { text: 'Usar este archivo' } });
			pf.on('select', function () { $('#expo_programa_url').val(pf.state().get('selection').first().toJSON().url); });
			pf.open();
		});
		render();
	});
	</script>
	<?php
} );

/**
 * Guardar los campos de la Expo.
 */
add_action( 'save_post_page', function ( $post_id ) {
	if ( ! isset( $_POST['asoinco_expo_nonce'] ) || ! wp_verify_nonce( $_POST['asoinco_expo_nonce'], 'asoinco_expo_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( asoinco_expo_all_fields() as $key => $type ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $key ] );
		if ( 'url' === $type ) {
			$val = esc_url_raw( $raw );
		} elseif ( 'textarea' === $type ) {
			$val = ( 'expo_mapa_embed' === $key ) ? wp_kses( $raw, array( 'iframe' => array( 'src' => array(), 'width' => array(), 'height' => array(), 'style' => array(), 'loading' => array(), 'allowfullscreen' => array(), 'referrerpolicy' => array() ) ) ) : sanitize_textarea_field( $raw );
		} else {
			$val = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, $key, $val );
	}
	if ( isset( $_POST['expo_galeria'] ) ) {
		$ids = array_filter( array_map( 'absint', explode( ',', wp_unslash( $_POST['expo_galeria'] ) ) ) );
		update_post_meta( $post_id, 'expo_galeria', implode( ',', $ids ) );
	}
	// Checkbox del banner: si no viene marcado, queda desactivado.
	update_post_meta( $post_id, 'expo_banner_activo', ( isset( $_POST['expo_banner_activo'] ) && 'si' === $_POST['expo_banner_activo'] ) ? 'si' : '' );

	// Visibilidad de secciones.
	foreach ( asoinco_expo_secciones() as $sk => $slbl ) {
		update_post_meta( $post_id, 'expo_show_' . $sk, isset( $_POST[ 'expo_show_' . $sk ] ) ? 'si' : 'no' );
	}
} );

/**
 * Formulario "Quiero un stand": guarda la solicitud + avisa por correo.
 */
add_action( 'template_redirect', function () {
	if ( empty( $_POST['asoinco_stand_submit'] ) ) {
		return;
	}
	if ( ! isset( $_POST['asoinco_stand_nonce'] ) || ! wp_verify_nonce( $_POST['asoinco_stand_nonce'], 'asoinco_stand_form' ) ) {
		return;
	}
	$interes = sanitize_text_field( wp_unslash( $_POST['interes'] ?? '' ) );
	$empresa = sanitize_text_field( wp_unslash( $_POST['empresa'] ?? '' ) );
	$nombre  = sanitize_text_field( wp_unslash( $_POST['nombre'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$fono    = sanitize_text_field( wp_unslash( $_POST['fono'] ?? '' ) );
	$mensaje = sanitize_textarea_field( wp_unslash( $_POST['mensaje'] ?? '' ) );

	if ( ! $empresa || ! $email || ! $interes ) {
		wp_safe_redirect( add_query_arg( 'stand', 'faltan', wp_get_referer() ) );
		exit;
	}

	$id = wp_insert_post( array(
		'post_title'  => $empresa . ' — ' . $interes,
		'post_type'   => 'solicitud_stand',
		'post_status' => 'publish',
	) );
	if ( $id && ! is_wp_error( $id ) ) {
		foreach ( array( 'interes' => $interes, 'empresa' => $empresa, 'nombre' => $nombre, 'email' => $email, 'fono' => $fono, 'mensaje' => $mensaje ) as $k => $v ) {
			update_post_meta( $id, 'stand_' . $k, $v );
		}
		$to      = defined( 'ASOINCO_FORM_EMAIL' ) ? ASOINCO_FORM_EMAIL : get_option( 'admin_email' );
		$headers = $email ? array( 'Reply-To: ' . $nombre . ' <' . $email . '>' ) : array();
		wp_mail( $to, 'Expo ASOINCO — ' . $interes . ' (' . $empresa . ')', "Interés: $interes\nEmpresa: $empresa\nContacto: $nombre\nEmail: $email\nTeléfono: $fono\n\nMensaje:\n$mensaje", $headers );
	}
	wp_safe_redirect( add_query_arg( 'stand', 'ok', wp_get_referer() ) );
	exit;
} );

/**
 * Mostrar los datos de cada solicitud de stand en el admin.
 */
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'stand_datos', 'Datos de la solicitud', function ( $post ) {
		foreach ( array( 'interes' => 'Interés', 'empresa' => 'Empresa', 'nombre' => 'Contacto', 'email' => 'Email', 'fono' => 'Teléfono', 'mensaje' => 'Mensaje' ) as $k => $lbl ) {
			echo '<p><strong>' . esc_html( $lbl ) . ':</strong> ' . esc_html( get_post_meta( $post->ID, 'stand_' . $k, true ) ) . '</p>';
		}
	}, 'solicitud_stand', 'normal', 'high' );
} );

/**
 * Valores por defecto de la Expo + auspiciadores de ejemplo (una sola vez).
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_expo_seeded' ) ) {
		return;
	}
	$page = get_page_by_path( ASOINCO_EXPO_SLUG );
	if ( $page ) {
		$defaults = array(
			'expo_estado'       => 'activa',
			'expo_titulo'       => 'Expo ASOINCO 2027',
			'expo_tagline'      => 'La feria multisectorial más grande de la Región de Coquimbo',
			'expo_fechas_texto' => '11, 12 y 13 de Noviembre 2027',
			'expo_fecha_evento' => '2027-11-11',
			'expo_lugar'        => 'Club de Golf Pan de Azúcar, Coquimbo',
			'expo_horario'      => '11:00 a 20:00 hrs',
			'expo_entrada'      => 'Entrada liberada',
			'expo_intro'        => 'Expo ASOINCO es la muestra empresarial multisectorial más grande e importante de la Región de Coquimbo, donde confluyen el sector privado, el sector público y la academia para potenciar negocios, acceder a financiamiento y contribuir al desarrollo de la región.',
			'expo_whatsapp'     => '56997998887',
			'expo_email'        => 'contacto@asoinco.cl',
			'expo_stat1_num'    => '+100', 'expo_stat1_lbl' => 'stands',
			'expo_stat2_num'    => '+85',  'expo_stat2_lbl' => 'empresas',
			'expo_stat3_num'    => '+2.000', 'expo_stat3_lbl' => 'visitantes',
			'expo_stat4_num'    => 'US$1,5M', 'expo_stat4_lbl' => 'en negocios',
			'expo_stat5_num'    => '3', 'expo_stat5_lbl' => 'días de feria',
			'expo_benexp1_t'    => 'Networking eficiente', 'expo_benexp1_d' => 'Accede a la más grande red de contactos en un solo lugar y cierra nuevos negocios.',
			'expo_benexp2_t'    => 'Crecimiento empresarial', 'expo_benexp2_d' => 'Aumenta tus ventas, mejora costos y accede a nuevas oportunidades de financiamiento.',
			'expo_benexp3_t'    => 'Visibilidad', 'expo_benexp3_d' => 'Muestra tu empresa ante miles de visitantes, autoridades y medios de la región.',
			'expo_benvis1_t'    => 'Diversidad de proveedores', 'expo_benvis1_d' => 'Encuentra proveedores de los más variados rubros en un solo lugar.',
			'expo_benvis2_t'    => 'Networking e investigación', 'expo_benvis2_d' => 'Conecta con actividades inéditas y con la investigación de las universidades.',
			'expo_benvis3_t'    => 'Innovación', 'expo_benvis3_d' => 'Descubre productos y soluciones innovadoras de la industria regional.',
		);
		foreach ( $defaults as $k => $v ) {
			if ( '' === get_post_meta( $page->ID, $k, true ) ) {
				update_post_meta( $page->ID, $k, $v );
			}
		}
	}

	$existentes = get_posts( array( 'post_type' => 'auspiciador', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
	if ( empty( $existentes ) ) {
		foreach ( array( 'CORFO', 'Universidad Católica del Norte', 'CMP' ) as $a ) {
			wp_insert_post( array( 'post_title' => $a, 'post_type' => 'auspiciador', 'post_status' => 'publish' ) );
		}
	}

	update_option( 'asoinco_expo_seeded', 1 );
} );

/**
 * Migración: dejar la Expo configurada para 2027 (una sola vez).
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_expo_2027' ) ) {
		return;
	}
	$page = get_page_by_path( ASOINCO_EXPO_SLUG );
	if ( $page ) {
		update_post_meta( $page->ID, 'expo_titulo', 'Expo ASOINCO 2027' );
		update_post_meta( $page->ID, 'expo_fechas_texto', '11, 12 y 13 de Noviembre 2027' );
		update_post_meta( $page->ID, 'expo_fecha_evento', '2027-11-11' );
	}
	update_option( 'asoinco_expo_2027', 1 );
} );

/**
 * Demo: poblar galería, mapa, video, plano y brochure para mostrar la página completa (una sola vez).
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_expo_demo' ) ) {
		return;
	}
	$page = get_page_by_path( ASOINCO_EXPO_SLUG );
	if ( ! $page ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$base = 'https://www.asoinco.cl/wp-content/uploads/2025/05/';
	$imgs = array(
		'EXPO-ASOINCO-2024-PT3-130-scaled.jpg', 'EXPO-ASOINCO-2024-70-scaled.jpg',
		'EXPO-ASOINCO-2024-44-scaled.jpg', 'EXPO-ASOINCO-2024-84-scaled.jpg',
		'EXPO-ASOINCO-2024-22-scaled.jpg', 'EXPO-ASOINCO-2024-PT3-53-scaled.jpg',
		'EXPO-ASOINCO-2024-73-scaled.jpg', 'EXPO-ASOINCO-2024-43-scaled.jpg',
	);
	$ids = array();
	foreach ( $imgs as $im ) {
		$att = media_sideload_image( $base . $im, $page->ID, 'Expo ASOINCO', 'id' );
		if ( ! is_wp_error( $att ) ) {
			$ids[] = $att;
		}
	}
	if ( $ids ) {
		update_post_meta( $page->ID, 'expo_galeria', implode( ',', $ids ) );
		update_post_meta( $page->ID, 'expo_plano', wp_get_attachment_image_url( $ids[0], 'large' ) ); // placeholder: reemplazar por el plano real.
	}
	update_post_meta( $page->ID, 'expo_youtube', 'https://www.youtube.com/watch?v=nbYyciPyfE8' );
	update_post_meta( $page->ID, 'expo_direccion', 'Club de Golf Pan de Azúcar, Coquimbo, Región de Coquimbo' );
	update_post_meta( $page->ID, 'expo_mapa_embed', '<iframe src="https://www.google.com/maps?q=Club+de+Golf+Pan+de+Azucar,+Coquimbo&output=embed" width="100%" height="380" style="border:0" loading="lazy"></iframe>' );
	update_post_meta( $page->ID, 'expo_brochure', 'https://expoasoinco.cl/' );

	update_option( 'asoinco_expo_demo', 1 );
} );

/**
 * Defaults de precios de stands y banner (una sola vez).
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_expo_precios' ) ) {
		return;
	}
	$page = get_page_by_path( ASOINCO_EXPO_SLUG );
	if ( $page ) {
		$d = array(
			'expo_stand_incluye'      => "Modulación octonorma (solo stands interiores)\nPunto eléctrico\n2 sillas plegables\nMesa cuadrada (1,0 m x 0,5 m)\nMantel blanco\nNombre de empresa en cenefa (excepto stands exteriores)\nCubre piso (solo stands exteriores)",
			'expo_precio_int_socio'   => '$700.000',
			'expo_precio_int_nosocio' => '$800.000',
			'expo_precio_ext_socio'   => '$800.000',
			'expo_precio_ext_nosocio' => '$900.000',
			'expo_precio_nota'        => 'Valores + IVA',
			'expo_banner_activo'      => 'si',
			'expo_banner_texto'       => '¡Ya viene la Expo ASOINCO 2027! · 11, 12 y 13 de Noviembre · Club de Golf Pan de Azúcar',
			'expo_banner_cta_texto'   => 'Adquiere tu stand →',
			'expo_banner_cta_link'    => home_url( '/expo-2024/#stand' ),
		);
		foreach ( $d as $k => $v ) {
			if ( '' === get_post_meta( $page->ID, $k, true ) ) {
				update_post_meta( $page->ID, $k, $v );
			}
		}
	}
	update_option( 'asoinco_expo_precios', 1 );
} );

/**
 * Defaults de registro/cronograma/capacitaciones (placeholders, reemplazar por links reales).
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_expo_extras' ) ) {
		return;
	}
	$page = get_page_by_path( ASOINCO_EXPO_SLUG );
	if ( $page ) {
		$d = array(
			'expo_registro_url'     => 'https://expoasoinco.cl/',
			'expo_programa_url'     => 'https://expoasoinco.cl/',
			'expo_capacitacion_url' => 'https://expoasoinco.cl/',
			'expo_capacitacion_txt' => 'Súmate a las charlas y talleres certificados de la Expo, con relatores de primer nivel y cupos limitados.',
		);
		foreach ( $d as $k => $v ) {
			if ( '' === get_post_meta( $page->ID, $k, true ) ) {
				update_post_meta( $page->ID, $k, $v );
			}
		}
	}
	update_option( 'asoinco_expo_extras', 1 );
} );

/**
 * Reemplazar los links externos (expoasoinco.cl, desactualizado) por el formulario interno.
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_expo_links_form' ) ) {
		return;
	}
	$page = get_page_by_path( ASOINCO_EXPO_SLUG );
	if ( $page ) {
		$form = home_url( '/expo/#stand' );
		foreach ( array( 'expo_registro_url', 'expo_programa_url', 'expo_capacitacion_url', 'expo_brochure' ) as $k ) {
			$cur = get_post_meta( $page->ID, $k, true );
			if ( '' === $cur || false !== strpos( $cur, 'expoasoinco.cl' ) ) {
				update_post_meta( $page->ID, $k, $form );
			}
		}
	}
	update_option( 'asoinco_expo_links_form', 1 );
} );

/**
 * Ancla relativa para scroll suave (registro/capacitaciones) + cronograma vuelve a ser archivo.
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_expo_links2' ) ) {
		return;
	}
	$page = get_page_by_path( ASOINCO_EXPO_SLUG );
	if ( $page ) {
		update_post_meta( $page->ID, 'expo_registro_url', '#stand' );
		update_post_meta( $page->ID, 'expo_capacitacion_url', '#stand' );
		$prog = get_post_meta( $page->ID, 'expo_programa_url', true );
		if ( false !== strpos( (string) $prog, '#stand' ) || false !== strpos( (string) $prog, 'expoasoinco' ) ) {
			update_post_meta( $page->ID, 'expo_programa_url', '' ); // cronograma es archivo; vacío hasta que suban uno.
		}
	}
	update_option( 'asoinco_expo_links2', 1 );
} );

/**
 * El brochure debe ser un archivo (PDF), no un link al formulario: limpiar el valor equivocado.
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_expo_brochure_fix' ) ) {
		return;
	}
	$page = get_page_by_path( ASOINCO_EXPO_SLUG );
	if ( $page ) {
		$b = get_post_meta( $page->ID, 'expo_brochure', true );
		if ( false !== strpos( (string) $b, '#stand' ) || false !== strpos( (string) $b, 'expoasoinco' ) ) {
			update_post_meta( $page->ID, 'expo_brochure', '' ); // archivo; vacío hasta que suban el PDF real.
		}
	}
	update_option( 'asoinco_expo_brochure_fix', 1 );
} );

/**
 * Menú "Expo ASOINCO" en el panel → lleva directo a editar la Expo (con el interruptor del banner).
 */
add_action( 'admin_menu', function () {
	$page = get_page_by_path( ASOINCO_EXPO_SLUG );
	if ( $page ) {
		add_menu_page( 'Configurar Expo', 'Expo ASOINCO', 'edit_pages', 'post.php?post=' . $page->ID . '&action=edit', '', 'dashicons-megaphone', 24 );
	}
} );

/**
 * Aplicar el video propio y el plano reales subidos (una sola vez).
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_expo_media3' ) ) {
		return;
	}
	$page = get_page_by_path( ASOINCO_EXPO_SLUG );
	if ( $page ) {
		update_post_meta( $page->ID, 'expo_video_mp4', '/wp-content/uploads/expo/lanzamiento-expo.mp4' );
		update_post_meta( $page->ID, 'expo_plano', '/wp-content/uploads/expo/mapa-feria.jpg' );
	}
	update_option( 'asoinco_expo_media3', 1 );
} );
