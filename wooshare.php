<?php
/*
Plugin Name: WooShare
Plugin URI: http://henasraf.com/wooshare
Description: WooShare is a plugin that lets you add share links to the bottom of each of your posts. You can customize every link and add custom links easily!
Version: 1.1.0.1
Author: Hen Asraf
Author URI: http://henasraf.com

	Copyright 2009  Hen Asraf  (email : henasraf@wosaic.net)

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
add_action('admin_menu', 'wooshare_settings_page');
add_action('wp_head', 'wooshare_stylesheet');
add_action('the_ID', 'wooshare_get_title');
add_filter('the_content', 'show_wooshare_links');
register_activation_hook(__FILE__, 'wooshare_install');

$wooshare_post_info = Array();

function wooshare_install()
{
	global $wpdb;
	if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}wooshare_links'") != $table_name) { // if table doesn't exits	
		$query = "CREATE TABLE `{$wpdb->prefix}wooshare_links` (
					id int(11) NOT NULL AUTO_INCREMENT,
					name tinytext NOT NULL,
					text tinytext,
					image tinytext,
					title tinytext,
					url tinytext NOT NULL,
					PRIMARY KEY id (id)
				);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($query);
		
		$insert[] =     "'twitter',
						'Tweet This Post',
						'http://twitter.com/favicon.ico',
						'Click here to share this post on Twitter',
						'http://twitter.com/?status=&quot;%title%&quot; - %url'";
		
		$insert[] =     "'facebook',
						'Share on Facebook',
						'http://www.facebook.com/favicon.ico',
						'Click here to share this post on Facebook',
						'http://www.facebook.com/sharer.php?u=%url%'";
					
		$insert[] =     "'delicious',
						'Share on Delicious',
						'http://www.delicious.com/favicon.ico',
						'Click here to share this post on Delicious',
						'http://www.delicious.com/post?url=%url%'";
						
		$insert[] =     "'digg',
						'Digg This Post',
						'http://www.digg.com/favicon.ico',
						'Click here to share this post on Digg',
						'http://www.digg.com/submit?url=%url%'";
		
		$query = "INSERT INTO `{$wpdb->prefix}wooshare_links` (`name`, `text`, `image`, `title`, `url`)
								VALUES ";
		foreach ($insert as $item) {
			if (!$i)
				$query .= ",($item)";
			else {
				$query .= "($item)";
				$i = true;
			}
		}
	
		$wpdb->query($insert);
	}
	if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}wooshare_options'") != $table_name) {// if table doesn't exits	
		// options
		$sql = "CREATE TABLE `{$wpdb->prefix}wooshare_options` (
			`id` int(11) AUTO_INCREMENT NOT NULL,
			`name` varchar(255) NOT NULL,
			`value` text,
			PRIMARY KEY id (id)
		)";
		
		$insert = null;
		
		$insert[] = "'bitly_name', 'Bit.ly Login', ''";
		$insert[] = "'bitly_api', 'Bit.ly API key', ''";
		$insert[] = "'css', 'Button CSS Styles',
'div.wooshare_links {
margin:3px;overflow:auto;
}
a.wooshare_link {
background:#ffffff;
border:1px solid #dddddd;
color:#193441;
font-size:11px;
padding:3px 5px;
margin:3px;
text-decoration:none;
float:left;
width:16px;
}
a.wooshare_link:hover {
background:#E2E4E9;
color:#ffffff;
text-decoration:none;
}
img.wooshare_icon { 
margin:0;
vertical-align:middle;
}'";
		
		$query = "INSERT INTO `{$wpdb->prefix}wooshare_options` (`name`,`friendly_name`,`value`)
								VALUES ";
		foreach ($insert as $item) {
			if (!$i)
				$query .= ",($item)";
			else {
				$query .= "($item)";
				$i = true;
			}
		}
		
		$query = "ALTER TABLE  `{$wpdb->prefix}posts` ADD `bitly_url` varchar(255) NULL";
		
		$wpdb->query($query);
	}
}


function show_wooshare_links($content)
{
	global $wpdb, $post;
	$table_name = $wpdb->prefix . 'wooshare_links';
	$query = "SELECT `name`,`url`,`image`,`text`,`title` FROM `$table_name`";
	$buttons = $wpdb->get_results($query, OBJECT);
	
	$link = get_permalink($post->ID);
	$shortlink_original =
		$wpdb->get_var("SELECT `ID`,`bitly_url` FROM `{$wpdb->prefix}posts` WHERE `ID` = '{$post->ID}'",1);
	$shortlink = $shortlink_original ? $shortlink_original : wooshare_shorten_url($link,$post->ID);
	$shortlink = $shortlink ? $shortlink : $link;
	$insert = '';
	foreach ($buttons as $button)
		$insert .= get_wooshare_link($button,$shortlink);
	return $content . '<div class="wooshare_links">'.$insert.'</div>';
}
function get_wooshare_option($name) {
	global $wpdb;
	$option = $wpdb->get_var("SELECT `value` FROM `{$wpdb->prefix}wooshare_options` WHERE `name` = '{$name}'");
	return $option;
}
function wooshare_shorten_url($url,$post_id) {
	global $wpdb;
	$login = get_wooshare_option('bitly_name');
	$api   = get_wooshare_option('bitly_api');
	$file = 'http://api.bit.ly/shorten?version=2.0.1&longUrl='.$url.'&login='.$login.'&apiKey='.$api;
	$json = file_get_contents($file);
	$bitly = json_decode($json,true);
	$shorturl = $bitly['results'][$url]['shortUrl'];
	$query = "UPDATE `{$wpdb->prefix}posts` SET `bitly_url` = '$shorturl' WHERE `ID` = '$post_id'";
	$wpdb->query($query);
	return $shorturl;
	
}
function get_wooshare_link($button,$url)
{
		global $wpdb,$post;
		if ($post) {
			$find = Array(
				"\\",
				'%title%',
				'%url%',
				'%date%'
			);
			$replace = Array(
				"",
				$post->post_title,
				$url,
				$post->post_date
			);
		}
		else {
			$find = "\\"; $replace = "";
		}
		$button->url = htmlentities(str_ireplace($find, $replace, $button->url));
		$text = '<a href="' . $button->url . '" class="wooshare_link wooshare_' . $button->name . '" title="' . $button->title . '" target="_blank"><img src="' . $button->image . '" alt="" class="wooshare_icon wooshare_' . $button->name . '_icon" /> ' . $button->text . '</a>';
		return $text;
}
function wooshare_settings_page()
{
	add_submenu_page('plugins.php', 'WooShare Settings', 'WooShare Settings', 'administrator', 'wooshare/options.php');
}
function wooshare_options()
{
	include('options.php');
}
function wooshare_stylesheet()
{
	global $wpdb;
	echo '
<style type="text/css">
';
include($woodir.'style.php');
echo '
</style>
	';
}
?>