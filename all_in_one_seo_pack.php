<?php
/*
Plugin Name: All In One SEO Pack
Plugin URI: http://semperfiwebdesign.com
Description: Out-of-the-box SEO for your WordPress blog. Features like XML Sitemaps, SEO for custom post types, SEO for blogs or business sites, SEO for ecommerce sites, and much more. Almost 30 million downloads since 2007.
Version: 2.3a
Author: Michael Torbert
Author URI: http://michaeltorbert.com
Text Domain: all-in-one-seo-pack
Domain Path: /i18n/
*/

/*
Copyright (C) 2007-2016 Michael Torbert, semperfiwebdesign.com (michael AT semperfiwebdesign DOT com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * @package All-in-One-SEO-Pack
 * @version 2.3a
 */

if ( ! defined( 'ABSPATH' ) ) return;

define('AIOSEOPPRO', false);

global $aioseop_plugin_name;
$aioseop_plugin_name = 'All in One SEO Pack';
if ( ! defined( 'AIOSEOP_PLUGIN_NAME' ) ) define( 'AIOSEOP_PLUGIN_NAME', $aioseop_plugin_name );
if ( ! defined( 'AIOSEOP_VERSION' ) ) define( 'AIOSEOP_VERSION', '2.3a' );

require_once( 'init.php' );

register_activation_hook( __FILE__, 'aiosp_install' );

function aiosp_install(){
	aioseop_activate();
}