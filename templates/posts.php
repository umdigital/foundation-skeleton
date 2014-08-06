<?php if( have_posts() ): ?>

<ol id="posts">
    <?php while (have_posts()) : the_post(); ?>

    <li class="postWrapper" id="post-<?php the_ID(); ?>">
        <h2 class="postTitle"><? if( !is_single() ): ?><a href="<?php the_permalink() ?>" rel="bookmark"><?php endif; ?><?php the_title(); ?><? if( !is_single() ): ?></a><? endif; ?></h2>
        <small><?php the_date(); ?></small>

        <div class="post"><?php the_content(__('(more...)')); ?></div>

        <hr class="noCss" />
    </li>

    <?php if( have_comments() || comments_open() ):  comments_template(); endif; // Get wp-comments.php template ?>

    <?php endwhile; ?>
</ol>

<?php else: ?>

<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>

<?php endif; ?>

<?php if( FoundationSkeleton::willPaginate() ): ?>
<?php
    global $wp_query;
    echo paginate_links(array(
        'type'    => 'list',
        'current' => get_query_var( 'page' ) ? get_query_var( 'page' ) : 1,
        'total'   => $wp_query->max_num_pages
    ));
?>
<?php endif; ?>
