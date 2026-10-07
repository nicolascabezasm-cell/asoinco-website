<?php
/**
 * ASOINCO — Socios (Directorio).
 * Tipo de contenido "Socio" + taxonomía "Rubro" + campos Web/Email.
 * Vista pública: solo logo + rubro + web (pedido de Javier Julio).
 *
 * @package asoinco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registrar el tipo de contenido "Socio" y la taxonomía "Rubro".
 */
add_action( 'init', function () {

	register_post_type( 'socio', array(
		'labels' => array(
			'name'               => 'Socios',
			'singular_name'      => 'Socio',
			'add_new'            => 'Añadir socio',
			'add_new_item'       => 'Añadir nuevo socio',
			'edit_item'          => 'Editar socio',
			'new_item'           => 'Nuevo socio',
			'view_item'          => 'Ver socio',
			'search_items'       => 'Buscar socios',
			'not_found'          => 'No hay socios',
			'not_found_in_trash' => 'No hay socios en la papelera',
			'all_items'          => 'Todos los socios',
			'menu_name'          => 'Socios',
		),
		'public'        => true,
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-groups',
		'menu_position' => 25,
		'supports'      => array( 'title', 'thumbnail' ), // título = nombre empresa; thumbnail = logo.
		'show_in_rest'  => true,
		'rewrite'       => array( 'slug' => 'socio' ),
	) );

	register_taxonomy( 'rubro', 'socio', array(
		'labels' => array(
			'name'          => 'Rubros',
			'singular_name' => 'Rubro',
			'add_new_item'  => 'Añadir rubro',
			'menu_name'     => 'Rubros',
		),
		'public'            => true,
		'hierarchical'      => true, // casillas tipo categoría.
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'rubro' ),
	) );
} );

/**
 * Caja de campos "Datos del socio" (Web y Email).
 */
add_action( 'add_meta_boxes', function () {
	add_meta_box(
		'asoinco_socio_datos',
		'Datos del socio',
		'asoinco_socio_metabox',
		'socio',
		'normal',
		'high'
	);
} );

function asoinco_socio_metabox( $post ) {
	wp_nonce_field( 'asoinco_socio_save', 'asoinco_socio_nonce' );
	$web   = get_post_meta( $post->ID, 'socio_web', true );
	$email = get_post_meta( $post->ID, 'socio_email', true );
	?>
	<p>
		<label for="socio_web"><strong>Página web</strong></label><br>
		<input type="url" id="socio_web" name="socio_web" value="<?php echo esc_attr( $web ); ?>" placeholder="https://www.empresa.cl" style="width:100%;max-width:480px">
		<br><span style="color:#666;font-size:12px">Se muestra en el directorio público.</span>
	</p>
	<p>
		<label for="socio_email"><strong>Email de contacto</strong></label><br>
		<input type="email" id="socio_email" name="socio_email" value="<?php echo esc_attr( $email ); ?>" placeholder="contacto@empresa.cl" style="width:100%;max-width:480px">
		<br><span style="color:#666;font-size:12px">Uso interno (verificación del portal de noticias). <strong>NO se muestra</strong> en el directorio.</span>
	</p>
	<p style="color:#666;font-size:12px;border-top:1px solid #eee;padding-top:10px">
		<strong>Recuerda:</strong> el <em>Logo</em> se sube en la caja «Imagen destacada» (derecha), y el <em>Rubro</em> se marca en la caja «Rubros». En el directorio público se muestra solo <strong>logo + rubro + web</strong>.
	</p>
	<?php
}

add_action( 'save_post_socio', function ( $post_id ) {
	if ( ! isset( $_POST['asoinco_socio_nonce'] ) || ! wp_verify_nonce( $_POST['asoinco_socio_nonce'], 'asoinco_socio_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['socio_web'] ) ) {
		update_post_meta( $post_id, 'socio_web', esc_url_raw( wp_unslash( $_POST['socio_web'] ) ) );
	}
	if ( isset( $_POST['socio_email'] ) ) {
		update_post_meta( $post_id, 'socio_email', sanitize_email( wp_unslash( $_POST['socio_email'] ) ) );
	}
} );

/**
 * Sembrar rubros y socios de ejemplo (una sola vez).
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_socios_seeded' ) ) {
		return;
	}

	$rubros = array( 'Metalmecánica', 'Logística', 'Energía', 'Construcción', 'Minería', 'Ingeniería', 'Agricultura', 'Servicios industriales', 'Tecnología', 'Pesca industrial' );
	foreach ( $rubros as $r ) {
		if ( ! term_exists( $r, 'rubro' ) ) {
			wp_insert_term( $r, 'rubro' );
		}
	}

	$ejemplos = array(
		array( 'Metalmecánica del Norte SpA', 'Metalmecánica', 'https://www.metalnorte.cl' ),
		array( 'Transportes Elqui Ltda', 'Logística', 'https://www.transporteselqui.cl' ),
		array( 'Energías del Pacífico SpA', 'Energía', 'https://www.energiaspacifico.cl' ),
		array( 'Constructora Limarí SA', 'Construcción', 'https://www.constructoralimari.cl' ),
		array( 'Minera Andina Servicios SpA', 'Minería', 'https://www.mineraandina.cl' ),
		array( 'Ingeniería y Montajes Choapa SpA', 'Ingeniería', 'https://www.montajeschoapa.cl' ),
		array( 'Frío Pacífico SpA', 'Servicios industriales', 'https://www.friopacifico.cl' ),
		array( 'Agrícola Valle del Elqui Ltda', 'Agricultura', 'https://www.agricolaelqui.cl' ),
	);
	foreach ( $ejemplos as $e ) {
		$id = wp_insert_post( array(
			'post_title'  => $e[0],
			'post_type'   => 'socio',
			'post_status' => 'publish',
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			wp_set_object_terms( $id, $e[1], 'rubro' );
			update_post_meta( $id, 'socio_web', $e[2] );
			update_post_meta( $id, 'socio_email', 'contacto@' . wp_parse_url( $e[2], PHP_URL_HOST ) );
		}
	}

	update_option( 'asoinco_socios_seeded', 1 );
} );
