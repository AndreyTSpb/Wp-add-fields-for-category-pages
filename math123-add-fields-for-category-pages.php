<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 21/07/2021
 * Time: 09:42
 */
/**
 * Plugin Name: Math123 add fields for category pages
 * Description: Плагин для добавления описания к категориям.
 * Plugin URI: https://
 * Author: Andrey Tynyany
 * Version: 1.0.1
 * Author URI: http://tynyany.ru
 *
 * Text Domain: Math123 add fields for category pages
 *
 * @package Math123 add fields for category pages
 */

defined('ABSPATH') or die('No script kiddies please!');

define( 'WPM123AFFCP_VERSION', '1.0.1' );

define( 'WPM123AFFCP_REQUIRED_WP_VERSION', '5.5' );

define( 'WPM123AFFCP_PLUGIN', __FILE__ );

define( 'WPM123AFFCP_PLUGIN_BASENAME', plugin_basename( WPM123AFFCP_PLUGIN ) );

define( 'WPM123AFFCP_PLUGIN_NAME', trim( dirname( WPM123AFFCP_PLUGIN_BASENAME ), '/' ) );

define( 'WPM123AFFCP_PLUGIN_DIR', untrailingslashit( dirname( WPM123AFFCP_PLUGIN ) ) );

define( 'WPM123AFFCP_PLUGIN_URL',
    untrailingslashit( plugins_url( '', WPM123AFFCP_PLUGIN ) )
);