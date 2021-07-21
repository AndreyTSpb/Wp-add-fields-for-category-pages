<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 21/07/2021
 * Time: 10:03
 */
?>
<tr class="form-field">
    <th scope="row" valign="top"><label>Заголовок (title)</label></th>
    <td>
        <input type="text" name="fields[title]" value="<?php echo esc_attr( get_term_meta( $term->term_id, 'title', 1 ) ) ?>"><br />
        <p class="description">Не более 60 знаков, включая пробелы, в шапке</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label>Заголовок h1</label></th>
    <td>
        <input type="text" name="fields[h1]" value="<?php echo esc_attr( get_term_meta( $term->term_id, 'h1', 1 ) ) ?>"><br />
        <p class="description">Заголовок страницы который будем отображать в H1</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label>Ключевые слова</label></th>
    <td>
        <input type="text" name="fields[keywords]" value="<?php echo esc_attr( get_term_meta( $term->term_id, 'keywords', 1 ) ) ?>"><br />
        <p class="description">Ключевые слова (keywords) для SEO</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label>Краткое описание (description) </label></th>
    <td>
        <input type="text" name="fields[description]" value="<?php echo esc_attr( get_term_meta( $term->term_id, 'description', 1 ) ) ?>"><br />
        <p class="description">Краткое описание (description)</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label>Текст в категории</label></th>
    <td>
        <?php wp_editor( esc_attr( get_term_meta( $term->term_id, 'text', 1 )), 'wpeditor', array('textarea_name' => 'fields[text]') ); ?>
        <br />
        <p class="description">Текст для категории</p>
    </td>
</tr>