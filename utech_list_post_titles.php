<?php
/*
Plugin Name: Utech List Post Titles
Plugin URI: http://www.utechworld.com/projects/list-posts-for-wp/
Description: List your latest posts by category using simple shortcode. E.g [utech_latest_posts category='news']
Version: 2.0
Author: Meini based on work by Sachethan G Reddy
Author URI: http://www.utechworld.com
License: GPL2
*/

/*  Copyright 2010  Meini  (http://www.utechworld.com)

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307USA
*/

//[latest_posts]
add_shortcode('utech_latest_posts', 'utech_sc_get_latest_posts');

function utech_sc_get_latest_posts($atts){
	extract(shortcode_atts(array(
		'category' => '',
		'limit' => 5,
		), $atts));
		return utech_get_latest_posts($category, $limit);
}

function utech_get_latest_posts($category, $limit=5){
	$start_tag='<li>'; $end_tag = '</li>';
	global $wpdb;
	$category = str_replace(",", "','", $category);
	$category = "'" . $category . "'";
	$query = "select wtr.object_id , wp.id, wp.post_title, wt.name, wp.post_modified, wp.post_date, wp.post_name from " . $wpdb->prefix."posts wp, " . $wpdb->prefix."term_relationships wtr, " . $wpdb->prefix."term_taxonomy wtt, " . $wpdb->prefix."terms wt where wp.id = wtr.object_id and wtr.term_taxonomy_id = wtt.term_taxonomy_id and wtt.term_id = wt.term_id and upper(wt.name) in (" . strtoupper($category) . ") AND wtt.taxonomy like 'category' AND wp.post_status = 'publish' AND wp.post_type like 'post' AND wp.post_password ='' ORDER BY wp.post_date DESC LIMIT ". $limit . ";" ;
	$latest_posts = $wpdb->get_results($query);
	$result = '<div class="utech-latest-posts"><ul>';
	if ($latest_posts) {
		foreach ($latest_posts as $latest_post) {
			$post_title = $latest_post->post_title;
			$permaLink = get_permalink($latest_post->id);
			$html = $start_tag . "<a href=\"" .  $permaLink . "\" title=\"" . $post_title . "\" rel='bookmark'>" .$post_title  . " </a>" . $end_tag;
			$result .= $html; 
		}
	}
	$result .= "</ul></div>";
	return $result;
	
}
?>