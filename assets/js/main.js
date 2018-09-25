(function($) {
    "use strict";

    /**
     * Detect what platform are we on (browser, mobile, etc)
     */

    function platformDetect() {
        isTouchDevice   = !!('ontouchstart' in window);
        is_ie           = typeof (is_ie) !== "undefined" || (!(window.ActiveXObject) && "ActiveXObject" in window);
        is_ie_le10      = ua.match(/msie (9|([1-9][0-9]))/i);
        is_ie9          = ua.match(/msie (9)/i);

        if (isTouchDevice) $('html').addClass('touch');

        if(is_ie) $('html').addClass('is--ie');
        if(is_ie_le10) $('html').addClass('is--ie-le10');
        if(is_ie9) $('html').addClass('is--ie9');
    }

    // /* ====== SHARED VARS ====== */
    // These do not depend on jQuery
    var isTouchDevice, is_ie, is_ie9, is_ie_le10;
    var ua = navigator.userAgent;

    $(document).ready(function() {
        $('body').addClass('is--ready');
        platformDetect();


        if (!isTouchDevice) {
            var $nav = $('.main-navigation'),
                navHeight = $nav.outerHeight();

            $('.site').css('padding-top', navHeight);
            $nav.addClass('main-navigation--fixed');
        }

        /**
         * Sub menus handling on touch devices
         */
        if (isTouchDevice) {
            $( '.menu-item-has-children > a, .page_item_has_children > a' ).on( 'touchstart click', function(e) {
                var posX = e.originalEvent.touches[0].pageX ? e.originalEvent.touches[0].pageX : e.pageX;
                var width = $(this).outerWidth();

                if ( ( width - posX ) < 80 ) {
                    e.preventDefault();
                    e.stopPropagation();

                    if($(this).parent().hasClass('hover')) {
                        $(this).parent().removeClass('hover');
                    } else {
                        $(this).parent().addClass('hover');
                        $(this).parent().siblings().removeClass('hover');
                    }
                }
            } );
        } else {
            $('.primary-menu ul').ariaNavigation();
        }
    });

    $(window).load(function() {
        $('body').addClass('is--loaded');
        $('.menu-close').appendTo('.primary-menu');
        cloneSocialMenu();
        movePopularPostsCategory();

        /**
         * Sub menus handling on touch devices
         */
        if (isTouchDevice) {
            $('.menu-item-has-children > a, .page_item_has_children > a').on('touchstart click', function(e) {
                var posX = e.originalEvent.touches[0].pageX ? e.originalEvent.touches[0].pageX : e.pageX;
                var width = $(this).outerWidth();

                if( (width - posX) < 80 ) {

                    e.preventDefault();
                    e.stopPropagation();

                    if($(this).parent().hasClass('hover')) {
                        $(this).parent().removeClass('hover');
                    } else {
                        $(this).parent().addClass('hover');
                        $(this).parent().siblings().removeClass('hover');
                    }
                }
            });
        }
    });

    /**
     * Mobile menu, mobile sidebar, search toggle
     */
    $('.overlay-toggle').on('touchstart click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        $('body').toggleClass('overlay-is-open');

        if ( $('body').hasClass('overlay-is-open') ) {
            $('body').width($('body').width());
            $('body').css('overflow', 'hidden');
        } else {
            $('body').removeAttr('style');
        }
    });

    $('.right-close-button').on('touchstart click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        $('body').removeClass('sidebar-is-open');

        if (searchIsOpen) {
            searchIsOpen = !searchIsOpen;
            $('body').removeClass('search-is-open');
            $('.search-overlay .search-field').blur();
        }
    });

    // Esc key to close search
    $(document).keydown( function( e ) {
        if ( e.keyCode == 27 ) {

            if (searchIsOpen) {
                searchIsOpen = !searchIsOpen;

                $('body').removeClass('search-is-open');
                $('.search-overlay .search-field').blur();
            }

            $('body').removeClass('overlay-is-open');
            $('body').removeAttr('style');
        }
    });

    $('.menu-toggle').on('touchstart click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('body').toggleClass('nav-is-open');
    });

    $('.sidebar-toggle').on('touchstart click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('body').toggleClass('sidebar-is-open');
    });

    var searchIsOpen = false;
    $('.search-toggle').on('touchstart click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        $('body').toggleClass('search-is-open');
        searchIsOpen = !searchIsOpen;

        if (searchIsOpen) {
            $('.search-overlay .search-field').focus();
        } else {
            $('.search-overlay .search-field').blur();
        }
    });


    /**
     * Attach social menu icons to mobile menu
     */
    function cloneSocialMenu() {
        $('<li/>', {
            class: 'social-menu-items-mobile'
        }).appendTo('.primary-menu');

        $('.social-menu').clone().appendTo($('.social-menu-items-mobile'));
    }

    /**
     * Function to move the category above the title
     * on Top Posts widget
     */
    function movePopularPostsCategory() {
        var widget = $('.widgets-list-layout');

        if(widget) {
            $(widget).children().each( function(){
                var $destination = $(this).find('.widgets-list-layout-links');
                $(this).find('.cat-links').insertBefore($destination);

            });
        }
    }

    /* Handle the archives months dropdown - on select redirect to URL */
    var monthDropdown = document.getElementById( "page-filter-by-month" );
    function onMonthChange() {
        if ( monthDropdown.options[ monthDropdown.selectedIndex ].value.length > 0 ) {
            location.href = monthDropdown.options[ monthDropdown.selectedIndex ].value;
        }
    }
    if ( monthDropdown ) {
        monthDropdown.onchange = onMonthChange;
    }

    /* Handle the archives tags dropdown - on select redirect to URL */
    var tagDropdown = document.getElementById( "page-filter-by-tag" );
    function onTagChange() {
        if ( tagDropdown.options[ tagDropdown.selectedIndex ].value.length > 0 ) {
            location.href = tagDropdown.options[ tagDropdown.selectedIndex ].value;
        }
    }
    if ( tagDropdown ) {
        tagDropdown.onchange = onTagChange;
    }

    /* Handle the archives category dropdown - on select redirect to URL */
    var catDropdown = document.getElementById( "page-filter-by-category" );
    function onCatChange() {
        if ( catDropdown.options[ catDropdown.selectedIndex ].value > 0 ) {
            location.href = jasonData.homeUrl + "/?cat=" + catDropdown.options[ catDropdown.selectedIndex ].value;
        }
    }
    if ( catDropdown ) {
        catDropdown.onchange = onCatChange;
    }



    /*
     * debouncedresize: special jQuery event that happens once after a window resize
     *
     * latest version and complete README available on Github:
     * https://github.com/louisremi/jquery-smartresize
     *
     * Copyright 2012 @louis_remi
     * Licensed under the MIT license.
     *
     * This saved you an hour of work?
     * Send me music http://www.amazon.co.uk/wishlist/HNTU0468LQON
     */
    (function($) {

        var $event = $.event,
            $special,
            resizeTimeout;

        $special = $event.special.debouncedresize = {
            setup: function() {
                $( this ).on( "resize", $special.handler );
            },
            teardown: function() {
                $( this ).off( "resize", $special.handler );
            },
            handler: function( event, execAsap ) {
                // Save the context
                var context = this,
                    args = arguments,
                    dispatch = function() {
                        // set correct event type
                        event.type = "debouncedresize";
                        $event.dispatch.apply( context, args );
                    };

                if ( resizeTimeout ) {
                    clearTimeout( resizeTimeout );
                }

                execAsap ?
                    dispatch() :
                    resizeTimeout = setTimeout( dispatch, $special.threshold );
            },
            threshold: 150
        };

    })(jQuery);

})(jQuery);