<?php
/**
 * ASOINCO — Configuración mínima del sitio (Pulso21).
 * Crea las páginas que el tema necesita y que no existen aún.
 *
 * @package asoinco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	// Páginas requeridas por el tema: slug => título.
	$paginas = array(
		'hazte-socio'     => 'Hazte socio',
		'publica-noticia' => 'Publica tu noticia',
	);

	foreach ( $paginas as $slug => $titulo ) {
		if ( ! get_page_by_path( $slug ) ) {
			wp_insert_post( array(
				'post_title'  => $titulo,
				'post_name'   => $slug,
				'post_status' => 'publish',
				'post_type'   => 'page',
			) );
		}
	}
} );
