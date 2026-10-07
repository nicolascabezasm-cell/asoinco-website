<?php
/**
 * ASOINCO — Panel de administración más amigable.
 * Vuelve al editor clásico (tipo Word) y limpia la pantalla de edición.
 *
 * @package asoinco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Usar el editor clásico en vez de Gutenberg (bloques).
 */
add_filter( 'use_block_editor_for_post', '__return_false', 100 );
add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );

/**
 * Quitar cajas que estorban en la pantalla de edición de entradas.
 */
add_action( 'admin_menu', function () {
	$screens = array( 'post', 'page' );
	foreach ( $screens as $s ) {
		remove_meta_box( 'postcustom', $s, 'normal' );      // Campos personalizados.
		remove_meta_box( 'trackbacksdiv', $s, 'normal' );   // Trackbacks.
		remove_meta_box( 'commentstatusdiv', $s, 'normal' );// Ajustes de comentarios.
		remove_meta_box( 'commentsdiv', $s, 'normal' );     // Comentarios.
		remove_meta_box( 'postexcerpt', $s, 'normal' );     // Extracto.
		remove_meta_box( 'slugdiv', $s, 'normal' );         // Slug.
		remove_meta_box( 'authordiv', $s, 'normal' );       // Autor.
	}
} );

/**
 * Mensaje de ayuda arriba del editor de entradas (para la periodista).
 */
add_action( 'edit_form_top', function ( $post ) {
	if ( 'post' !== $post->post_type ) {
		return;
	}
	echo '<div style="background:#eef6ff;border:1px solid #cfe2ff;border-radius:8px;padding:12px 16px;margin:10px 0;font-size:13px;color:#1B3A6B">'
		. '<strong>Cómo publicar una noticia:</strong> 1) Escribe el <strong>título</strong> arriba. 2) Redacta el texto en el editor. '
		. '3) A la derecha, marca la <strong>Categoría</strong> y sube la <strong>Imagen destacada</strong>. 4) Clic en <strong>Publicar</strong>.'
		. '</div>';
} );

/**
 * Simplificar el menú lateral para roles que no son administradores (ej. Periodista).
 */
add_action( 'admin_menu', function () {
	if ( current_user_can( 'manage_options' ) ) {
		return; // Los administradores ven todo.
	}
	remove_menu_page( 'edit-comments.php' ); // Comentarios.
	remove_menu_page( 'tools.php' );         // Herramientas.
}, 999 );

/**
 * Panel de bienvenida con accesos rápidos (widget del escritorio).
 */
add_action( 'wp_dashboard_setup', function () {
	wp_add_dashboard_widget( 'asoinco_bienvenida', '👋 Panel ASOINCO — accesos rápidos', function () {
		$expo    = get_page_by_path( 'expo' );
		$expo_url = $expo ? admin_url( 'post.php?post=' . $expo->ID . '&action=edit' ) : admin_url();
		$links = array(
			array( '📰 Publicar una noticia', admin_url( 'post-new.php' ) ),
			array( '🏢 Agregar un socio', admin_url( 'post-new.php?post_type=socio' ) ),
			array( '🤝 Agregar un convenio', admin_url( 'post-new.php?post_type=convenio' ) ),
			array( '🎪 Editar la Expo', $expo_url ),
			array( '⭐ Agregar auspiciador', admin_url( 'post-new.php?post_type=auspiciador' ) ),
			array( '📩 Ver solicitudes de stand', admin_url( 'edit.php?post_type=solicitud_stand' ) ),
		);
		echo '<p style="margin-top:0">Desde aquí administras el contenido del sitio de ASOINCO:</p>';
		echo '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">';
		foreach ( $links as $l ) {
			echo '<a href="' . esc_url( $l[1] ) . '" class="button" style="text-align:left;height:auto;padding:8px 12px;white-space:normal">' . esc_html( $l[0] ) . '</a>';
		}
		echo '</div>';
		echo '<p style="margin-bottom:0;font-size:12px;color:#666">¿Dudas? Escribe a Pulso21 y te ayudamos.</p>';
	} );
} );

/**
 * Rol "Equipo ASOINCO": gestiona contenido (socios, noticias, convenios, expo)
 * pero NO toca diseño, plugins, ajustes ni usuarios.
 */
add_action( 'init', function () {
	if ( 'v2' === get_option( 'asoinco_roles_version' ) ) {
		return;
	}
	remove_role( 'equipo_asoinco' );
	add_role( 'equipo_asoinco', 'Equipo ASOINCO', array(
		'read'                   => true,
		'upload_files'           => true,
		'edit_posts'             => true,
		'edit_published_posts'   => true,
		'publish_posts'          => true,
		'delete_posts'           => true,
		'delete_published_posts' => true,
		'edit_others_posts'      => true,
		'edit_pages'             => true,
		'edit_published_pages'   => true,
		'edit_others_pages'      => true,
		'manage_categories'      => true,
	) );
	update_option( 'asoinco_roles_version', 'v2' );
} );
