<?php
/**
 * ASOINCO — Noticias (2 canales).
 * Canal 1: periodista (rol acotado, publica desde el panel).
 * Canal 2: portal de socios (formulario web sin cuentas → pendiente → moderación).
 *
 * @package asoinco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rol "Periodista": solo gestiona noticias (entradas), sin tocar diseño ni ajustes.
 */
add_action( 'init', function () {
	if ( ! get_role( 'periodista' ) ) {
		add_role( 'periodista', 'Periodista', array(
			'read'                   => true,
			'upload_files'           => true,
			'edit_posts'             => true,
			'edit_published_posts'   => true,
			'publish_posts'          => true,
			'delete_posts'           => true,
			'delete_published_posts' => true,
			'edit_others_posts'      => true,  // moderar noticias de socios.
			'delete_others_posts'    => true,
			'manage_categories'      => true,
		) );
	}
} );

/**
 * Sembrar categorías y noticias de ejemplo (una sola vez).
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_noticias_seeded' ) ) {
		return;
	}

	$cats = array( 'Institucional', 'Gremio', 'Alianzas', 'Expo', 'Socios' );
	foreach ( $cats as $c ) {
		if ( ! term_exists( $c, 'category' ) ) {
			wp_insert_term( $c, 'category' );
		}
	}

	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$base = 'https://www.asoinco.cl/wp-content/uploads/2025/05/';
	$posts = array(
		array(
			'title'   => 'Gobierno potencia el trabajo asociativo con la Asociación Gremial de Industriales de Coquimbo',
			'cat'     => 'Institucional',
			'img'     => $base . 'EXPO-ASOINCO-2024-70-scaled.jpg',
			'content' => 'ASOINCO y autoridades regionales refuerzan la colaboración público-privada para impulsar el desarrollo industrial de la Región de Coquimbo.',
			'empresa' => '',
		),
		array(
			'title'   => 'ASOINCO lanza el Plan de Modernización 2030 con 7 comisiones de trabajo',
			'cat'     => 'Gremio',
			'img'     => $base . 'EXPO-ASOINCO-2024-44-scaled.jpg',
			'content' => 'El gremio presenta su hoja de ruta para transformarse en una plataforma moderna al servicio de sus socios.',
			'empresa' => '',
		),
		array(
			'title'   => 'ASOINCO integra la Mesa de Gobernanza del Programa Transforma Hídrico Coquimbo',
			'cat'     => 'Alianzas',
			'img'     => $base . 'EXPO-ASOINCO-2024-22-scaled.jpg',
			'content' => 'La asociación se suma a la gobernanza del programa que busca soluciones sostenibles para el recurso hídrico regional.',
			'empresa' => '',
		),
		array(
			'title'   => 'Metalmecánica del Norte inaugura nueva planta de fabricación en Coquimbo',
			'cat'     => 'Socios',
			'img'     => $base . 'EXPO-ASOINCO-2024-84-scaled.jpg',
			'content' => 'La empresa socia amplía su capacidad productiva con una nueva planta que generará empleos en la región.',
			'empresa' => 'Metalmecánica del Norte SpA',
		),
	);

	foreach ( $posts as $p ) {
		$post_id = wp_insert_post( array(
			'post_title'   => $p['title'],
			'post_content' => $p['content'],
			'post_status'  => 'publish',
			'post_type'    => 'post',
		) );
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			$term = get_term_by( 'name', $p['cat'], 'category' );
			if ( $term ) {
				wp_set_post_categories( $post_id, array( (int) $term->term_id ) );
			}
			if ( $p['empresa'] ) {
				update_post_meta( $post_id, 'noticia_socio_empresa', $p['empresa'] );
			}
			$att = media_sideload_image( $p['img'], $post_id, $p['title'], 'id' );
			if ( ! is_wp_error( $att ) ) {
				set_post_thumbnail( $post_id, $att );
			}
		}
	}

	update_option( 'asoinco_noticias_seeded', 1 );
} );

/**
 * Portal de socios: procesar el formulario "Publica tu noticia" (sin cuentas).
 * Crea la entrada como PENDIENTE (moderación de la periodista).
 */
add_action( 'template_redirect', function () {
	if ( empty( $_POST['asoinco_noticia_submit'] ) ) {
		return;
	}
	if ( ! isset( $_POST['asoinco_noticia_nonce'] ) || ! wp_verify_nonce( $_POST['asoinco_noticia_nonce'], 'asoinco_noticia_form' ) ) {
		return;
	}

	$empresa   = sanitize_text_field( wp_unslash( $_POST['empresa'] ?? '' ) );
	$email     = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$titulo    = sanitize_text_field( wp_unslash( $_POST['titulo'] ?? '' ) );
	$contenido = sanitize_textarea_field( wp_unslash( $_POST['contenido'] ?? '' ) );
	$tipo      = sanitize_text_field( wp_unslash( $_POST['tipo'] ?? 'Noticia' ) );

	if ( ! $empresa || ! $titulo || ! $contenido ) {
		wp_safe_redirect( add_query_arg( 'noticia', 'faltan', wp_get_referer() ) );
		exit;
	}

	$post_id = wp_insert_post( array(
		'post_title'   => $titulo,
		'post_content' => $contenido,
		'post_status'  => 'pending', // moderación.
		'post_type'    => 'post',
	) );

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		wp_safe_redirect( add_query_arg( 'noticia', 'error', wp_get_referer() ) );
		exit;
	}

	// Categoría "Socios".
	$term = get_term_by( 'name', 'Socios', 'category' );
	if ( $term ) {
		wp_set_post_categories( $post_id, array( (int) $term->term_id ) );
	}

	update_post_meta( $post_id, 'noticia_socio_empresa', $empresa );
	update_post_meta( $post_id, 'noticia_socio_email', $email );
	update_post_meta( $post_id, 'noticia_tipo', $tipo );

	// Verificación ligera: ¿el email coincide con un socio del directorio?
	if ( $email ) {
		$match = get_posts( array(
			'post_type'   => 'socio',
			'meta_key'    => 'socio_email',
			'meta_value'  => $email,
			'numberposts' => 1,
			'fields'      => 'ids',
		) );
		if ( $match ) {
			update_post_meta( $post_id, 'noticia_socio_verificado', 1 );
		}
	}

	// Imagen opcional.
	if ( ! empty( $_FILES['imagen']['name'] ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$att = media_handle_upload( 'imagen', $post_id );
		if ( ! is_wp_error( $att ) ) {
			set_post_thumbnail( $post_id, $att );
		}
	}

	wp_safe_redirect( add_query_arg( 'noticia', 'ok', wp_get_referer() ) );
	exit;
} );
