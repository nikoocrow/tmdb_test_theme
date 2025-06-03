<?php
get_header();

// Redirigir si ya está logueado
if (is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}

// Obtener mensaje de error de la URL
$error_message = '';
if (isset($_GET['login_error'])) {
    $error_message = urldecode($_GET['login_error']);
}
?>

<div class="login-body">
<div class="login">
  <h2 class="login__title">Sign In</h2>
  <?php if ($error_message): ?>
    <div class="login__error"><?php echo esc_html($error_message); ?></div>
  <?php endif; ?>
  
  <form method="post" class="login__form">
    <div class="login__field">
      <input type="text" name="username" id="username" class="login__input" placeholder="User Name or Email" required>
    </div>
    
    <div class="login__field">
      <input type="password" name="password" id="password" class="login__input" placeholder="Password" required>
    </div>
    
    <div class="login__field login__field--checkbox">
      <label class="login__checkbox-label">
        <input type="checkbox" name="remember" class="login__checkbox"> Remember me
      </label>
    </div>
    
    <button type="submit" name="login_submit" class="login__button">Login</button>
  </form>
  
  <p class="login__register-link">
    <a href="<?php echo home_url('/registration'); ?>" class="login__link">Don't have an account? Sign up</a>
  </p>
</div>
</div>

<?php get_footer(); ?>