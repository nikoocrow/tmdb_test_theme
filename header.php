<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title();?></title>
    <?php wp_head(); ?>
</head>
<body>

<header class="header">
    <div class="header-container">
    <div class="header-container__logo">
       
    <?php
                $header_logo = get_option('tmdbtheme_header_logo');
                if ($header_logo):
                ?>
                    <div class="site-header-logo">
                        <a href="<?php echo home_url(); ?>">
                            <img src="<?php echo esc_url($header_logo); ?>" alt="Logo" />
                        </a>
                    </div>
    <?php endif; ?>


    </div>
    <div class="header-container__navbar">
        <nav class="header-container__main-nav">
        <?php
            wp_nav_menu( array(
                'theme_location' => 'menu_principal',
                'container'      => false,
                'menu_class'     => 'nav-menu',
         ) );
        ?>
       </nav>
    </div>
    </div>
</header>
    