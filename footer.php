<div class="footer-container">
    <div class="footer-container__logo">
        <?php 
            $footer_logo = get_option('tmdbtheme_footer_logo');
            if ($footer_logo):
        ?>
    <div class="site-footer-logo">
        <img width="120" src="<?php echo esc_url($footer_logo); ?>" alt="Footer Logo" />
    </div>
    <?php endif; ?>
    </div>
    <a href="https://www.linkedin.com/in/nikocrow/" target="_blank">
        Nicolás Castro Cuervo &copy;
        <p><?php echo date('Y'); ?></p> 
    </a>
    
</div>
<!-- Popup Modal -->
<div id="custom-popup-overlay" style="display: none;">
  <div id="custom-popup">
    <button class="close-popup">×</button>
    <div class="popup-content">
        <h2>¡Contáctanos!</h2>
        <p>Nos comunicaremos contigo lo más pronto posible</p>
    </div>
    <div class="form">
        <form id="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="handle_contact_form">
            <input type="text" name="name" placeholder="Nombre" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <textarea name="message" placeholder="Mensaje" required></textarea>
            <button type="submit">Enviar</button>
        </form>
    </div>
  </div>
</div>

<?php wp_footer(); ?>
</body>