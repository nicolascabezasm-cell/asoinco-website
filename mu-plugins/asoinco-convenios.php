<?php
/**
 * ASOINCO — Convenios (Club de Beneficios).
 * CPT "convenio" + taxonomía de categoría + campos (beneficio / cómo acceder / web).
 * Autoadministrable, como Socios.
 *
 * @package asoinco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registrar el CPT "convenio" y la taxonomía de categorías.
 */
add_action( 'init', function () {
	register_post_type( 'convenio', array(
		'labels' => array(
			'name'               => 'Convenios',
			'singular_name'      => 'Convenio',
			'add_new'            => 'Añadir convenio',
			'add_new_item'       => 'Añadir nuevo convenio',
			'edit_item'          => 'Editar convenio',
			'all_items'          => 'Todos los convenios',
			'menu_name'          => 'Convenios',
		),
		'public'        => true,
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-tag',
		'menu_position' => 28,
		'supports'      => array( 'title', 'thumbnail' ), // título = empresa/convenio; thumbnail = logo.
		'show_in_rest'  => true,
		'rewrite'       => array( 'slug' => 'convenio' ),
	) );

	register_taxonomy( 'categoria_convenio', 'convenio', array(
		'labels' => array(
			'name'          => 'Categorías de convenio',
			'singular_name' => 'Categoría',
			'menu_name'     => 'Categorías',
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'categoria-convenio' ),
	) );
} );

/**
 * Caja de datos del convenio.
 */
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'convenio_datos', 'Datos del convenio', 'asoinco_convenio_metabox', 'convenio', 'normal', 'high' );
} );

function asoinco_convenio_metabox( $post ) {
	?>
	<p style="background:#eef6ff;border:1px solid #cfe2ff;border-radius:6px;padding:12px 14px;font-size:13px;color:#1B3A6B">
		🖼️ <strong>Sube el flyer del convenio en «Imagen destacada»</strong> (columna derecha).<br>
		El <strong>título</strong> es el nombre de la empresa o convenio (solo para identificarlo en el admin).
	</p>
	<?php
}

add_action( 'save_post_convenio', function ( $post_id ) {
	if ( ! isset( $_POST['asoinco_convenio_nonce'] ) || ! wp_verify_nonce( $_POST['asoinco_convenio_nonce'], 'asoinco_convenio_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['convenio_beneficio'] ) ) {
		update_post_meta( $post_id, 'convenio_beneficio', sanitize_textarea_field( wp_unslash( $_POST['convenio_beneficio'] ) ) );
	}
	if ( isset( $_POST['convenio_como'] ) ) {
		update_post_meta( $post_id, 'convenio_como', sanitize_text_field( wp_unslash( $_POST['convenio_como'] ) ) );
	}
	if ( isset( $_POST['convenio_web'] ) ) {
		update_post_meta( $post_id, 'convenio_web', esc_url_raw( wp_unslash( $_POST['convenio_web'] ) ) );
	}
} );

/**
 * Sembrar categorías y convenios de ejemplo (una sola vez).
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_convenios_seeded' ) ) {
		return;
	}
	$cats = array( 'Salud', 'Educación', 'Financiero', 'Seguros', 'Combustible', 'Tecnología', 'Automotriz', 'Turismo' );
	foreach ( $cats as $c ) {
		if ( ! term_exists( $c, 'categoria_convenio' ) ) {
			wp_insert_term( $c, 'categoria_convenio' );
		}
	}

	$ejemplos = array(
		array( 'Clínica RedSalud Elqui', 'Salud', '15% de descuento en consultas médicas y exámenes para socios y su grupo familiar.', 'Presenta tu credencial de socio ASOINCO en admisión.', 'https://www.redsalud.cl' ),
		array( 'Universidad Católica del Norte', 'Educación', '20% en diplomados y cursos de educación continua.', 'Inscríbete indicando el convenio ASOINCO.', 'https://www.ucn.cl' ),
		array( 'Copec', 'Combustible', 'Descuento por litro de combustible con la Tarjeta de Socio.', 'Solicita tu tarjeta en la oficina de ASOINCO.', 'https://www.copec.cl' ),
		array( 'Banco de Chile', 'Financiero', 'Condiciones preferenciales en productos financieros para empresas socias.', 'Agenda con el ejecutivo de convenios.', 'https://www.bancochile.cl' ),
		array( 'HDI Seguros', 'Seguros', 'Tarifas preferentes en seguros para empresas y flotas.', 'Cotiza mencionando el convenio ASOINCO.', 'https://www.hdi.cl' ),
		array( 'Enex / Shell', 'Combustible', 'Descuentos en combustible y lubricantes para socios.', 'Adhiere tu empresa al convenio.', 'https://www.enex.cl' ),
	);
	foreach ( $ejemplos as $e ) {
		$id = wp_insert_post( array(
			'post_title'  => $e[0],
			'post_type'   => 'convenio',
			'post_status' => 'publish',
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			wp_set_object_terms( $id, $e[1], 'categoria_convenio' );
			update_post_meta( $id, 'convenio_beneficio', $e[2] );
			update_post_meta( $id, 'convenio_como', $e[3] );
			update_post_meta( $id, 'convenio_web', $e[4] );
		}
	}

	update_option( 'asoinco_convenios_seeded', 1 );
} );
