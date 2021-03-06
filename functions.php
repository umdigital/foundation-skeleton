<?php

/*-----------------------------------------------------------------------------------*/
/* Set Proper Parent/Child theme paths for inclusion
/*-----------------------------------------------------------------------------------*/

@define( 'PARENT_DIR', get_template_directory() );
@define( 'CHILD_DIR', get_stylesheet_directory() );

@define( 'PARENT_URL', get_template_directory_uri() );
@define( 'CHILD_URL', get_stylesheet_directory_uri() );

// stuff for old versions of php
include 'includes/functions-phpfix.php';

class FoundationSkeleton
{
    static private $_version            = 1.0;
    static private $_foundationVersion  = '5.5.2';
    static private $_fontAwesomeVersion = '4.6.3';

    static private $_config             = array();

    static private $_gitUpdate = array(
        'dir' => 'foundation-skeleton',
        'url' => 'https://github.com/umichcreative/foundation-skeleton',
        'zip' => 'https://github.com/umichcreative/foundation-skeleton/zipball/master',
        'raw' => 'https://raw.githubusercontent.com/umichcreative/foundation-skeleton/master',
    );

    /**
     * Load config, add actions/filters
     **/
    static public function init()
    {
        // just in case it wasn't installed with the intended name
        self::$_gitUpdate['dir'] = get_option( 'template' );

        // get current version
        $theme = wp_get_theme( self::$_gitUpdate['dir'] );
        self::$_version = $theme->get( 'Version' );

        /** LOAD CONFIG **/
        if( is_file( PARENT_DIR .'/config.json' ) ) {
            self::$_config = json_decode( file_get_contents( PARENT_DIR .'/config.json' ), true );
        }

        // check for child theme config and merge onto default
        $childConfig = false;
        if( (CHILD_DIR != PARENT_DIR) && is_file( CHILD_DIR .'/config.json' ) ) {
            $childConfig = json_decode( file_get_contents( CHILD_DIR .'/config.json' ), true );

            self::$_config = array_replace_recursive(
                self::$_config,
                $childConfig
            );
        }

        // default grid columns (defaults to 12)
        if( !isset( self::$_config['grid'] ) || !in_array( self::$_config['grid'], array( 16 ) ) ) {
            self::$_config['grid'] = false;
        }
        // replace config with col# defaults
        else if( is_file( PARENT_DIR .'/config-'. self::$_config['grid'] .'col.json' ) ) {
            if( $tColConfig = json_decode( file_get_contents( PARENT_DIR .'/config-'. self::$_config['grid'] .'col.json' ), true ) ) {
                self::$_config = array_replace_recursive(
                    self::$_config,
                    $tColConfig
                );
                
                // remerge child config on new defaults
                if( $childConfig ) {
                    self::$_config = array_replace_recursive(
                        self::$_config,
                        $childConfig
                    );
                }
            }
        }

        // tweak debug config if needed
        if( self::$_config['debug']['enabled'] && !self::$_config['debug']['showall'] && !is_user_logged_in() ) {
            self::$_config['debug']['enabled'] = 0;
        }


        /** REGISTER WIDGET LOCATIONS **/
        foreach( self::$_config as $zone => $configs ) {
            if( !$configs || !is_array( $configs ) ) {  
                continue;
            }

            foreach( $configs as $key => $config ) {
                if( (strpos( $key, 'widget_' ) !== false) && $config['enabled'] ) {
                    $config['name'] = isset( $config['name'] ) && $config['name']
                                    ? $config['name']
                                    : ucwords( str_replace( '_', ' ', $zone .'_'. $key ) );

                    register_sidebar(array(
                        'name'          => __( $config['name'], 'foundation_skeleton' ),
                        'id'            => $zone .'-'. $key,
                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                        'after_widget'  => '</div>',
                        'before_title'  => '<h4 class="widget-title">',
                        'after_title'   => '</h4>'
                    ));
                }
            }
        }

        // REGISTER ACTIONS/FILTERS
        add_action( 'after_setup_theme', 'FoundationSkeleton::setupTheme' );
        add_action( 'wp_enqueue_scripts', 'FoundationSkeleton::enqueue', 1 );
        add_action( 'wp_head', 'FoundationSkeleton::wpHead', 99 );
        add_action( 'wp_footer', 'FoundationSkeleton::wpFooter', 99 );

        if( !is_admin() ) {
            add_action( 'wp_before_admin_bar_render', 'FoundationSkeleton::adminBarRender' );
        }

        add_filter( 'body_class', 'FoundationSkeleton::bodyClass' );
        add_filter( 'excerpt_more', 'FoundationSkeleton::excerptMore' );

        // ALLOW SHORTCODES IN TEXT WIDGET
        add_filter('widget_text', 'do_shortcode');


        // THEME UPDATE HOOKS
        add_filter( 'pre_set_site_transient_update_themes', 'FoundationSkeleton::_updateCheck' );
        add_filter( 'upgrader_source_selection', 'FoundationSkeleton::_updateSource', 10, 3 );
        if( is_admin() ) {
            get_transient( 'update_themes' );
        }
    }

    /**
     * Override the settings in config.json for a specific template.
     * NOTE: only works to disable or resize.  Cannot enable something that is not globally enabled.
     **/
    static public function setConfig( $zone, $option, $flag, $value )
    {
        if( isset( self::$_config[ $zone ][ $option ][ $flag ] ) ) {
            self::$_config[ $zone ][ $option ][ $flag ] = $value;
        }
    }

    /**
     * Standard theme setup logic
     **/
    static public function setupTheme()
    {
        // Adds RSS feed links to <head> for posts and comments.
        add_theme_support( 'automatic-feed-links' );

        // image stuff
        add_theme_support( 'post-thumbnails' );

        // add thumbnail sizes
        foreach( self::$_config['thumbnails'] as $key => $thumb ) {
            if( $key == 'example-thumb-key' ) {
                continue;
            }

            add_image_size(
                $key,
                $thumb['width'],
                $thumb['height'],
                $thumb['crop']
            );
        }

        /**
         * This feature enables custom-menus support for a theme.
         * @see http://codex.wordpress.org/Function_Reference/register_nav_menus
         */
        foreach( self::$_config['menus'] as $key => $val ) {
            if( $val ) {
                register_nav_menu( $key, __( $val, 'foundation_skeleton' ) );
            }
        }

        add_theme_support('custom-header', array(
            // Header text display default
           'header-text'            => false,
            // Header image flex width
           'flex-width'             => true,
            // Header image width (in pixels)
           'width'                  => 300,
            // Header image flex height
           'flex-height'            => true,
            // Header image height (in pixels)
           'height'                 => 100
        ));
    }


    /**
     * Add debugging classes (as needed)
     **/
    static public function bodyClass( $classes )
    {
        // add grid class (for ie < 8 style removal)
        $classes[] = 'fskelGrid-'. (self::$_config['grid'] ?: 12);

        if( isset( self::$_config['debug']['enabled'] ) && self::$_config['debug']['enabled'] ) {
            $classes[] = 'fskelDebug';

            if( isset( self::$_config['debug']['grid'] ) && self::$_config['debug']['grid'] ) {
                $classes[] = 'fskelDebugGrid';
            }

            if( isset( self::$_config['debug']['widgets'] ) && self::$_config['debug']['widgets'] ) {
                $classes[] = 'fskelDebugWidgets';
            }
        }

        return $classes;
    }


    /**
     * Load base CSS/JS & autoload from styles/*.css and scripts/*.js for child themes
     **/
    static public function enqueue()
    {
        // vendor assets
        wp_enqueue_style( 'foundation-normalize', PARENT_URL .'/vendor/foundation-'. self::$_foundationVersion .'/css/normalize.css', null, self::$_foundationVersion );
        wp_enqueue_style( 'foundation-base', PARENT_URL .'/vendor/foundation-'. self::$_foundationVersion .'/css/foundation.min.css', null, self::$_foundationVersion );

        // load grid override
        if( self::$_config['grid'] ) {
            wp_enqueue_style( 'foundation-base-'. self::$_config['grid'] .'grid', PARENT_URL .'/vendor/foundation-'. self::$_config['grid'] .'col/foundation.min.css', null, self::$_foundationVersion );
        }

        wp_enqueue_style( 'font-awesome', PARENT_URL .'/vendor/font-awesome-'. self::$_fontAwesomeVersion .'/css/font-awesome.min.css', null, self::$_fontAwesomeVersion );
        wp_enqueue_script( 'jq-placeholder', PARENT_URL .'/vendor/scripts/jquery.placeholder.js', array( 'jquery' ), self::$_version );

        wp_enqueue_style( 'fskeleton-base', PARENT_URL .'/styles/base.css', null, self::$_version );
        wp_enqueue_script( 'fskeleton', PARENT_URL .'/scripts/fskeleton.js', array( 'jquery' ), self::$_version );

        // debug assets
        if( self::$_config['debug']['enabled'] ) {
            wp_enqueue_style( 'fskeleton-debug', PARENT_URL .'/styles/fskeleton-debug.css', null, self::$_version );
            wp_enqueue_script( 'fskeleton-debug', PARENT_URL .'/scripts/fskeleton-debug.js', array( 'jquery' ), self::$_version );
        }

        // autoload child styles/scripts from styles/scripts directories
        if( PARENT_DIR !== CHILD_DIR ) {
            $files = array();
            foreach( glob( CHILD_DIR . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR .'*.css' ) as $file ) {
                $files[ preg_replace( '/\.css$/i', '', basename( $file ) ) ] = $file;
            }
            ksort( $files );
            foreach( $files as $key => $file ) {
                $file = str_replace( CHILD_DIR, '', $file );
                wp_enqueue_style( 'child-'. $key, CHILD_URL . $file, null, self::$_version );
            }

            $files = array();
            foreach( glob( CHILD_DIR . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR .'*.js' ) as $file ) {
                $files[ preg_replace( '/\.js$/i', '', basename( $file ) ) ] = $file;
            }
            ksort( $files );
            foreach( $files as $key => $file ) {
                $file = str_replace( CHILD_DIR, '', $file );
                wp_enqueue_script( 'child-'. $key, CHILD_URL . $file, array( 'jquery' ), self::$_version );
            }
        }
    }

    /**
     * ADD HEADER IE CONDITIONALS
     **/
    static public function wpHead()
    {
        echo '
        <!--[if lt IE 9]>
        ';

        // load grid styles or default 12col grid overrides
        if( self::$_config['grid'] ) {
            echo '<link rel="stylesheet" href="'. PARENT_URL .'/vendor/foundation-'. self::$_config['grid'] .'col/foundation-ie8.css" />';
        }
        else {
            echo '<link rel="stylesheet" href="'. PARENT_URL .'/vendor/foundation-ie8.css" />';
        }

        echo '
        <link rel="stylesheet" href="'. PARENT_URL .'/styles/base-ie8.css" />
        <![endif]-->
        ';
    }

    /**
     * ADD FOOTER IE CONDITIONALS
     **/
    static public function wpFooter()
    {
        echo '
        <!--[if lt IE 9]>
        <script src="'. PARENT_URL .'/vendor/scripts/rem.js" type="text/javascript"></script>
        <![endif]-->
        ';

    }

    /**
     * Add grid overlay html
     */
    static public function getGridOverlay() {
        if( self::$_config['debug']['enabled'] ) {
            echo '
            <div id="fskelGridOverlay">
                <div class="row">
                ';

                for( $i=0; $i < (self::$_config['grid'] ?: 12); $i++ ) {
                echo '    <div class="small-1 columns"><div class="fskelGridBackground"></div></div>
                ';
                }
            echo '
                </div>
            </div>
            ';
        }
    }

    // EXCERPT MORE LINK
    static public function excerptMore( $more )
    {
        global $post;

        return ' <a href="'. get_permalink( $post->ID ) .'" class="readmore">Read more</a>';
    }

    // check if the archive paginates
    static public function willPaginate()
    {
        global $wp_query;

        if( !is_singular() && ($wp_query->max_num_pages > 1) ) {
            return true;
        }

        return false;
    }

    /**
     * Add admin bar options if debug enabled
     **/
    static public function adminBarRender()
    {
        global $wp_admin_bar;

        if( self::$_config['debug']['enabled'] ) {
            // root menu
            $wp_admin_bar->add_menu(array(
                'parent' => false,
                'id'     => 'fskeleton-admin-root',
                'title'  => 'Foundation Skeleton',
                'href'   => false
            ));

            // submenu item
            $wp_admin_bar->add_menu(array(
                'parent' => 'fskeleton-admin-root',
                'id'     => 'toggle-widgets',
                'title'  => self::$_config['debug']['widgets'] ? 'Hide Widget Areas' : 'Show Widget Areas',
                'href'   => '#',
                'meta'   => array(
                    'onclick' => 'return fskeletonToggleWidgets();'
                )
            ));

            $wp_admin_bar->add_menu(array(
                'parent' => 'fskeleton-admin-root',
                'id'     => 'toggle-grid',
                'title'  => self::$_config['debug']['grid'] ? 'Hide Grid' : 'Show Grid',
                'href'   => '#',
                'meta'   => array(
                    'onclick' => 'return fskeletonToggleGrid();'
                )
            ));
        }
    }

    /**
     * Check if an area is enabled or not
     **/
    static public function areaEnabled( $config )
    {
        $debug = 'fskelWidget';

        if( strpos( $config, ',' ) ) {
            $return      = false;
            $allDisabled = true;
            foreach( explode( ',', $config ) as $config ) {
                if( ($tmp = self::areaEnabled( $config )) !== false ) {
                    // part disabled by config ignore it
                    if( $tmp === false ) {
                        continue;
                    }

                    // part of the group is enabled
                    if( ($tmp === '') || ($tmp === $debug) ) {
                        $allDisabled = false;
                    }

                    // if this is the debug class then append "Group"
                    // if $debug is returned then set this to $debug.Group
                    if( (strpos( $tmp, $debug ) === 0) ) {
                        $tmp = $debug.'Group';
                    }

                    // if enabled has not been set
                    if( !$return ) {
                        $return = $tmp;
                    }
                }
            }
        
            if( $allDisabled && self::$_config['debug']['enabled'] ) {
                $return .= ' fskelInactive';
            }

            return $return;
        }
        else {
            list( $zone, $location ) = explode( ':', $config );
            
            $return = false;

            // if enabled in config
            if( isset( self::$_config[ $zone ][ $location ]['enabled'] ) && self::$_config[ $zone ][ $location ]['enabled'] ) {
                // if we have widgets configured
                if( is_active_sidebar( $zone .'-'. $location ) ) {
                    return self::$_config['debug']['enabled'] ? $debug : ''; // show this area
                }
                else if( self::$_config['debug']['enabled'] ) {
                    return $debug . ' fskelInactive';
                }
            }

            return $return;
        }
    }

    /**
     * Get columns for a location
     **/
    static public function getColumns( $config, $default = null )
    {
        $defaultColumns = (self::$_config['grid'] ?: 12);

        $columns = $default ? $default : array( 'large' => $defaultColumns );

        list( $zone, $location ) = explode( ':', $config );

        if( isset( self::$_config[ $zone ][ $location ]['columns'] ) ) {
            $columns = self::$_config[ $zone ][ $location ]['columns'];
        }

        $return = null;
        foreach( $columns as $key => $val ) {
            $val = $val ?: $defaultColumns;

            $return[] = "{$key}-{$val}";
        }

        return implode( ' ', $return );
    }   

    /**
     * Calculate and get columns for main content well
     **/
    static public function getContentColumns()
    {
        $defaultColumns = (self::$_config['grid'] ?: 12);

        $columns = self::$_config['general']['columns'];
        $columns = array(
            'small'  => $defaultColumns,
            'medium' => $defaultColumns,
            'large'  => $defaultColumns
        );

        if( self::areaEnabled( 'content:widget_first' ) !== false ) {
            $columns['small']  -= self::$_config['content']['widget_first']['columns']['small'];
            $columns['medium'] -= self::$_config['content']['widget_first']['columns']['medium'];
            $columns['large']  -= self::$_config['content']['widget_first']['columns']['large'];
        }

        if( self::areaEnabled( 'content:widget_second' ) !== false ) {
            $columns['small']  -= self::$_config['content']['widget_second']['columns']['small'];
            $columns['medium'] -= self::$_config['content']['widget_second']['columns']['medium'];
            $columns['large']  -= self::$_config['content']['widget_second']['columns']['large'];
        }

        $return = null;
        foreach( $columns as $key => $val ) {
            if( $val < 1 ) {
                $val = $defaultColumns;
            }

            $return[] = "{$key}-{$val}";
        }

        return implode( ' ', $return );
    }


    /**
     * Check for updated version
     **/
    static public function _updateCheck( $checkedData )
    {
        $raw = wp_remote_get(
            trailingslashit( self::$_gitUpdate['raw'] ) .'style.css',
            array( 'sslverify' => true )
        );

        if( !is_array( $raw ) || !isset( $raw['body'] ) ) {
            return $checkedData;
        }

        if( preg_match( '#^\s*Version\:\s*(.*)$#im', $raw['body'], $matches ) ) {
            $version = $matches[1];
        }

        if( $version && version_compare( $version, self::$_version ) ) {
            $checkedData->response[ get_option( 'template' ) ] = array(
                'package'     => self::$_gitUpdate['zip'],
                'new_version' => $version,
                'url'         => self::$_gitUpdate['url']
            );
        }

        return $checkedData;
    }

    /**
     * Rename downloaded source directory
     **/
    static public function _updateSource( $source, $remote, $upgrader )
    {
        global $wp_filesystem;

        // check for upgrade process
        if( !is_a( $upgrader, 'Theme_Upgrader' ) || !isset( $source, $remote ) ) {
            return $source;
        }

        // check if this theme is being updated
        if( stripos( basename( $source ), basename( self::$_gitUpdate['url'] ) ) === false ) {
            return $source;
        }

        // Rename source
        $destination = trailingslashit( $remote ) . self::$_gitUpdate['dir'];
        if( $wp_filesystem->move( $source, $destination, true ) ) {
            return $destination;
        }

        return new WP_Error();
    }
}
FoundationSkeleton::init();
