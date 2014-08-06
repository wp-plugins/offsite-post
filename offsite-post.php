<?php
/*
Plugin Name: Offsite Post
Plugin URI: https://github.com/pathawks/offsite-post
Description: Redirects Links offsite
Author: Pat Hawks
Author URI: http://pathawks.com
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Version: 1.00

  Copyright 2014 Pat Hawks  (email : pat@pathawks.com)

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

function dirtysuds_link_rate($links,$file) {
		if (plugin_basename(__FILE__) == $file) {
			$links[] = '<a href="http://wordpress.org/extend/plugins/offsite-post/">Rate this plugin</a>';
		}
	return $links;
}
add_filter('plugin_row_meta', 'dirtysuds_link_rate',10,2);
add_filter('single_template', 'dirtysuds_link_do_redirect');
add_filter('post_link', 'dirtysuds_link_filter_permalink',1,2);
