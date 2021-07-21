 #Плагин для добавления кастомных полей к категориям.
 --
 > 1) Title - заголовок для шапки
 > 2) H1 - заголовок уровня H1 для страницы
 > 3) Description - краткое описание (дублирует описание из основных полей)
 > 4) Keywords - ключевые слова для СЕО
 > 5) Text - текст для страницы катекогий, включен wp_editor, есть возможность добавлять изображения и другие медиа файлы
 
 ## Использование на странице вывода категории
 
 ### Вывод заголовка 
 Для того что бы WP и  SEO плагины брали вкачестве заголовка наш Title, надо добавить функцию фильтр, если наш заголовок не указан то берет штатный:
 ````
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
 ````
  ### Вывод заголовка на странице
  Созданна отдельная функция для добавления заголовка первого уровня the_wpaffcp_h1
  ````
   <h1><?php the_wpaffcp_h1();?></h1>
  ````
  
  ### Вывод описания для категории
  Для вывода описания добавлена функция wpaffcp_description(), отрабатывает автоматом при активации плагина.
  
  ### Вывод ключевых слов
  
  
  ### Вывод текста на  странице категорий
  Для отображения произвольного текста для катего добавлена функция the_wpaffcp_cat_text()
  ````
    <div class="pages-desc">
        <?php the_wpaffcp_cat_text();?>
    </div>
  ````