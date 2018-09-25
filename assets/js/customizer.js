/**
 * Jason Customizer JavaScript - keeps things nicer for all
 * v 1.0.0
 */

/**
 * Some AJAX powered controls
 * jQuery is available
 */
(function( $ ) {

	// Change site title and description when they are typed
	wp.customize( 'blogname', function( value ) {
		value.bind( function( text ) {
			//first we need to wrap the first half with our special span
			var halves = jason_get_halves( text, 0.25 );

			if ( false === halves ) {
				$( '.site-title a' ).text( text );
			} else {
				$( '.site-title a' ).text( halves[1] );
				$( '.site-title a').prepend('<span class="alt"></span>').find('span:first').text( halves[0] );
			}

			//now take care of the size classes
			var siteTitleLength = strlen(text);

			$('.site-branding').removeClass('site-branding--size-small site-branding--size-medium site-branding--size-large');
			if ( siteTitleLength < 8 ) {
				$('.site-branding').addClass('site-branding--size-large');
			} else if ( siteTitleLength > 20 ) {
				$('.site-branding').addClass('site-branding--size-small');
			} else {
				$('.site-branding').addClass('site-branding--size-medium');
			}
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( text ) {
			$( '.site-description-text' ).text( text );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'color': to,
					'position': 'relative'
				} );
			}
		} );
	} );

	// Update the site title size class
	wp.customize( 'jason_title_size', function( value ) {
		value.bind( function( sizeClass ) {
			//remove the previous size class
			$( '.site-branding' ).removeClass( function (index, css) {
				return (css.match (/(^|\s)site-branding--size-\S+/g) || []).join(' ');
			});

			//add the new class
			$( '.site-branding' ).addClass( sizeClass );

		} );
	} );

	// Update the site title style class
	wp.customize( 'jason_site_title_styling', function( value ) {
		value.bind( function( styleClass ) {
			var $logo = $( '.site-branding' );
			//remove the previous style class
			$logo.removeClass( function (index, css) {
				return (css.match (/(^|\s)site-branding--style-\S+/g) || []).join(' ');
			});

			//add the new class
			var $clone = $logo.clone(true).addClass( styleClass );

			$logo.replaceWith($clone);
		} );
	} );

	/**
	 * Returns an array with the halves of the provided string. It cuts by spaces.
	 *
	 * @since Jason 1.0
	 *
	 * @param string $content The string to cut
	 * @param float $threshold Optional. If a space is not found in the first half, for how long should we search next.
	 * This is a percentage of the total $content length
	 *
	 * @return array with the two parts or false if it could not split
	 */
	function jason_get_halves( content, threshold ) {
		//Convert HTML special chars into normal text
		content = html_entity_decode( content );
		//Also cut line breaks
		content = content.replace(/(\r\n|\n|\r)/gm,"");

		var content_length = strlen( content);
		//If we have a string that is one or two characters long we really don't have anything to work with
		if ( content_length < 3 ) {
			return false;
		}

		//determine the middle point
		var limit = parseInt( content_length / 2 );

		//Find the last space symbol position within the given range
		var last_space = strrpos( substr( content, 0, limit), " ", 0 );

		if ( false === last_space ) {
			//it seems we haven't found a space in the first exact half
			//let's give it another change by searching for the next space to the right
			var next_space = strpos( content, " ", limit );

			//only split here if we are no further than the $threshold from the middle
			//also we don't split by the space at the end
			if ( false !== next_space && next_space != (content_length - 1) && next_space <= Math.floor( content_length * ( 0.5 + threshold ) ) ) {
				last_space = next_space;
			}
		}

		//if no space was found, return the original
		if ( false === last_space ) {
			return false;
		}

		return [ substr( content, 0, last_space ), substr( content, last_space, 9999 ) ];
	}

	//utility functions from php.js http://phpjs.org/

	/*
	 * License for these functions:
	 *
	 * Copyright (c) 2013 Kevin van Zonneveld (http://kvz.io)
	 * and Contributors (http://phpjs.org/authors)

	 * Permission is hereby granted, free of charge, to any person obtaining a copy of
	 * this software and associated documentation files (the "Software"), to deal in
	 * the Software without restriction, including without limitation the rights to
	 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
	 * of the Software, and to permit persons to whom the Software is furnished to do
	 * so, subject to the following conditions:

	 * The above copyright notice and this permission notice shall be included in all
	 * copies or substantial portions of the Software.
	 */

	/**
	 * https://raw.githubusercontent.com/kvz/phpjs/master/functions/strings/strpos.js
	 *
	 * @param haystack
	 * @param needle
	 * @param offset
	 * @returns {*}
	 */
	function strpos(haystack, needle, offset) {
		//  discuss at: http://phpjs.org/functions/strpos/
		// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// improved by: Onno Marsman
		// improved by: Brett Zamir (http://brett-zamir.me)
		// bugfixed by: Daniel Esteban
		//   example 1: strpos('Kevin van Zonneveld', 'e', 5);
		//   returns 1: 14

		var i = (haystack + '')
			.indexOf(needle, (offset || 0));
		return i === -1 ? false : i;
	}

	/**
	 * https://raw.githubusercontent.com/kvz/phpjs/master/functions/strings/strrpos.js
	 *
	 * @param haystack
	 * @param needle
	 * @param offset
	 * @returns {*}
	 */
	function strrpos(haystack, needle, offset) {
		//  discuss at: http://phpjs.org/functions/strrpos/
		// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// bugfixed by: Onno Marsman
		// bugfixed by: Brett Zamir (http://brett-zamir.me)
		//    input by: saulius
		//   example 1: strrpos('Kevin van Zonneveld', 'e');
		//   returns 1: 16
		//   example 2: strrpos('somepage.com', '.', false);
		//   returns 2: 8
		//   example 3: strrpos('baa', 'a', 3);
		//   returns 3: false
		//   example 4: strrpos('baa', 'a', 2);
		//   returns 4: 2

		var i = -1;
		if (offset) {
			i = (haystack + '')
				.slice(offset)
				.lastIndexOf(needle); // strrpos' offset indicates starting point of range till end,
			// while lastIndexOf's optional 2nd argument indicates ending point of range from the beginning
			if (i !== -1) {
				i += offset;
			}
		} else {
			i = (haystack + '')
				.lastIndexOf(needle);
		}
		return i >= 0 ? i : false;
	}

	/**
	 * https://raw.githubusercontent.com/kvz/phpjs/master/functions/strings/html_entity_decode.js
	 *
	 * @param string
	 * @param quote_style
	 * @returns {*}
	 */
	function html_entity_decode(string, quote_style) {
		//  discuss at: http://phpjs.org/functions/html_entity_decode/
		// original by: john (http://www.jd-tech.net)
		//    input by: ger
		//    input by: Ratheous
		//    input by: Nick Kolosov (http://sammy.ru)
		// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// improved by: marc andreu
		//  revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		//  revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// bugfixed by: Onno Marsman
		// bugfixed by: Brett Zamir (http://brett-zamir.me)
		// bugfixed by: Fox
		//  depends on: get_html_translation_table
		//   example 1: html_entity_decode('Kevin &amp; van Zonneveld');
		//   returns 1: 'Kevin & van Zonneveld'
		//   example 2: html_entity_decode('&amp;lt;');
		//   returns 2: '&lt;'

		var hash_map = {},
			symbol = '',
			tmp_str = '',
			entity = '';
		tmp_str = string.toString();

		if (false === (hash_map = get_html_translation_table('HTML_ENTITIES', quote_style))) {
			return false;
		}

		// fix &amp; problem
		// http://phpjs.org/functions/get_html_translation_table:416#comment_97660
		delete(hash_map['&']);
		hash_map['&'] = '&amp;';

		for (symbol in hash_map) {
			entity = hash_map[symbol];
			tmp_str = tmp_str.split(entity)
				.join(symbol);
		}
		tmp_str = tmp_str.split('&#039;')
			.join("'");

		return tmp_str;
	}

	/**
	 * https://raw.githubusercontent.com/kvz/phpjs/master/functions/strings/get_html_translation_table.js
	 *
	 * @param table
	 * @param quote_style
	 * @returns {{}}
	 */
	function get_html_translation_table(table, quote_style) {
		//  discuss at: http://phpjs.org/functions/get_html_translation_table/
		// original by: Philip Peterson
		//  revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// bugfixed by: noname
		// bugfixed by: Alex
		// bugfixed by: Marco
		// bugfixed by: madipta
		// bugfixed by: Brett Zamir (http://brett-zamir.me)
		// bugfixed by: T.Wild
		// improved by: KELAN
		// improved by: Brett Zamir (http://brett-zamir.me)
		//    input by: Frank Forte
		//    input by: Ratheous
		//        note: It has been decided that we're not going to add global
		//        note: dependencies to php.js, meaning the constants are not
		//        note: real constants, but strings instead. Integers are also supported if someone
		//        note: chooses to create the constants themselves.
		//   example 1: get_html_translation_table('HTML_SPECIALCHARS');
		//   returns 1: {'"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;'}

		var entities = {},
			hash_map = {},
			decimal;
		var constMappingTable = {},
			constMappingQuoteStyle = {};
		var useTable = {},
			useQuoteStyle = {};

		// Translate arguments
		constMappingTable[0] = 'HTML_SPECIALCHARS';
		constMappingTable[1] = 'HTML_ENTITIES';
		constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
		constMappingQuoteStyle[2] = 'ENT_COMPAT';
		constMappingQuoteStyle[3] = 'ENT_QUOTES';

		useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
		useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() :
			'ENT_COMPAT';

		if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
			throw new Error('Table: ' + useTable + ' not supported');
			// return false;
		}

		entities['38'] = '&amp;';
		if (useTable === 'HTML_ENTITIES') {
			entities['160'] = '&nbsp;';
			entities['161'] = '&iexcl;';
			entities['162'] = '&cent;';
			entities['163'] = '&pound;';
			entities['164'] = '&curren;';
			entities['165'] = '&yen;';
			entities['166'] = '&brvbar;';
			entities['167'] = '&sect;';
			entities['168'] = '&uml;';
			entities['169'] = '&copy;';
			entities['170'] = '&ordf;';
			entities['171'] = '&laquo;';
			entities['172'] = '&not;';
			entities['173'] = '&shy;';
			entities['174'] = '&reg;';
			entities['175'] = '&macr;';
			entities['176'] = '&deg;';
			entities['177'] = '&plusmn;';
			entities['178'] = '&sup2;';
			entities['179'] = '&sup3;';
			entities['180'] = '&acute;';
			entities['181'] = '&micro;';
			entities['182'] = '&para;';
			entities['183'] = '&middot;';
			entities['184'] = '&cedil;';
			entities['185'] = '&sup1;';
			entities['186'] = '&ordm;';
			entities['187'] = '&raquo;';
			entities['188'] = '&frac14;';
			entities['189'] = '&frac12;';
			entities['190'] = '&frac34;';
			entities['191'] = '&iquest;';
			entities['192'] = '&Agrave;';
			entities['193'] = '&Aacute;';
			entities['194'] = '&Acirc;';
			entities['195'] = '&Atilde;';
			entities['196'] = '&Auml;';
			entities['197'] = '&Aring;';
			entities['198'] = '&AElig;';
			entities['199'] = '&Ccedil;';
			entities['200'] = '&Egrave;';
			entities['201'] = '&Eacute;';
			entities['202'] = '&Ecirc;';
			entities['203'] = '&Euml;';
			entities['204'] = '&Igrave;';
			entities['205'] = '&Iacute;';
			entities['206'] = '&Icirc;';
			entities['207'] = '&Iuml;';
			entities['208'] = '&ETH;';
			entities['209'] = '&Ntilde;';
			entities['210'] = '&Ograve;';
			entities['211'] = '&Oacute;';
			entities['212'] = '&Ocirc;';
			entities['213'] = '&Otilde;';
			entities['214'] = '&Ouml;';
			entities['215'] = '&times;';
			entities['216'] = '&Oslash;';
			entities['217'] = '&Ugrave;';
			entities['218'] = '&Uacute;';
			entities['219'] = '&Ucirc;';
			entities['220'] = '&Uuml;';
			entities['221'] = '&Yacute;';
			entities['222'] = '&THORN;';
			entities['223'] = '&szlig;';
			entities['224'] = '&agrave;';
			entities['225'] = '&aacute;';
			entities['226'] = '&acirc;';
			entities['227'] = '&atilde;';
			entities['228'] = '&auml;';
			entities['229'] = '&aring;';
			entities['230'] = '&aelig;';
			entities['231'] = '&ccedil;';
			entities['232'] = '&egrave;';
			entities['233'] = '&eacute;';
			entities['234'] = '&ecirc;';
			entities['235'] = '&euml;';
			entities['236'] = '&igrave;';
			entities['237'] = '&iacute;';
			entities['238'] = '&icirc;';
			entities['239'] = '&iuml;';
			entities['240'] = '&eth;';
			entities['241'] = '&ntilde;';
			entities['242'] = '&ograve;';
			entities['243'] = '&oacute;';
			entities['244'] = '&ocirc;';
			entities['245'] = '&otilde;';
			entities['246'] = '&ouml;';
			entities['247'] = '&divide;';
			entities['248'] = '&oslash;';
			entities['249'] = '&ugrave;';
			entities['250'] = '&uacute;';
			entities['251'] = '&ucirc;';
			entities['252'] = '&uuml;';
			entities['253'] = '&yacute;';
			entities['254'] = '&thorn;';
			entities['255'] = '&yuml;';
		}

		if (useQuoteStyle !== 'ENT_NOQUOTES') {
			entities['34'] = '&quot;';
		}
		if (useQuoteStyle === 'ENT_QUOTES') {
			entities['39'] = '&#39;';
		}
		entities['60'] = '&lt;';
		entities['62'] = '&gt;';

		// ascii decimals to real symbols
		for (decimal in entities) {
			if (entities.hasOwnProperty(decimal)) {
				hash_map[String.fromCharCode(decimal)] = entities[decimal];
			}
		}

		return hash_map;
	}

	/**
	 * https://raw.githubusercontent.com/kvz/phpjs/master/functions/strings/strlen.js
	 *
	 * @param string
	 * @returns {*}
	 */
	function strlen(string) {
		//  discuss at: http://phpjs.org/functions/strlen/
		// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// improved by: Sakimori
		// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		//    input by: Kirk Strobeck
		// bugfixed by: Onno Marsman
		//  revised by: Brett Zamir (http://brett-zamir.me)
		//        note: May look like overkill, but in order to be truly faithful to handling all Unicode
		//        note: characters and to this function in PHP which does not count the number of bytes
		//        note: but counts the number of characters, something like this is really necessary.
		//   example 1: strlen('Kevin van Zonneveld');
		//   returns 1: 19
		//   example 2: ini_set('unicode.semantics', 'on');
		//   example 2: strlen('A\ud87e\udc04Z');
		//   returns 2: 3

		var str = string + '';
		var i = 0,
			chr = '',
			lgth = 0;

		var getWholeChar = function(str, i) {
			var code = str.charCodeAt(i);
			var next = '',
				prev = '';
			if (0xD800 <= code && code <= 0xDBFF) {
				// High surrogate (could change last hex to 0xDB7F to treat high private surrogates as single characters)
				if (str.length <= (i + 1)) {
					throw 'High surrogate without following low surrogate';
				}
				next = str.charCodeAt(i + 1);
				if (0xDC00 > next || next > 0xDFFF) {
					throw 'High surrogate without following low surrogate';
				}
				return str.charAt(i) + str.charAt(i + 1);
			} else if (0xDC00 <= code && code <= 0xDFFF) {
				// Low surrogate
				if (i === 0) {
					throw 'Low surrogate without preceding high surrogate';
				}
				prev = str.charCodeAt(i - 1);
				if (0xD800 > prev || prev > 0xDBFF) {
					//(could change last hex to 0xDB7F to treat high private surrogates as single characters)
					throw 'Low surrogate without preceding high surrogate';
				}
				// We can pass over low surrogates now as the second component in a pair which we have already processed
				return false;
			}
			return str.charAt(i);
		};

		for (i = 0, lgth = 0; i < str.length; i++) {
			if ((chr = getWholeChar(str, i)) === false) {
				continue;
			} // Adapt this line at the top of any loop, passing in the whole string and the current iteration and returning a variable to represent the individual character; purpose is to treat the first part of a surrogate pair as the whole character and then ignore the second part
			lgth++;
		}
		return lgth;
	}

	/**
	 * https://raw.githubusercontent.com/kvz/phpjs/master/functions/strings/substr.js
	 *
	 * @param str
	 * @param start
	 * @param len
	 * @returns {*}
	 */
	function substr(str, start, len) {
		//  discuss at: http://phpjs.org/functions/substr/
		//     version: 909.322
		// original by: Martijn Wieringa
		// bugfixed by: T.Wild
		// improved by: Onno Marsman
		// improved by: Brett Zamir (http://brett-zamir.me)
		//  revised by: Theriault
		//        note: Handles rare Unicode characters if 'unicode.semantics' ini (PHP6) is set to 'on'
		//   example 1: substr('abcdef', 0, -1);
		//   returns 1: 'abcde'
		//   example 2: substr(2, 0, -6);
		//   returns 2: false
		//   example 3: ini_set('unicode.semantics',  'on');
		//   example 3: substr('a\uD801\uDC00', 0, -1);
		//   returns 3: 'a'
		//   example 4: ini_set('unicode.semantics',  'on');
		//   example 4: substr('a\uD801\uDC00', 0, 2);
		//   returns 4: 'a\uD801\uDC00'
		//   example 5: ini_set('unicode.semantics',  'on');
		//   example 5: substr('a\uD801\uDC00', -1, 1);
		//   returns 5: '\uD801\uDC00'
		//   example 6: ini_set('unicode.semantics',  'on');
		//   example 6: substr('a\uD801\uDC00z\uD801\uDC00', -3, 2);
		//   returns 6: '\uD801\uDC00z'
		//   example 7: ini_set('unicode.semantics',  'on');
		//   example 7: substr('a\uD801\uDC00z\uD801\uDC00', -3, -1)
		//   returns 7: '\uD801\uDC00z'

		var i = 0,
			allBMP = true,
			es = 0,
			el = 0,
			se = 0,
			ret = '';
		str += '';
		var end = str.length;

		// BEGIN REDUNDANT
		this.php_js = this.php_js || {};
		this.php_js.ini = this.php_js.ini || {};
		// END REDUNDANT
		switch ((this.php_js.ini['unicode.semantics'] && this.php_js.ini['unicode.semantics'].local_value.toLowerCase())) {
			case 'on':
				// Full-blown Unicode including non-Basic-Multilingual-Plane characters
				// strlen()
				for (i = 0; i < str.length; i++) {
					if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {
						allBMP = false;
						break;
					}
				}

				if (!allBMP) {
					if (start < 0) {
						for (i = end - 1, es = (start += end); i >= es; i--) {
							if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {
								start--;
								es--;
							}
						}
					} else {
						var surrogatePairs = /[\uD800-\uDBFF][\uDC00-\uDFFF]/g;
						while ((surrogatePairs.exec(str)) != null) {
							var li = surrogatePairs.lastIndex;
							if (li - 2 < start) {
								start++;
							} else {
								break;
							}
						}
					}

					if (start >= end || start < 0) {
						return false;
					}
					if (len < 0) {
						for (i = end - 1, el = (end += len); i >= el; i--) {
							if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {
								end--;
								el--;
							}
						}
						if (start > end) {
							return false;
						}
						return str.slice(start, end);
					} else {
						se = start + len;
						for (i = start; i < se; i++) {
							ret += str.charAt(i);
							if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {
								// Go one further, since one of the "characters" is part of a surrogate pair
								se++;
							}
						}
						return ret;
					}
					break;
				}
			// Fall-through
			case 'off':
			// assumes there are no non-BMP characters;
			//    if there may be such characters, then it is best to turn it on (critical in true XHTML/XML)
			default:
				if (start < 0) {
					start += end;
				}
				end = typeof len === 'undefined' ? end : (len < 0 ? len + end : len + start);
				// PHP returns false if start does not fall within the string.
				// PHP returns false if the calculated end comes before the calculated start.
				// PHP returns an empty string if start and end are the same.
				// Otherwise, PHP returns the portion of the string from start to end.
				return start >= str.length || start < 0 || start > end ? !1 : str.slice(start, end);
		}
		// Please Netbeans
		return undefined;
	}

	//end of function from php.js

})( jQuery );