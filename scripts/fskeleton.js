(function($){
    $(document).ready(function(){
        if ($("HTML").hasClass("lte-ie8")) {
            for (var c = 1; c <= 12; c++) {
                $('.small-' + c + '[class*="medium"]').removeClass("small-" + c);
                $('.small-' + c + '[class*="large"]').removeClass("small-" + c);
                $('.medium-' + c + '[class*="large"]').removeClass("medium-" + c);
                //Perform any other column-specific adjustments your design may require here
            }

            $('.show-for-small').remove();
            $('.hide-for-small').removeClass('hide-for-small');
            $('.top-bar-section UL').css({ width: "inherit" });
            $('.top-bar-section UL LI').css({ float: "left" });
            $('.top-bar').css({ height: "inherit" });

            $('img').removeAttr('height').removeAttr('width');
        }

        if ($.browser.msie && 8 == parseInt($.browser.version)) {
            // IE font race condition fix
            var head = document.getElementsByTagName('head')[0],
                style = document.createElement('style');
            style.type = 'text/css';
            style.styleSheet.cssText = ':before,:after{content:none !important';
            head.appendChild(style);
            setTimeout(function(){
                head.removeChild(style);
            }, 0);
        }

        $('input').placeholder();

        var mainMenuWrap = $('#header .menu');
        var mainMenuList = mainMenuWrap.find('.header-menu');

        var totalWidth = 25;
        mainMenuList.find('> li').each(function(){
            totalWidth += $(this).outerWidth( true );
        });

        $(window).resize(function(){
            if( $(window).width() < totalWidth ) {
                if( !mainMenuWrap.hasClass('responsive') ) {
                    mainMenuList.hide();
                    mainMenuWrap.addClass('responsive');
                }
            }
            else {
                if( mainMenuWrap.hasClass('responsive') ) {
                    mainMenuWrap.removeClass('responsive');
                    mainMenuList.show();
                }
            }
        }).trigger('resize');

        $(document).on('click', '#header .menu .hamburger-header', function(){
            mainMenuList.slideToggle();
        });
    });
}(jQuery));