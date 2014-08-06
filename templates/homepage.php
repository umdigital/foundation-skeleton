<?php if( have_posts() ): ?>

<div id="posts">
    <?php while (have_posts()) : the_post(); ?>
    <? if( has_post_thumbnail() ): ?>
    <div class="featured-image">
        <? the_post_thumbnail('featured-image'); ?>
    </div>
    <? endif; ?>

    <div class="postWrapper" id="post-<?php the_ID(); ?>">
        <h2 class="postTitle"><?php the_title(); ?></h2>
        <div class="post"><?php the_content(); ?></div>
    </div>

    <?php endwhile; ?>
</div>

<?php endif; ?>
