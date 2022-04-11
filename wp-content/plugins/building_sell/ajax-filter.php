<?php
/**
 * @package Ajax Filter
 * @version 1.1
 */
/*
Plugin Name: Ajax Filter

Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Matt Mullenweg
Version: 1.1
Author URI: http://ma.tt/
*/
function true_loadmore_scripts()
{
	wp_enqueue_script ("jquery"); // скорее всего он уже будет подключен, это на всякий случай
 	wp_enqueue_script ("true_loadmore",  plugin_dir_url (__FILE__)."js/loadmore.js", array ("jquery"), 1.1, true);
}
function true_load_posts ()
{
  	$custom_query_args = unserialize (stripslashes ($_POST ["query"]));
	$custom_query_args ["paged"] = $_POST ["page"] + 1; // следующая страница
	$custom_query_args ["post_status"] = "publish";
	$query = new WP_Query ($custom_query_args);
	global $paged;
	if (($query->max_num_pages > 1) && ($custom_query_args ["cat"] != 71) && ($custom_query_args ["cat"] != 287)) :		
		?>
		<script>									
			current_page = <?php echo ($custom_query_args ["paged"])? $custom_query_args ["paged"] : 1; ?>;
		</script>
		<?php
		endif;
	if ($query->have_posts ()) :
		while ($query->have_posts ()):			
			$query->the_post ();
			switch ($custom_query_args ["cat"])
			{
				case get_cat_ID ("Новости"): case get_cat_ID ("Главные новости"):				
					global $kit_news;
					$post_number = number_of_posts_on_archive ($query);
					$ppp = $post_number->query_vars ["posts_per_page"];
					$news_curent_number = $_POST ["page"] * $ppp + $kit_news;
					if(($kit_news % 3) == 0)
					{
						echo '<div class="kit_news_grid-container';
						if ($count - $news_curent_number < 4)
							echo " mod-3".($count % 3);
						echo '" id="kit_news_grid-container'.($news_curent_number / 3 + 1).'">';
					}
					get_template_part ("template-parts/content/content", "news");
					if ($kit_news % 3 == 0)
					{
						echo '</div>
							<div class="kit_news_prev" onClick="prev_click (this)">&lt;</div>
							<div class="kit_news_next" onClick="next_click (this)">&gt;</div>';
					}
					elseif ($count == $news_curent_number + 1)
						echo "</div>";
					break;
				case get_cat_ID ("Общая"): case get_cat_ID ("Расписание"): case get_cat_ID ("Активные вакансии"): case get_cat_ID ("Работы учеников"): case get_cat_ID ("Исключающая"): case get_cat_ID ("Учебные материалы"):
					get_template_part ("template-parts/content/content");
					break;
				case get_cat_ID ("Вопросы и ответы"):
					get_template_part ("template-parts/content/content", "faq");
					break;
				default:
					get_template_part ("template-parts/content/content-courses");					
					break;
			}
		endwhile;	
		wp_reset_postdata ();
	endif;
	die ();
} 
 
add_action ("wp_ajax_loadmore", "true_load_posts");
add_action ("wp_ajax_nopriv_loadmore", "true_load_posts"); 
add_action ("wp_enqueue_scripts", "true_loadmore_scripts");
wp_enqueue_script ("ajax_filter", plugin_dir_url (__FILE__ )."js/ajax-filter.js", array ("jquery"), 1.1, true);
wp_enqueue_style ("ajax_filter_css", plugin_dir_url (__FILE__ )."css/ajax-filter.css");
add_action ("wp_ajax_myfilter", "ajax_filter_function"); // wp_ajax_{ACTION HERE} 
add_action ("wp_ajax_nopriv_myfilter", "ajax_filter_function"); 

function ajax_filter_function ()
{
	global $wp_query, $paged, $post, $wpdb;
	if ($_POST ["page-type"] == "courses")
	{
		$custom_query_args = array
		(
			"tax_query" => array (),
			"cat" => ",-".get_cat_ID ("Общая").",-".get_cat_ID ("Новости").",-".get_cat_ID ("Главные новости").",-".get_cat_ID ("Работы учеников").",-".get_cat_ID ("Расписание").",-".get_cat_ID ("Активные вакансии").",-".get_cat_ID ("Вопросы и ответы").",-".get_cat_ID ("Исключающая").",-".get_cat_ID ("Учебные материалы"),
			"post_type" => "post", 
			"paged" => $paged,
			"post_status" => "publish",
			"ignore_sticky_posts" => true,
			"meta_key"  => "Число просмотров",
			"orderby" => ["meta_value_num" => "DESC"],
		);
		$query = new WP_Query ($custom_query_args);
		$wp_query = NULL;
		$wp_query = $query;
		while (have_posts ())
		{
			the_post ();
			$categories = get_the_category ();
			if ((count ($categories) == 1) && ($categories [0]->cat_name == "Общая"))
				$only_general [] = $post->ID;
		}
		$custom_query_args ["post__not_in"] = $only_general;
		$query->set ("posts_per_page", 12);		
		$query = new WP_Query ($custom_query_args);
		$wp_query = NULL;
		$wp_query = $query;
	}
	else		
	{
		$custom_query_args = array
		(
			"tax_query" => array (),
			"cat" => get_cat_ID ("Курсы"),
			"post_type" => "post", 
			"paged" => $paged,
			"post_status" => "publish",
			"ignore_sticky_posts" => true,
			"meta_key"  => "Число просмотров",
			"orderby" => ["meta_value_num" => "DESC"],
			"posts_per_page" => 12,	
		);
	}
	if (get_query_var ("paged"))
		$paged = get_query_var ("paged");
	elseif (get_query_var ("page")) // 'page' вместо 'paged' на статической главной странице
		$paged = get_query_var ("page");
	else
		$paged = 1;
	$custom_query_args ["paged"] = $paged;
	if ($_POST ["schoolchildren"] != "")
		array_push ($custom_query_args ["tax_query"],
			array
			(
				"taxonomy" => "category",
				"field" => "id",
				"terms" => $_POST ["schoolchildren"]
			)
		);
	if ($_POST ["sort"] == "voices")
	{
		$sqlstr = "SELECT post_id, count(*) as c FROM wp_ulike GROUP BY ip ORDER BY c DESC";
		$query = new WP_Query ($custom_query_args);
		$wp_query = NULL;
		$wp_query = $query;
		$course_posts = array ();
		$i = 0;
		while ($wp_query->have_posts ())
		{
			$wp_query->the_post ();
			$course_posts [$i] = $post->ID;
			$i++;
		}
		$voices = $wpdb->get_results ($sqlstr, ARRAY_A);
		$voices_posts = array ();
		$i = 0;
		foreach ($voices as $voice)
		{
			$voices_post [$i] = $voice ["post_id"];
			$i++;
		}
		echo " vp ";
		print_r ($voices_post);
		$sqlstr = "SELECT ID FROM wp_posts where ID NOT IN (SELECT post_id FROM wp_ulike) AND ID NOT IN (SELECT object_id from wp_term_relationships where term_taxonomy_id IN (SELECT term_id FROM wp_terms WHERE name IN('Общая','Новости','Главные новости','Работы учеников','Расписание','Активные вакансии','Вопросы и ответы','Исключающая','Учебные материалы'))) ";
		$voices1 = $wpdb->get_results ($sqlstr, ARRAY_A);
		foreach ($voices1 as $voice1)
		{
			$voices_post [$i] = $voice1 ["ID"];
			$i++;
		}
		//echo " vp ";
		//print_r ($voices_post);
		$most_voices = array_count_values ($voices_post);
		var_dump (array_diff ($course_posts, $voices_post));
		$posts_all = array_merge ($voices_post, $course_posts);
		$custom_query_args ["post__in"] = $posts_all;
		$custom_query_args ["orderby"] = "post__in";
	}
	else
	{
		$custom_query_args ["orderby"] = ["meta_value_num" => "DESC"];
		$custom_query_args ["meta_key"] = "Число просмотров";
	}
	if ($_POST ["directions"] != "")
		array_push ($custom_query_args ["tax_query"],
			array
			(
				"taxonomy" => "category",
				"field" => "id",
				"terms" => $_POST ["directions"]
			)
		);
	if ($_POST ["tranings"] != "")
		array_push ($custom_query_args ["tax_query"],
			array
			(
				"taxonomy" => "category",
				"field" => "id",
				"terms" => $_POST ["tranings"]
			)
		);
	if ($_POST ["students"] != "")
		array_push ($custom_query_args ["tax_query"],
			array
			(
				"taxonomy" => "category",
				"field" => "id",
				"terms" => get_cat_ID ("Студенты")
			)
		);
	if ($_POST ["summer_camp"] != "")
		array_push ($custom_query_args ["tax_query"],
			array
			(
				"taxonomy" => "category",
				"field" => "id",
				"terms" => get_cat_ID ("Летний лагерь")
			)
		);
	if ($_POST ["admission_is_going"] != "")
		array_push ($custom_query_args ["tax_query"],
			array
			(
				"taxonomy" => "category",
				"field" => "id",
				"terms" => get_cat_ID ("Идёт набор")
			)
		);
	
		$pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache'); 
		if ($_POST ["reset"] != "" || $pageRefreshed == 1)
	{
		if ($_POST ["page-type"] == "courses")
		{
			$custom_query_args = array
			(
				"cat" => ",-".get_cat_ID ("Общая").",-".get_cat_ID ("Новости").",-".get_cat_ID ("Главные новости").",-".get_cat_ID ("Работы учеников").",-".get_cat_ID ("Расписание").",-".get_cat_ID ("Активные вакансии").",-".get_cat_ID ("Вопросы и ответы").",-".get_cat_ID ("Исключающая").",-".get_cat_ID ("Учебные материалы"),
				"post_type" => "post", 
				"paged" => $paged,
				"post_status" => "publish",
				"ignore_sticky_posts" => true,
				"meta_key"  => "Число просмотров",
				"orderby" => ["meta_value_num" => "DESC"],
			);
			$custom_query = new WP_Query ($custom_query_args);
			$wp_query = NULL;
			$wp_query = $custom_query;
			while (have_posts ())
			{
				the_post ();
				$categories = get_the_category ();
				if ((count ($categories) == 1) && ($categories [0]->cat_name == "Общая"))
					$only_general [] = $post->ID;
			}
			$custom_query_args ["post__not_in"] = $only_general;
			$query = new WP_Query ($custom_query_args);
			$query->set ("posts_per_page", 12);
			$wp_query = NULL;
			$wp_query = $query;
		}
		else		
		{
			$custom_query_args = array
			(
				"tax_query" => array (),
				"cat" => get_cat_ID ("Курсы"),
				"post_type" => "post", 
				"paged" => $paged,
				"post_status" => "publish",
				"ignore_sticky_posts" => true,
				"meta_key"  => "Число просмотров",
				"orderby" => ["meta_value_num" => "DESC"],
				"posts_per_page" => 12,
			);
		}
	}
	$query = new WP_Query ($custom_query_args);
	$wp_query = NULL;
	$wp_query = $query;
 	if ($wp_query->have_posts ()) :
		echo "<article class='page type-page status-publish hentry entry'><div class='entry-content'><ul class='lcp_catlist' id='lcpc'>";
		while ($wp_query->have_posts ()):
			$wp_query->the_post ();
			get_template_part ("template-parts/content/content-courses");
		endwhile;
		echo "</ul></div></article>";
		wp_reset_postdata ();
	?>
	<script>
		document.getElementById ("true_loadmore").style.display = "none";
	</script>
	<?php 
		if ($wp_query->max_num_pages > 1) :
	?>
		<script>
			document.getElementById ("true_loadmore").style.display = "block";
			ajaxurl = '<?php echo site_url() ?>/wp-admin/admin-ajax.php';
			true_posts = '<?php echo serialize ($wp_query->query_vars); ?>';
			current_page = <?php echo (get_query_var ("paged")) ? get_query_var ("paged") : 1; ?>;
			max_pages = '<?php echo $wp_query->max_num_pages; ?>';
		</script>
	<?php 
		endif;
		else :
			echo "Курсов, удовлетвоворяющих данному критерию, не найдено";
		endif;
		die ();
}