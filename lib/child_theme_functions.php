<?php
/*
 * CHILD THEME FUNCTIONS
 *
 * Child Theme Name: OBM-Genesis-Child
 * Author: Orange Blossom Media
 * Url: https://orangeblossommedia.com/
 *
 * Add any functions here that you wish to use sitewide.
 */

// Change the footer credits
function obm_footer_cred( $obm_ft ) {

	// Footer Menu
    $obm_footer_menu = wp_nav_menu( array(
    	'theme_location' 	=> 'secondary',
    	'echo' 				=> false,
    	'container' 		=> '',
    	'fallback_cb' 		=> false,
    	'menu_class'		=> 'secondary-navigation',
    	));


    // Copyright Info
    $obm_copy = '<span class="copyright">&copy; ' . date("Y") .' OBM-Genesis-Child</span>';

	$footer = $obm_footer_menu;
	$footer .= '<div class="copy">' . $obm_copy . '</div>';

    return $footer;

}

