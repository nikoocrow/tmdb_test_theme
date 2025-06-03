<?php
get_header();

// Redirigir si ya está logueado
if (is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}

// Obtener errores de la URL
$errors = array();
if (isset($_GET['reg_errors'])) {
    $error_string = urldecode($_GET['reg_errors']);
    $errors = explode('|', $error_string);
}
?>

<div class="login-body">
        <div class="register">
            <h2 class="register__title">Create Account</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="register__errors">
                <?php foreach ($errors as $error): ?>
                    <p class="register__error"><?php echo esc_html($error); ?></p>
                <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="post" class="register__form">
        <div class="register__field">
        <input type="text" name="username" id="username" class="register__input" placeholder="Username" required>
        </div>
        
        <div class="register__field">
        <input type="email" name="email" id="email" class="register__input" placeholder="Email"  required>
        </div>
        
        <div class="register__field">
        <input type="password" name="password" id="password" class="register__input"  placeholder="Password"  required>
        </div>
        
        <div class="register__field">
        <input type="password" name="confirm_password" id="confirm_password" class="register__input" placeholder="Confirm Password" required>
        </div>
        
        <button type="submit" name="register_submit" class="register__button">Create Account</button>
    </form>
    
    <p class="register__login-link">
        <a href="<?php echo home_url('/login'); ?>" class="register__link">Already have an account? Sign in</a>
    </p>
    </div>
</div>

<?php get_footer(); ?>