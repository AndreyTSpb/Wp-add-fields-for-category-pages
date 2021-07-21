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
 * Plugin URI: https://github.com/AndreyTSpb/Wp-add-fields-for-category-pages
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

/**
 * наш заголовк
 * @param $term_name
 * @return mixed
 */
function wpm123affcp_filter_single_cat_title($term_name) {

    $terms = get_category( get_query_var('cat'));
    $cat_id = $terms->cat_ID;
    $term_name = get_term_meta ($cat_id, 'title', true);

    /**
     * если заголовок не заполнен
     */
    if(empty($term_name)){
        $terms = get_category( get_query_var( 'cat' ));
        $cat_id = $terms->cat_ID;
        $term_name = get_cat_name($cat_id);
    }

    return $term_name;
}

add_filter('single_cat_title', 'wpm123affcp_filter_single_cat_title', 10, 1 );

/**
 * Получаем стандартный заголовок категории
 */
function get_cat_caption() {
    $terms = get_category( get_query_var( 'cat' ));
    $cat_id = $terms->cat_ID;
    $caption = get_cat_name($cat_id);
    return $caption;
}

/**
 * Получаем наш заголовок страницы H1
 */
function the_wpaffcp_h1() {
    $terms = get_category( get_query_var( 'cat' ));
    $cat_id = $terms->cat_ID;

    $name_cat = get_term_meta ( $cat_id, 'h1', true );
    if(empty($name_cat)){
        $name_cat = get_cat_caption();
    }
    echo $name_cat;
}

function wpaffcp_description(){
    if(is_category()){
        $terms = get_category( get_query_var( 'cat' ));
        $category_id = $terms->cat_ID;

        $description = get_term_meta ( $category_id, 'description', true );
        if(!empty($description)){
            $meta = '<meta name="description"  content="'.$description.'" />'."\n";
        }
        else {
            $description = wp_filter_nohtml_kses(substr(category_description(), 0, 280));
            $meta = '<meta name="description"  content="'.$description.'" />'."\n";
        }
        echo $meta;
    }
}
add_action('wp_head', 'wpaffcp_description', 1, 1);

/**
 * Вывод ключевиков для категории
 * @param $keywords
 */
function wpaffcp_keywords(){
    if(is_category()){

        $terms = get_category( get_query_var( 'cat' ));
        $cat_id = $terms->cat_ID;

        $keywords = get_term_meta ( $cat_id, 'keywords', true );
        echo '<meta name="keywords" content="'.$keywords.'">'."\n";
    }
}

add_action('wp_head', 'wpaffcp_keywords', 1, 1);

/**
 * Вывод текста для категории
 */
function the_wpaffcp_cat_text(){
    if(is_category()){

        $terms = get_category( get_query_var( 'cat' ));
        $cat_id = $terms->cat_ID;

        $text = get_term_meta ( $cat_id, 'text', true );
        echo $text;
    }
}