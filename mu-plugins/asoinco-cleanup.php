<?php
/**
 * ASOINCO — Limpieza y desactivación de comentarios.
 *
 * @package asoinco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Limpieza de una sola vez: comentarios spam + páginas duplicadas legacy.
 */
add_action( 'init', function () {
	if ( get_option( 'asoinco_cleanup_done' ) ) {
		return;
	}

	// Borrar todos los comentarios (son spam en el sitio importado).
	$ids = get_comments( array( 'status' => 'all', 'fields' => 'ids', 'number' => 0 ) );
	foreach ( $ids as $cid ) {
		wp_delete_comment( $cid, true );
	}

	// Enviar a la papelera las páginas duplicadas legacy (no las que usamos).
	foreach ( array( 'socios-2', 'socios2' ) as $slug ) {
		$p = get_page_by_path( $slug );
		if ( $p ) {
			wp_trash_post( $p->ID );
		}
	}

	// Que las nuevas entradas/páginas no permitan comentarios.
	update_option( 'default_comment_status', 'closed' );
	update_option( 'default_ping_status', 'closed' );

	update_option( 'asoinco_cleanup_done', 1 );
} );

/**
 * Desactivar comentarios en todo el sitio (gremio, no los necesita).
 */
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );
add_filter( 'comments_array', '__return_empty_array', 20 );

add_action( 'init', function () {
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'page', 'comments' );
} );

// Quitar "Comentarios" del menú y de la barra superior.
add_action( 'admin_menu', function () {
	remove_menu_page( 'edit-comments.php' );
}, 999 );
add_action( 'wp_before_admin_bar_render', function () {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'comments' );
} );
