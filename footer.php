
                    </div> <!-- #CONTENT -->

                    <?php if( ($class = FoundationSkeleton::areaEnabled( 'content:widget_bottom' )) !== false ): ?>
                    <div id="widget-content-bottom" class="<?php echo $class;?>">
                        <?php dynamic_sidebar( 'content-widget_bottom' ); ?>
                    </div>
                    <?php endif; // widget first ?>
                </div>

                <?php if( ($class = FoundationSkeleton::areaEnabled( 'content:widget_second' )) !== false ): ?>
                <div id="widget-content-second" class="<?php echo FoundationSkeleton::getColumns( 'content:widget_second' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'content-widget_second' ); ?>
                </div>
                <?php endif; // widget first ?>
            </div>
        </div>
    </div><!-- SECTION-CONTENT -->

    <div id="section-footer">
        <?php if( ($class = FoundationSkeleton::areaEnabled( 'footer:widget_prefix1,footer:widget_prefix2,footer:widget_prefix3,footer:widget_prefix4' )) !== false ): ?>
        <div id="zone-footer-prefix" class="<?php echo $class;?>">
            <div class="row">
                <?php if( ($class = FoundationSkeleton::areaEnabled( 'footer:widget_prefix1' )) !== false ): ?>
                <div id="widget-footer-prefix1" class="<?php echo FoundationSkeleton::getColumns( 'footer:widget_prefix1' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'footer-widget_prefix1' ); ?>
                </div>
                <?php endif;?>
                <?php if( ($class = FoundationSkeleton::areaEnabled( 'footer:widget_prefix2' )) !== false ): ?>
                <div id="widget-footer-prefix2" class="<?php echo FoundationSkeleton::getColumns( 'footer:widget_prefix2' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'footer-widget_prefix2' ); ?>
                </div>
                <?php endif;?>
                <?php if( ($class = FoundationSkeleton::areaEnabled( 'footer:widget_prefix3' )) !== false ): ?>
                <div id="widget-footer-prefix3" class="<?php echo FoundationSkeleton::getColumns( 'footer:widget_prefix3' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'footer-widget_prefix3' ); ?>
                </div>
                <?php endif;?>
                <?php if( ($class = FoundationSkeleton::areaEnabled( 'footer:widget_prefix4' )) !== false ): ?>
                <div id="widget-footer-prefix4" class="<?php echo FoundationSkeleton::getColumns( 'footer:widget_prefix4' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'footer-widget_prefix4' ); ?>
                </div>
                <?php endif;?>
            </div>
        </div>
        <?php endif; // widget_prefix* enabled ?>

        <div id="zone-footer">
            <div class="row">
                <?php if( ($class = FoundationSkeleton::areaEnabled( 'footer:widget_top' )) !== false ): ?>
                <div id="widget-footer-top" class="<?php echo FoundationSkeleton::getColumns( 'footer:widget_top' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'footer-widget_top' ); ?>
                </div>
                <?php endif;?>

                <div id="footer-content" class="<?php echo FoundationSkeleton::getColumns( 'footer:content' );?> columns">
                    <? get_template_part( 'templates/footer' ); ?>
                </div>

                <?php if( ($class = FoundationSkeleton::areaEnabled( 'footer:widget_bottom' )) !== false ): ?>
                <div id="widget-footer-bottom" class="<?php echo FoundationSkeleton::getColumns( 'footer:widget_bottom' );?> columns <?php echo $class;?>">
                    <?php dynamic_sidebar( 'footer-widget_bottom' ); ?>
                </div>
                <?php endif;?>
            </div>
        </div>

        <?php if( ($class = FoundationSkeleton::areaEnabled( 'footer:widget_postfix' )) !== false ): ?>
        <div id="zone-footer-postfix" class="<?php echo $class;?>">
            <div class="row">
                <div id="widget-footer-postfix" class="<?php echo FoundationSkeleton::getColumns( 'footer:widget_postfix' );?> columns">
                    <?php dynamic_sidebar( 'footer-widget_postfix' ); ?>
                </div>
            </div>
        </div>
        <?php endif;?>
    </div><!-- SECTION-FOOTER -->

    <?php if( has_nav_menu( 'footer-menu', 'foundation_skeleton' ) ): ?>
    <div id="section-bottom-menu">
        <div class="row">
            <div class="<?php echo FoundationSkeleton::getColumns( 'menus:bottom_menu' );?> columns fskelMenu">
                <?php wp_nav_menu(array(
                    'container'      => '',
                    'fallback_cb'    => false,
                    'menu_class'     => 'bottom-menu clearfix',
                    'theme_location' => 'bottom-menu'
                ));?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div><!-- #WRAPPER -->

    <? wp_footer(); ?>

</body>
</html>
