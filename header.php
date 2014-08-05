<?php header( 'X-UA-Compatible: IE=Edge' ); ?>
<!doctype html>
<!--[if lte IE 8]><html class="no-js lte-ie8" lang="en"><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><? wp_title( '|', true, 'right' ); ?> <?php echo get_bloginfo('name'); ?></title>

    <? wp_head(); ?>
</head>
<body <? body_class(); ?>>
<div id="wrapper">
    <? if( has_nav_menu( 'top-menu', 'foundation_skeleton' ) ): ?>
    <div id="section-top-menu">
        <div class="row">
            <div class="<?php echo FoundationSkeleton::getColumns( 'menus:top_menu' );?> columns fskelMenu">
                <?php wp_nav_menu(array(
                    'container'      => '',
                    'fallback_cb'    => false,
                    'menu_class'     => 'top-menu clearfix',
                    'theme_location' => 'top-menu',
                    'depth'          => 1
                ));?>
            </div>
        </div>
    </div>
    <? endif; ?>

    <div id="section-header">
        <?php if( ($class = FoundationSkeleton::areaEnabled( 'header:widget_prefix' )) !== false ): ?>
        <div id="zone-header-prefix" class="<?php echo $class;?>">
            <div class="row">
                <div id="widget-header-prefix" class="<?php echo FoundationSkeleton::getColumns( 'header:widget_prefix' );?> columns">
                    <?php dynamic_sidebar( 'header-widget_prefix' ); ?>
                </div>
            </div>
        </div>
        <?php endif; // widget prefix ?>

        <div id="zone-header-branding">
            <div class="row">
                <?php if( ($class = FoundationSkeleton::areaEnabled( 'header:widget_first' )) !== false ): ?>
                <div id="widget-header-first" class="<?php echo FoundationSkeleton::getColumns( 'header:widget_first' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'header-widget_first' ); ?>
                </div>
                <?php endif; // widget first ?>

                <div class="logo-title <?php echo FoundationSkeleton::getColumns( 'header:branding' );?> columns">
                     <? if( get_header_image() ): ?>
                    <h1 class="logo"><a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr( get_bloginfo('name', 'display') ); ?>" rel="home">
                        <img src="<?php header_image(); ?>" title="<?php echo esc_attr( get_bloginfo('name', 'display') ); ?>" />
                    </a></h1>
                    <? else: ?>
                    <h1><a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr( get_bloginfo('name', 'display') ); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                    <? endif; ?>
                </div>

                <?php if( ($class = FoundationSkeleton::areaEnabled( 'header:widget_second' )) !== false ): ?>
                <div id="widget-header-second" class="<?php echo FoundationSkeleton::getColumns( 'header:widget_second' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'header-widget_second' ); ?>
                </div>
                <?php endif; // widget second ?>
            </div>
        </div>

        <?php if( ($class = FoundationSkeleton::areaEnabled( 'header:widget_prefix' )) !== false ): ?>
        <div id="zone-header-postfix" class="<?php echo $class;?>">
            <div class="row">
                <div id="widget-header-postfix" class="<?php echo FoundationSkeleton::getColumns( 'header:widget_postfix' );?> columns">
                    <?php dynamic_sidebar( 'header-widget_postfix' ); ?>
                </div>
            </div>
        </div>
        <?php endif; // widget postifx ?>
    </div><!-- #SECTION-HEADER -->

    <?php if( has_nav_menu( 'header-menu', 'foundation_skeleton' ) ): ?>
    <div id="section-header-menu">
        <div class="row">
            <div class="<?php echo FoundationSkeleton::getColumns( 'menus:header_menu' );?> columns fskelMenu">
                <a href="javascript:void();" class="hamburger-header"><h3><i class="fa fa-bars"></i>Menu</h3></a>
                 <?php wp_nav_menu(array(
                    'container'      => '',
                    'fallback_cb'    => false,
                    'menu_class'     => 'header-menu clearfix',
                    'theme_location' => 'header-menu',
                    'depth'          => 1,
                    'link_before'    => '<span>',
                    'link_after'     => '</span>'
                ));?> 
            </div>
        </div>
    </div>
    <? endif; ?>

    <div id="section-content">
        <?php if( ($class = FoundationSkeleton::areaEnabled( 'content:widget_prefix1,content:widget_prefix2,content:widget_prefix3,content:widget_prefix4' )) !== false ): ?>
        <div id="zone-content-prefix" class="<?php echo $class;?>">
            <div class="row">
                <?php if( ($class = FoundationSkeleton::areaEnabled( 'content:widget_prefix1' )) !== false ): ?>
                <div id="widget-content-prefix1" class="<?php echo FoundationSkeleton::getColumns( 'content:widget_prefix1' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'content-widget_prefix1' ); ?>
                </div>
                <?php endif;?>
                <?php if( ($class = FoundationSkeleton::areaEnabled( 'content:widget_prefix2' )) !== false ): ?>
                <div id="widget-content-prefix2" class="<?php echo FoundationSkeleton::getColumns( 'content:widget_prefix2' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'content-widget_prefix2' ); ?>
                </div>
                <?php endif;?>
                <?php if( ($class = FoundationSkeleton::areaEnabled( 'content:widget_prefix3' )) !== false ): ?>
                <div id="widget-content-prefix3" class="<?php echo FoundationSkeleton::getColumns( 'content:widget_prefix3' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'content-widget_prefix3' ); ?>
                </div>
                <?php endif;?>
                <?php if( ($class = FoundationSkeleton::areaEnabled( 'content:widget_prefix4' )) !== false ): ?>
                <div id="widget-content-prefix4" class="<?php echo FoundationSkeleton::getColumns( 'content:widget_prefix4' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'content-widget_prefix4' ); ?>
                </div>
                <?php endif;?>
            </div>
        </div>
        <?php endif; // widget_prefix* enabled ?>

        <div id="zone-content">
            <div class="row">
                <?php if( ($class = FoundationSkeleton::areaEnabled( 'content:widget_first' )) !== false ): ?>
                <div id="widget-content-first" class="<?php echo FoundationSkeleton::getColumns( 'content:widget_first' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'content-widget_first' ); ?>
                </div>
                <?php endif; // widget first ?>

                <div id="content-main" class="<?php echo FoundationSkeleton::getContentColumns();?> columns">
                    <?php if( ($class = FoundationSkeleton::areaEnabled( 'content:widget_top' )) !== false ): ?>
                    <div id="widget-content-top" class="<?php echo $class;?>">
                        <?php dynamic_sidebar( 'content-widget_top' ); ?>
                    </div>
                    <?php endif; // widget first ?>

                    <div id="content">
