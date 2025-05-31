<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php bloginfo('name'); ?></title>
    <title><?php wp_title(); ?></title>
     <?php wp_head(); ?>
     <?php get_header(); ?>

</head>
    <body <?php body_class(); ?>>
       
       

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <h2><?php the_title(); ?></h2>
            <div><?php the_content(); ?></div>
        <?php endwhile; endif; ?>

        <?php wp_footer(); ?>
         <?php get_footer(); ?>
    </body>
   
</html>
