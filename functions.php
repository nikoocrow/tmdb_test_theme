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