<?php get_header() ?> 
<main class="main-container">
    <?php if ( have_posts() ) : ?>
        <section class="post-list">
            <?php while ( have_posts() ) : the_post(); ?>
               <p><?php the_content() ?></p>
            <?php endwhile; ?>
        </section>
    <?php endif; ?>
</main>
<?php get_footer(); ?>


