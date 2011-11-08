<?php
/*
Plugin Name: Links to Web Proxy
Plugin URI: http://trabaria.com/plugins/links-to-web-proxy/
Description: Need to run your links through a proxy? This plugin provides a fast, efficient proxy which also fixes most encoding problems from the original link content.
Author: Michael Marus
Version: 0.1
Author URI: http://trabaria.com/
License: GPL2
*/
define('WP_BASE_PROXY',  str_replace(get_site_url()."/","",plugins_url( '/px.php' , __FILE__ )));
function links_2proxy_init()
{
global $wp_rewrite;
add_rewrite_rule( '(.*)links_2proxy/([0-9]+)/([0-1])/(.*$)', WP_BASE_PROXY.'?link_id=$2&clean=$3&qs=$4', 'top' );
$wp_rewrite->flush_rules();
}
add_action( 'init', 'links_2proxy_init' );
/* Add the settings menu page */
add_action('admin_menu', 'trab_links_2proxysettings_menu');

function trab_links_2proxysettings_menu(){
	add_options_page('Links to Web Proxy Settings', 'Links to Web Proxy Settings', 'manage_options', 'trab_set_links_2proxy', 'trab_links_2proxyoptions_page');
}

function trab_links_2proxyoptions_page(){
	echo "<div>";
	echo "<h2>Links to Web Proxy Settings</h2>";
	do_settings_sections('trab_set_links_2proxy');
	echo '</div>';
}

/* Fill the Menu page with content */

function trab_links_2proxyinit(){
	register_setting( 'trab_links_2proxyoptions', 'trab_links_2proxyoptions', 'trab_links_2proxyoptions_validate' );
	add_settings_section('the_trab_links_2proxy', 'Trabaria Settings', 'trab_links_2proxydetails_text', 'trab_set_links_2proxy');
	add_settings_field('trab_links_2proxyfields', 'Allowed Proxy Links', 'trab_links_2proxyfields_display', 'trab_set_links_2proxy', 'the_trab_links_2proxy');
}
add_action('admin_init', 'trab_links_2proxyinit');

function trab_links_2proxyfields_display(){
$args = array(
    'orderby'        => 'name', 
    'order'          => 'ASC',
    'limit'          => -1, 
    'category_name'  => 'allow-proxy',
    'hide_invisible' => 0
    );
    $allowedProxies = get_bookmarks( $args );
    echo'
<table cellspacing="0" class="wp-list-table widefat fixed media">
	<thead>
	<tr>
	<th class="manage-column column-tTitle" id="tTitle" scope="col"><span>Link Name</span></th>
	<th class="manage-column column-tURL" id="tURL" scope="col"><span>Proxy Link</span></th>
	</tr>
	</thead>
	<tbody id="the-list">    
';

	foreach ( $allowedProxies as $allowedProxy ) {
        echo'
                <tr valign="top" class="alternate author-self status-inherit" id="post-'.$tmpl->ID.'">
                <td class="title column-tTitle">'.$allowedProxy->link_name.'</td>
                <td class="title column-tURL">
                <a target="_blank" href="'.site_url('/links_2proxy/'.$allowedProxy->link_id."/1/", 'http').'">Clean ASCII Proxy Link</a> | 
                <a target="_blank" href="'.site_url('/links_2proxy/'.$allowedProxy->link_id."/0/", 'http').'">Basic Proxy Link</a>
</td>
                </tr> 
        ';
	}
echo'
	</tbody>
</table>    
';
}

function trab_links_2proxydetails_text(){
	echo "<p>Links to Web Proxy Settings</p>";
}

function trab_links_2proxyoptions_validate($input){
	return $input;
}

function uninstall_trab_links_2proxy(){
    	global $wp_rewrite;
	$wp_rewrite->flush_rules();        
}
register_activation_hook( __FILE__, 'links_2proxy_init');
register_deactivation_hook(__FILE__, 'uninstall_trab_links_2proxy');
?>