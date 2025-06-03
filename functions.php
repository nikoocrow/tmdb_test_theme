<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function TMDBTheme() {
   
    wp_enqueue_style( 'normalize', get_template_directory_uri() . '/css/normalize.css', array(), '1.0', 'all' );

    //Gulp
    wp_enqueue_style('TMDBTheme-style', get_template_directory_uri() . '/assets/dist/css/main.min.css');
    wp_enqueue_style('aosScrollAnimationCSS', 'https://unpkg.com/aos@2.3.1/dist/aos.css', array(), '3.0.0-beta.6');


    wp_enqueue_script('TMDBTheme-scripts', get_template_directory_uri() . '/assets/dist/js/main.min.js', array(), false, true);
    //AOS animations
    wp_enqueue_script('aosScrollAnimationJS', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), '3.0.0-beta.6', true);
    wp_enqueue_script('aosInit', get_template_directory_uri() . '/assets/dist/js/aosSrollAnimation.js', array(), true);


   

    wp_enqueue_media();
   // wp_enqueue_script('tmdbtheme-media-uploader', get_template_directory_uri() . '/assets/dist/js/main.min.js', array('jquery'), null, true);

    



}
add_action( 'wp_enqueue_scripts', 'TMDBTheme' );
add_theme_support( 'post-thumbnails' );
function TMDBTheme_menus() {
    register_nav_menus( array(
        'menu_principal' => __( 'Menú Principal', 'winbuTheme' ),
        'menu_footer'    => __( 'Menú del Footer', 'winbuTheme' ),
    ));
}
add_action( 'after_setup_theme', 'TMDBTheme_menus' );
remove_action( 'wp_head', 'wp_generator' );
add_theme_support( 'title-tag' );
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );




require get_template_directory() . '/theme-options.php';
add_action('admin_enqueue_scripts', function($hook) {
    wp_enqueue_media(); 
});


/**
 *  LOGIN AND REGITER FUNCTIONS
 */


 // Procesar formularios de login y registro
function process_custom_forms() {
    // Solo procesar en las páginas correctas
    if (!is_admin() && is_main_query()) {
        global $post;
        
        // Procesar login
        if (isset($_POST['login_submit']) && $post && $post->post_name == 'login') {
            if (is_user_logged_in()) {
                wp_redirect(home_url());
                exit;
            }
            
            $username = sanitize_user($_POST['username']);
            $password = $_POST['password'];
            
            $creds = array(
                'user_login'    => $username,
                'user_password' => $password,
                'remember'      => isset($_POST['remember'])
            );
            
            $user = wp_signon($creds, false);
            
            if (is_wp_error($user)) {
                wp_redirect(add_query_arg('login_error', urlencode($user->get_error_message()), get_permalink()));
                exit;
            } else {
                wp_redirect(home_url());
                exit;
            }
        }
        
        // Procesar registro
       if (isset($_POST['register_submit']) && $post && $post->post_name == 'registration') {
            if (is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
            }
            $username = sanitize_user($_POST['username']);
            $email = sanitize_email($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            // Validations
            $errors = array();
            if (empty($username)) {
            $errors[] = 'Username is required.';
            } elseif (username_exists($username)) {
            $errors[] = 'This username already exists.';
            }
            if (empty($email)) {
            $errors[] = 'Email is required.';
            } elseif (!is_email($email)) {
            $errors[] = 'Invalid email format.';
            } elseif (email_exists($email)) {
            $errors[] = 'This email is already registered.';
            }
            if (empty($password)) {
            $errors[] = 'Password is required.';
            } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
            }
            if ($password !== $confirm_password) {
            $errors[] = 'Passwords do not match.';
            }
            // If there are errors, redirect with errors
            if (!empty($errors)) {
            $error_string = implode('|', $errors);
            wp_redirect(add_query_arg('reg_errors', urlencode($error_string), get_permalink()));
            exit;
            }
            
            // Si no hay errores, crear el usuario
            $user_id = wp_create_user($username, $password, $email);
            
            if (!is_wp_error($user_id)) {
                // Login automático después del registro
                $creds = array(
                    'user_login'    => $username,
                    'user_password' => $password,
                    'remember'      => true
                );
                
                wp_signon($creds, false);
                wp_redirect(home_url());
                exit;
            } else {
                wp_redirect(add_query_arg('reg_errors', urlencode('Error while creating the account: ' . $user_id->get_error_message()), get_permalink()));
                exit;
            }
        }
    }
}
add_action('template_redirect', 'process_custom_forms');

// Redirigir después del logout
function custom_logout_redirect() {
    wp_redirect(home_url('/login'));
    exit;
}
add_action('wp_logout', 'custom_logout_redirect');

// Deshabilitar la barra de admin para usuarios no administradores
function disable_admin_bar_for_users() {
    if (!current_user_can('administrator')) {
        show_admin_bar(false);
    }
}
add_action('wp_loaded', 'disable_admin_bar_for_users');



//WISHLIST MENU ITEM


add_filter('wp_nav_menu_items', 'tmdb_add_wishlist_to_menu', 10, 2);
function tmdb_add_wishlist_to_menu($items, $args) {
    // Solo mostrar para usuarios logueados
    if (!is_user_logged_in()) return $items;
    
    global $wpdb;
    $table = $wpdb->prefix . 'tmdb_wishlist';
    $user_id = get_current_user_id();
    
    // Contar películas en wishlist del usuario
    $wishlist_count = 0;
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table'") == $table;
    if ($table_exists) {
        $wishlist_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE user_id = %d", 
            $user_id
        ));
    }
    
    // Crear badge con contador solo si hay películas
    $count_badge = $wishlist_count > 0 ? ' <span class="wishlist-menu-count">' . $wishlist_count . '</span>' : '';
    
    // HTML del enlace de wishlist
    $wishlist_item = '<li class="menu-item menu-item-type-custom wishlist-menu-item">
        <a href="' . home_url('/my-wishlist') . '" class="wishlist-link">
             My Wishlist' . $count_badge . '
        </a>
    </li>';
    
    // Agregar al final del menú
    $items .= $wishlist_item;
    
    return $items;
}

// Estilos CSS para el enlace de wishlist
add_action('wp_head', 'tmdb_wishlist_menu_styles');
function tmdb_wishlist_menu_styles() {
    if (!is_user_logged_in()) return;
    ?>
    <style>
    /* Estilos para el enlace de wishlist en el menú */
    .wishlist-menu-item {
        position: relative;
    }
    
    .wishlist-link {
        position: relative;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .wishlist-icon {
        font-size: 16px;
        margin-right: 5px;
        transition: transform 0.3s ease;
    }
    
    .wishlist-link:hover .wishlist-icon {
        transform: scale(1.2);
    }
    
    .wishlist-menu-count {
        background: #DB4886;;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: bold;
        margin-left: 5px;
        min-width: 18px;
        height: 18px;
        text-align: center;
        display: inline-block;
        line-height: 14px;
        vertical-align: top;
        animation: pulse 2s infinite;
    }
    
    .wishlist-menu-item:hover .wishlist-menu-count {
        background-color:rgb(226, 104, 155);
    }
    
    /* Animación sutil para el contador */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    /* Responsive - ajustes para móviles */
    @media (max-width: 768px) {
        .wishlist-link {
            font-size: 14px;
        }
        .wishlist-menu-count {
            font-size: 10px;
            padding: 1px 4px;
            min-width: 16px;
            height: 16px;
            line-height: 14px;
        }
    }
    </style>
    <?php
}

// AJAX para actualizar contador del menú en tiempo real
add_action('wp_ajax_update_menu_wishlist_count', 'tmdb_update_menu_wishlist_count');
function tmdb_update_menu_wishlist_count() {
    if (!is_user_logged_in()) {
        wp_die(json_encode(['count' => 0]));
    }
    
    global $wpdb;
    $table = $wpdb->prefix . 'tmdb_wishlist';
    $user_id = get_current_user_id();
    
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE user_id = %d", 
        $user_id
    ));
    
    wp_die(json_encode(['count' => intval($count)]));
}

// JavaScript para actualizar contador automáticamente
add_action('wp_footer', 'tmdb_wishlist_menu_script');
function tmdb_wishlist_menu_script() {
    if (!is_user_logged_in()) return;
    ?>
    <script>
    // Actualizar contador del menú cuando se modifica la wishlist
    document.addEventListener('DOMContentLoaded', function() {
        // Interceptar las llamadas AJAX de wishlist
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            return originalFetch.apply(this, args)
                .then(response => {
                    // Si es una llamada AJAX de wishlist, actualizar contador
                    if (args[0] && args[0].includes('admin-ajax.php')) {
                        response.clone().text().then(text => {
                            try {
                                const data = JSON.parse(text);
                                if (data.success && (data.action === 'added' || data.action === 'removed')) {
                                    updateMenuWishlistCounter();
                                }
                            } catch(e) {
                                // Ignorar errores de parsing
                            }
                        });
                    }
                    return response;
                });
        };
    });
    
    function updateMenuWishlistCounter() {
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=update_menu_wishlist_count'
        })
        .then(r => r.json())
        .then(data => {
            const counter = document.querySelector('.wishlist-menu-count');
            const link = document.querySelector('.wishlist-link');
            
            if (data.count > 0) {
                if (counter) {
                    // Actualizar contador existente
                    counter.textContent = data.count;
                } else if (link) {
                    // Crear nuevo contador
                    link.innerHTML = link.innerHTML + ' <span class="wishlist-menu-count">' + data.count + '</span>';
                }
            } else if (counter) {
                // Remover contador si es 0
                counter.remove();
            }
        })
        .catch(error => {
            console.log('Error updating wishlist counter:', error);
        });
    }
    </script>
    <?php
}