<?php
/**
 * ASOINCO — Tema a medida (Pulso21)
 * v0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Configuración del tema.
 */
function asoinco_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 50, 'flex-width' => true ) );

	register_nav_menus( array(
		'primary' => __( 'Menú principal', 'asoinco' ),
		'footer'  => __( 'Menú del pie', 'asoinco' ),
	) );
}
add_action( 'after_setup_theme', 'asoinco_setup' );

/**
 * Estilos y scripts.
 */
function asoinco_assets() {
	$ver = wp_get_theme()->get( 'Version' );

	// Fuente Inter (Google Fonts).
	wp_enqueue_style( 'asoinco-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap', array(), null );

	// Estilos compartidos (base + componentes de subpáginas).
	wp_enqueue_style( 'asoinco-shared', get_template_directory_uri() . '/assets/site.css', array( 'asoinco-fonts' ), $ver );

	// Hoja de estilos principal (style.css del tema: home + cabecera del tema).
	wp_enqueue_style( 'asoinco-style', get_stylesheet_uri(), array( 'asoinco-shared' ), $ver );

	// Script principal (slider, menú, carruseles).
	wp_enqueue_script( 'asoinco-main', get_template_directory_uri() . '/assets/main.js', array(), $ver, true );

	// Ruta a los assets del tema, disponible para el JS.
	wp_add_inline_script(
		'asoinco-main',
		'window.ASOINCO_ASSETS="' . esc_js( get_template_directory_uri() . '/assets/' ) . '";',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'asoinco_assets' );

/**
 * Favicon de marca (monograma ASOINCO en SVG, sin archivo externo).
 * Se muestra en el sitio, el admin y el login.
 */
function asoinco_favicon() {
	$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">'
		. '<rect width="64" height="64" rx="14" fill="#1B3A6B"/>'
		. '<text x="32" y="45" font-family="Arial,Helvetica,sans-serif" font-size="42" font-weight="800" text-anchor="middle" fill="#ffffff">A</text>'
		. '<rect x="19" y="50" width="26" height="5" rx="2.5" fill="#5BAD5E"/></svg>';
	$uri = 'data:image/svg+xml;base64,' . base64_encode( $svg );
	echo '<link rel="icon" type="image/svg+xml" href="' . esc_attr( $uri ) . '">' . "\n";
}
add_action( 'wp_head', 'asoinco_favicon' );
add_action( 'admin_head', 'asoinco_favicon' );
add_action( 'login_head', 'asoinco_favicon' );
