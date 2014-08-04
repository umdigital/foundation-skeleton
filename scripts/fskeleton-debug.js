jQuery(document).ready(function(){
    jQuery('.fskelWidget').each(function(){
        var id = jQuery(this).attr( 'id' );
        var name = id.replace( /^(zone|widget)-/, '' ).replace( /-/g, ' ' );

        if( jQuery(this).find('.row .columns').length ) {
            var elem = jQuery(this).find('.row .columns');
        }
        else {
            var elem = jQuery(this);
        }

        elem.prepend('<div id="'+ id +'-description" class="fskelWidgetDescription" title="'+ name +'"><h6>'+ name +'</h6><p>debugging block</p></div>');
    });
});

function fskeletonToggleWidgets()
{
    jQuery('body').toggleClass( 'fskelDebugWidgets' );

    if( jQuery( 'body' ).hasClass( 'fskelDebugWidgets' ) ) {
        jQuery( '#wp-admin-bar-toggle-widgets' ).find( 'a.ab-item' ).text( 'Hide Widget Areas' );
    }
    else {
        jQuery( '#wp-admin-bar-toggle-widgets' ).find( 'a.ab-item' ).text( 'Show Widget Areas' );
    }

    return false;
}

function fskeletonToggleGrid()
{
    jQuery('body').toggleClass( 'fskelDebugGrid' );

    if( jQuery( 'body' ).hasClass( 'fskelDebugGrid' ) ) {
        jQuery( '#wp-admin-bar-toggle-grid' ).find( 'a.ab-item' ).text( 'Hide Grid' );
    }
    else {
        jQuery( '#wp-admin-bar-toggle-grid' ).find( 'a.ab-item' ).text( 'Show Grid' );
    }

    return false;
}
