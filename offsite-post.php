<?php
/*
Plugin Name: Offsite Post
Plugin URI: http://dirtysuds.com
Description: Redirects Links offsite
Author: Pat Hawks
Version: 1.00
Author URI: http://www.pathawks.com

Updates:
1.00.20110226 - First Version

  Copyright 2011 Pat Hawks  (email : pat@pathawks.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function dirtysuds_link_get_redirect( $permalink, $post ) {
	preg_match('!https?://[\S]+!', $post->post_content, $newlink);
	if (isset($newlink[0]))
		return $newlink[0];
	else
		return $permalink;
}

function dirtysuds_link_filter_permalink( $permalink, $post ) {
	if (has_post_format('link', $post))
		return dirtysuds_link_get_redirect( $permalink, $post );
	else
		return $permalink;
}

function dirtysuds_link_do_redirect($single_template) {
	global $post;
	if (has_post_format('link', $post)) {
		$newlink = dirtysuds_link_get_redirect( $_SERVER['REQUEST_URI'], $post );
		if ( $newlink != $_SERVER['REQUEST_URI'])
			header( 'Location: ' . $newlink ) ;
		else
			return $single_template;
	} else
		return $single_template;
}

add_filter('single_template', 'dirtysuds_link_do_redirect');
add_filter('post_link', 'dirtysuds_link_filter_permalink',1,2);