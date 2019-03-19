/**
 * jQuery plugin to make the main navigation WAI-ARIA compatible
 * Inspired by http://simplyaccessible.com/examples/css-menu/option-6/
 *
 * It needs jquery.hoverIntent
 */
(function( $ ) {

	$.fn.ariaNavigation = function( settings ) {

		//Map of all the alphanumeric keys so one can jump through submenus by typing the first letter
		//Also use the ESC key to close a submenu
		var keyCodeMap = {
				48: "0",
				49: "1",
				50: "2",
				51: "3",
				52: "4",
				53: "5",
				54: "6",
				55: "7",
				56: "8",
				57: "9",
				59: ";",
				65: "a",
				66: "b",
				67: "c",
				68: "d",
				69: "e",
				70: "f",
				71: "g",
				72: "h",
				73: "i",
				74: "j",
				75: "k",
				76: "l",
				77: "m",
				78: "n",
				79: "o",
				80: "p",
				81: "q",
				82: "r",
				83: "s",
				84: "t",
				85: "u",
				86: "v",
				87: "w",
				88: "x",
				89: "y",
				90: "z",
				96: "0",
				97: "1",
				98: "2",
				99: "3",
				100: "4",
				101: "5",
				102: "6",
				103: "7",
				104: "8",
				105: "9"
			},
			$nav = $( this ),
			$allLinks = $nav.find( 'li.menu-item > a, li.page_item > a' ),
			$topLevelLinks = $nav.find( '> li > a' ),
			subLevelLinks = $topLevelLinks.parent( 'li' ).find( 'ul' ).find( 'a' );
			navWidth = $nav.outerWidth();

		//default settings
		settings = jQuery.extend( {
			menuHoverClass: 'hover',
			topMenuHoverClass: 'hover'
		}, settings );


		/**
		 *  First add the needed WAI-ARIA markup - supercharge the menu
		 */

		// Add ARIA role to menubar and menu items
		$nav.attr( 'role', 'menubar' ).find( 'li' ).attr( 'role', 'menuitem' );

		$topLevelLinks.each( function() {
			//for regular sub-menus
			// Set tabIndex to -1 so that links can't receive focus until menu is open
			$( this ).next( 'ul' )
				.attr( {'aria-hidden': 'true', 'role': 'menu'} )
				.find( 'a' )
				.attr( 'tabIndex', -1 );

			// Add aria-haspopup for appropriate items
			if ( $( this ).next( 'ul' ).length > 0 ) {
				$( this ).parent( 'li' ).attr( 'aria-haspopup', 'true' );
			}
		} );


		/**
		 * Now let's begin binding things to their proper events
		 */

		// First, bind to the hover event
		// use hoverIntent to make sure we avoid flicker
		$allLinks.closest( 'li' ).hoverIntent( {
			over: function() {
				//clean up first
				$( this ).closest( 'ul' )
					.find( 'ul.' + settings.menuHoverClass )
					.attr( 'aria-hidden', 'true' )
					.removeClass( settings.menuHoverClass )
					.find( 'a' )
					.attr( 'tabIndex', -1 );

				$( this ).closest( 'ul' )
					.find( '.' + settings.topMenuHoverClass )
					.removeClass( settings.topMenuHoverClass );

				//now do things
				showSubMenu( $( this ) );

			},
			out: function() {
				hideSubMenu( $( this ) );
			},
			timeout: 300
		} );

		// Secondly, bind to the focus event - very important for WAI-ARIA purposes
		$allLinks.focus( function() {
			//clean up first
			$( this ).closest( 'ul' )
				.find( 'ul.' + settings.menuHoverClass )
				.attr( 'aria-hidden', 'true' )
				.removeClass( settings.menuHoverClass )
				.find( 'a' )
				.attr( 'tabIndex', -1 );

			$( this ).closest( 'ul' )
				.find( '.' + settings.topMenuHoverClass )
				.removeClass( settings.topMenuHoverClass );

			//now do things
			showSubMenu( $( this ).closest( 'li' ) );

		} );


		// Now bind arrow keys for navigating the menu

		// First the top level links (the permanent visible links)
		$topLevelLinks.keydown( function( e ) {
			var $item = $( this );

			if ( e.keyCode == 37 ) { //left arrow
				e.preventDefault();
				// This is the first item
				if ( $item.parent( 'li' ).prev( 'li' ).length == 0 ) {
					$item.parents( 'ul' ).find( '> li' ).last().find( 'a' ).first().focus();
				} else {
					$item.parent( 'li' ).prev( 'li' ).find( 'a' ).first().focus();
				}
			} else if ( e.keyCode == 38 ) { //up arrow
				e.preventDefault();
				if ( $item.parent( 'li' ).find( 'ul' ).length > 0 ) {
					$item.parent( 'li' ).find( 'ul' )
						.attr( 'aria-hidden', 'false' )
						.addClass( settings.menuHoverClass )
						.find( 'a' ).attr( 'tabIndex', 0 )
						.last().focus();
				}
			} else if ( e.keyCode == 39 ) { //right arrow
				e.preventDefault();

				// This is the last item
				if ( $item.parent( 'li' ).next( 'li' ).length == 0 ) {
					$item.parents( 'ul' ).find( '> li' ).first().find( 'a' ).first().focus();
				} else {
					$item.parent( 'li' ).next( 'li' ).find( 'a' ).first().focus();
					}
			} else if ( e.keyCode == 40 ) { //down arrow
				e.preventDefault();
				if ( $item.parent( 'li' ).find( 'ul' ).length > 0 ) {
					$item.parent( 'li' ).find( 'ul' )
						.attr( 'aria-hidden', 'false' )
						.addClass( settings.menuHoverClass )
						.find( 'a' ).attr( 'tabIndex', 0 )
						.first().focus();
				}
			} else if ( e.keyCode == 32 ) { //space key
				// If submenu is hidden, open it
				e.preventDefault();
				$item.parent( 'li' ).find( 'ul[aria-hidden=true]' )
					.attr( 'aria-hidden', 'false' )
					.addClass( settings.menuHoverClass )
					.find( 'a' ).attr( 'tabIndex', 0 )
					.first().focus();
			} else if ( e.keyCode == 27 ) { //escape key
				e.preventDefault();
				$( '.' + settings.menuHoverClass )
					.attr( 'aria-hidden', 'true' )
					.removeClass( settings.menuHoverClass )
					.find( 'a' )
					.attr( 'tabIndex', -1 );
			} else { //cycle through the child submenu items based on the first letter
				$item.parent('li').find('ul[aria-hidden=false] > li > a').each( function() {
					if ( $( this ).text().substring( 0, 1 ).toLowerCase() == keyCodeMap[e.keyCode] ) {
						$( this ).focus();
						return false;
					}
				} );
			}
		} );

		// Now do the keys bind for the submenus links
		$( subLevelLinks ).keydown( function( e ) {
			var $item = $( this );

			if ( e.keyCode == 38 ) { //up arrow
				e.preventDefault();
				// This is the first item
				if ( $item.parent( 'li' ).prev( 'li' ).length == 0 ) {
					$item.parents( 'ul' ).parents( 'li' ).find( 'a' ).first().focus();
				} else {
					$item.parent( 'li' ).prev( 'li' ).find( 'a' ).first().focus();
				}
			} else if ( e.keyCode == 39 ) { //right arrow
				e.preventDefault();

				//if it has sub-menus we should go into them
				if ( $item.parent( 'li' ).hasClass('menu-item-has-children') ) {
					$item.next('ul' ).find( '> li' ).first().find( 'a' ).first().focus();
				} else {
					// This is the last item
					if ( $item.parent( 'li' ).next( 'li' ).length == 0 ) {
						$item.closest('ul').closest('li').children('a').first().focus();
					} else {
						$item.parent( 'li' ).next( 'li' ).find( 'a' ).first().focus();
					}
				}
			} else if ( e.keyCode == 40 ) { //down arrow
				e.preventDefault();

				// This is the last item
				if ( $item.parent( 'li' ).next( 'li' ).length == 0 ) {
					$item.closest('ul').closest('li').children('a').first().focus();
				} else {
					$item.parent( 'li' ).next( 'li' ).find( 'a' ).first().focus();
				}
			} else if ( e.keyCode == 27 || e.keyCode == 37 ) { //escape key or left arrow => jump to the upper level links
				e.preventDefault();

				//focus on the upper level link
				$item.closest('ul').closest('li')
					.children('a').first().focus();

			} else if ( e.keyCode == 32 ) { //space key
				e.preventDefault();
				window.location = $item.attr( 'href' );
			} else {

				//cycle through the menu items based on the first letter
				var found = false;
				$item.parent( 'li' ).nextAll( 'li' ).find( 'a' ).each( function() {
					if ( $( this ).text().substring( 0, 1 ).toLowerCase() == keyCodeMap[e.keyCode] ) {
						$( this ).focus();
						found = true;
						return false;
					}
				} );

				if ( !found ) {
					$item.parent( 'li' ).prevAll( 'li' ).find( 'a' ).each( function() {
						if ( $( this ).text().substring( 0, 1 ).toLowerCase() == keyCodeMap[e.keyCode] ) {
							$( this ).focus();
							return false;
						}
					} );
				}
			}
		} );


		// Hide menu if click or focus occurs outside of navigation
		$nav.find( 'a' ).last().keydown( function( e ) {
			if ( e.keyCode == 9 ) { //tab key
				// If the user tabs out of the navigation hide all menus
				hideSubMenus();
			}
		} );

		//close all menus when pressing ESC key
		$( document ).keydown( function( e ) {
			if ( e.keyCode == 27 ) { //esc key
				hideSubMenus();
			}
		} );

		//close all menus on click outside
		$( document ).click( function() {
			hideSubMenus();
		} );

		function showSubMenu( $item ) {

			$item.addClass( settings.topMenuHoverClass );

			$item.find( '.sub-menu' ).first() //affect only the first ul found - the one with the submenus, ignore the mega menu items
				.attr( 'aria-hidden', 'false' )
				.addClass( settings.menuHoverClass );

			$item.find( 'a' ).attr( 'tabIndex', 0 ); //set the tabIndex to 0 so we let the browser figure out the tab order

		}

		function hideSubMenu( $item ) {

			$item.children('a' ).first().next('ul')
				.attr( 'aria-hidden', 'true' )
				.removeClass( settings.menuHoverClass )
				.find( 'a' )
				.attr( 'tabIndex', -1 );

			$item.removeClass( settings.topMenuHoverClass );
		}

		function hideSubMenus() {

			$( '.' + settings.menuHoverClass )
				.attr( 'aria-hidden', 'true' )
				.removeClass( settings.menuHoverClass );

			$( '.' + settings.topMenuHoverClass ).removeClass( settings.topMenuHoverClass );

		}
	}

})( jQuery );