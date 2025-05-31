<?php
   get_header(); 
while (have_posts()) : the_post();
    ?>
    <div id="page-<?php the_ID(); ?>" class="page-content">
        <h1><?php the_title(); ?></h1>
        <div class="content">
            <?php the_content(); ?>
        </div>
    </div>
    <?php
endwhile;
get_footer(); 
?>