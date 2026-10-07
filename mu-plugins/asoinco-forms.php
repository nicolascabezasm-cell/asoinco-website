<?php
/**
 * ASOINCO — Formularios: envían el mensaje directo al correo de ASOINCO.
 * Sin plugin ni panel: alguien llena el formulario → llega un correo.
 *
 * @package asoinco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Correo destino de los formularios. Cambia este valor si es otro. */
const ASOINCO_FORM_EMAIL = 'asistente@asoinco.cl';

/**
 * Formulario de CONTACTO → correo a ASOINCO.
 */
add_action( 'template_redirect', function () {
	if ( empty( $_POST['asoinco_contacto_submit'] ) ) {
		return;
	}
	if ( ! isset( $_POST['asoinco_contacto_nonce'] ) || ! wp_verify_nonce( $_POST['asoinco_contacto_nonce'], 'asoinco_contacto' ) ) {
		return;
	}
	$nombre  = sanitize_text_field( wp_unslash( $_POST['nombre'] ?? '' ) );
	$empresa = sanitize_text_field( wp_unslash( $_POST['empresa'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$fono    = sanitize_text_field( wp_unslash( $_POST['fono'] ?? '' ) );
	$asunto  = sanitize_text_field( wp_unslash( $_POST['asunto'] ?? '' ) );
	$mensaje = sanitize_textarea_field( wp_unslash( $_POST['mensaje'] ?? '' ) );

	if ( ! $nombre || ! $email || ! $mensaje ) {
		wp_safe_redirect( add_query_arg( 'enviado', 'faltan', wp_get_referer() ) );
		exit;
	}

	$cuerpo = "Nuevo mensaje de contacto desde el sitio web de ASOINCO:\n\n"
		. "Nombre: $nombre\nEmpresa: $empresa\nEmail: $email\nTeléfono: $fono\nAsunto: $asunto\n\nMensaje:\n$mensaje\n";
	$headers = array( 'Reply-To: ' . $nombre . ' <' . $email . '>' );
	wp_mail( ASOINCO_FORM_EMAIL, 'Contacto web — ' . $asunto . ' (' . $nombre . ')', $cuerpo, $headers );

	wp_safe_redirect( add_query_arg( 'enviado', 'contacto', wp_get_referer() ) );
	exit;
} );

/**
 * Formulario HAZTE SOCIO → correo a ASOINCO.
 */
add_action( 'template_redirect', function () {
	if ( empty( $_POST['asoinco_socio_submit'] ) ) {
		return;
	}
	if ( ! isset( $_POST['asoinco_socio_form_nonce'] ) || ! wp_verify_nonce( $_POST['asoinco_socio_form_nonce'], 'asoinco_socio_form' ) ) {
		return;
	}
	$empresa = sanitize_text_field( wp_unslash( $_POST['empresa'] ?? '' ) );
	$rut     = sanitize_text_field( wp_unslash( $_POST['rut'] ?? '' ) );
	$rubro   = sanitize_text_field( wp_unslash( $_POST['rubro'] ?? '' ) );
	$comuna  = sanitize_text_field( wp_unslash( $_POST['comuna'] ?? '' ) );
	$nombre  = sanitize_text_field( wp_unslash( $_POST['nombre'] ?? '' ) );
	$cargo   = sanitize_text_field( wp_unslash( $_POST['cargo'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$fono    = sanitize_text_field( wp_unslash( $_POST['fono'] ?? '' ) );
	$web     = esc_url_raw( wp_unslash( $_POST['web'] ?? '' ) );
	$tier    = sanitize_text_field( wp_unslash( $_POST['tier'] ?? '' ) );
	$mensaje = sanitize_textarea_field( wp_unslash( $_POST['mensaje'] ?? '' ) );

	if ( ! $empresa || ! $rubro || ! $comuna || ! $nombre || ! $email || ! $fono ) {
		wp_safe_redirect( add_query_arg( 'enviado', 'faltan', wp_get_referer() ) );
		exit;
	}

	$cuerpo = "Nueva solicitud de inscripción de socio (sitio web ASOINCO):\n\n"
		. "Empresa: $empresa\nRUT: $rut\nRubro: $rubro\nComuna: $comuna\n"
		. "Contacto: $nombre\nCargo: $cargo\nEmail: $email\nTeléfono: $fono\nSitio web: $web\n"
		. "Nivel de interés: $tier\n\nSobre la empresa:\n$mensaje\n";
	$headers = array( 'Reply-To: ' . $nombre . ' <' . $email . '>' );
	wp_mail( ASOINCO_FORM_EMAIL, 'Nuevo socio — ' . $empresa, $cuerpo, $headers );

	wp_safe_redirect( add_query_arg( 'enviado', 'socio', wp_get_referer() ) );
	exit;
} );
