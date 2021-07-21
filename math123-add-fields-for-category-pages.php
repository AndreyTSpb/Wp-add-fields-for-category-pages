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
$taxname = 'category';

// Поля при добавлении элемента таксономии
add_action("{$taxname}_add_form_fields", 'wpm123affcp_add');

// Поля при редактировании элемента таксономии
add_action("{$taxname}_edit_form_fields", 'wpm123affcp_edit');

// Сохранение при добавлении элемента таксономии
add_action("create_{$taxname}", 'wpm123affcp_save_custom_taxonomy_meta');

// Сохранение при редактировании элемента таксономии
add_action("edited_{$taxname}", 'wpm123affcp_save_custom_taxonomy_meta');

function wpm123affcp_edit($term){
    echo wpm123affcp_get_html($term);
}

function wpm123affcp_add( $taxonomy_slug ){
    echo wpm123affcp_get_html($taxonomy_slug);
}

/**
 * Вывод шаблона
 */
function wpm123affcp_get_html($term){
    ob_start();
    include WPM123AFFCP_PLUGIN_DIR."/templates/template-views.php";
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function wpm123affcp_save_custom_taxonomy_meta( $term_id ) {

    if ( ! isset($_POST['fields']) ) return;

    if ( ! current_user_can('edit_term', $term_id) ) return;

    if (
        ! wp_verify_nonce( $_POST['_wpnonce'], "update-tag_$term_id" ) && // wp_nonce_field( 'update-tag_' . $tag_ID );
        ! wp_verify_nonce( $_POST['_wpnonce_add-tag'], "add-tag" ) // wp_nonce_field('add-tag', '_wpnonce_add-tag');
    ) return;

    // Все ОК! Теперь, нужно сохранить/удалить данные
    $extra = wp_unslash($_POST['fields']);

    foreach( $extra as $key => $val ){
        // проверка ключа
        $_key = sanitize_key( $key );
        if( $_key !== $key ) wp_die( 'bad key'. esc_html($key) );

        // очистка
        if( $_key === 'tag_posts_shortcode_links' )
            $val = sanitize_textarea_field( strip_tags($val) );
        else
            $val = sanitize_text_field( $val );

        // сохранение
        if( ! $val )
            delete_term_meta( $term_id, $_key );
        else
            update_term_meta( $term_id, $_key, $val );
    }

    return $term_id;
}