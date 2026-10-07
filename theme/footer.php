<?php
/**
 * Pie de página del tema.
 *
 * @package asoinco
 */
$theme_uri = get_template_directory_uri();
?>
<!-- 13 FOOTER -->
<footer class="footer">
  <div class="wrap">
    <div>
      <img class="flogo" src="<?php echo esc_url( $theme_uri . '/assets/logo-asoinco-white.png' ); ?>" alt="ASOINCO">
      <p>Asociación Gremial de Industriales de la Región de Coquimbo. Impulsando Empresas, Conectando el Futuro.</p>
      <div class="social">
        <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H9v3h2v7h3v-7h3l1-3h-4V9c0-.6.4-1 1-1Z"/></svg></a>
        <a href="https://instagram.com/asoinco.ag" aria-label="Instagram"><svg viewBox="0 0 24 24"><path d="M12 2c2.7 0 3 0 4.1.1 1 0 1.7.2 2.3.5.6.2 1 .5 1.5 1 .5.4.8.9 1 1.5.2.6.4 1.3.5 2.3 0 1.1.1 1.4.1 4.1s0 3-.1 4.1c0 1-.2 1.7-.5 2.3-.2.6-.5 1-1 1.5-.4.5-.9.8-1.5 1-.6.2-1.3.4-2.3.5-1.1 0-1.4.1-4.1.1s-3 0-4.1-.1c-1 0-1.7-.2-2.3-.5-.6-.2-1-.5-1.5-1-.5-.4-.8-.9-1-1.5-.2-.6-.4-1.3-.5-2.3C2 15 2 14.7 2 12s0-3 .1-4.1c0-1 .2-1.7.5-2.3.2-.6.5-1 1-1.5.4-.5.9-.8 1.5-1 .6-.2 1.3-.4 2.3-.5C8.5 2 8.8 2 11.5 2H12Zm0 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm5.3-.4a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4Z"/></svg></a>
        <a href="#" aria-label="X"><svg viewBox="0 0 24 24"><path d="M18 2h3l-7 8 8 12h-6l-5-7-5 7H3l8-9L3 2h6l4 6 5-6Z"/></svg></a>
      </div>
    </div>
    <div class="col">
      <h5>Navegación</h5>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a>
      <a href="<?php echo esc_url( home_url( '/quienes-somos/' ) ); ?>">Quiénes somos</a>
      <a href="<?php echo esc_url( home_url( '/directorio/' ) ); ?>">Socios</a>
      <a href="<?php echo esc_url( home_url( '/convenios/' ) ); ?>">Convenios</a>
      <a href="<?php echo esc_url( home_url( '/noticias/' ) ); ?>">Noticias</a>
      <a href="<?php echo esc_url( home_url( '/expo/' ) ); ?>">Expo ASOINCO</a>
      <a href="<?php echo esc_url( home_url( '/hazte-socio/' ) ); ?>">Hazte socio</a>
    </div>
    <div class="col contact">
      <h5>Contacto</h5>
      <div><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg> Coquimbo, Región de Coquimbo</div>
      <div><svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg> asistente@asoinco.cl</div>
      <div><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/></svg> +56 9 7979 8887</div>
    </div>
    <div class="col">
      <h5>@asoinco.ag</h5>
      <div class="fig" id="footIg"></div>
      <a class="iglink" href="https://instagram.com/asoinco.ag">Seguir en Instagram →</a>
    </div>
  </div>
  <div class="copyright">© <?php echo esc_html( date( 'Y' ) ); ?> ASOINCO · Asociación Gremial de Industriales de la Región de Coquimbo · Todos los derechos reservados · Powered by <a href="https://pulso21.cl" target="_blank" rel="noopener" style="color:inherit;text-decoration:none;">Pulso21</a></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
