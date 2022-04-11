<?php
/*
	Plugin Name: Building sell
	Plugin URI: http://страница_с_описанием_плагина_и_его_обновлений
	Description: Плагин выводит список квартир и домов с параметрами, заполненными через плагин Simple Fields.
	Version: 1.0
	Author: arhibober
	Author URI: http://bobrydrova.zzz.com
*/
?>

<?php
/*  Copyright 2018  arhibober (email: arhibober@gmail.com)

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
?>

<?php
	function pagination_scripts()
	{
		wp_enqueue_script ("jquery"); // скорее всего он уже будет подключен, это на всякий случай
		wp_enqueue_script ("pagination",  plugin_dir_url (__FILE__)."js/ajax-pagination.js", array ("jquery"), 1.1, true);
	}
	add_action ("wp_enqueue_scripts", "pagination_scripts");
	if ($other_pages = locate_template ("other_pages.php"))
		// locate_template() вернет путь до файла, если дочер. или родит. тема имеет такой файл
		load_template ($overridden_template);
	function test_plugin_setup_menu ()
	{
        add_menu_page ("Building Sell Page", "Test Sell", "manage_options", "building_sell", "test_init");
	}
	
	function test_init ()
	{
		$i = 0;
		$building_content = $_GET ["page_b"]."<div id='apartments'>
			<form>
				<table>
					<tr>
						<th>
							№ п/п
						</th>
						<th>
							Адрес
						</th>
						<th>
							Координаты местоположения
						</th>
						<th>
							Количество этажей
						</th>
						<th>
							Тип строения
						</th>
						<th>
							Изображение дома
						</th>
						<th>
							Площадь
						</th>
						<th>
							Количество комнат
						</th>
						<th>
							Балкон
						</th>
						<th>
							Санузел
						</th>
						<th>
							Изображение квартиры
						</th>
						</tr>";
					global $wpdb;
					global $user_ID;
					$sqlstr = "SELECT post_id FROM wp_postmeta WHERE meta_key='_simple_fields_selected_connector' LIMIT 1, 10";
					$posts = $wpdb->get_results ($sqlstr, ARRAY_A);
					foreach ($posts as $post)
					{
						$i++;
						$building_content .= "<tr>
							<td>".
								$i.
							"</td>";
						$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_1_numInSet_0'";
						$address = $wpdb->get_results ($sqlstr, ARRAY_A);
						$building_content .= "<td>".
							$address [0]["meta_value"].
						"</td>";
						$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_2_numInSet_0'";
						$coordinats = $wpdb->get_results ($sqlstr, ARRAY_A);
						$building_content .= "<td>".
							$coordinats [0]["meta_value"].
						"</td>";
						$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_3_numInSet_0'";
						$flors = $wpdb->get_results ($sqlstr, ARRAY_A);
						$building_content .= "<td>".
							(((int)(substr (strstr ($flors [0]["meta_value"], "num_"), 4, strlen (strstr ($flors [0]["meta_value"], "num_") - 4)))) - 1).
						"</td>";
						$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_4_numInSet_0'";
						$material = $wpdb->get_results ($sqlstr, ARRAY_A);
						$building_content .= "<td>";
						switch ($material [0]["meta_value"])
						{
							case "radiobutton_num_2":
								$building_content .= "Панель";
								break;
							case "radiobutton_num_3":
								$building_content .= "Кирпич";
								break;
							case "radiobutton_num_4":
								$building_content .= "Пенблок";
								break;
						}
						$building_content .= "</td>";
						$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE meta_key='_wp_attached_file' AND post_id=(SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_6_numInSet_0')";
						$house_photo = $wpdb->get_results ($sqlstr, ARRAY_A);
						$building_content .=  "<td>".
							"<img src='http://localhost/france2/wp-content/uploads/".$house_photo [0]["meta_value"]."'/>
						</td>";
						$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_5_numInSet_0'";
						$square = $wpdb->get_results ($sqlstr, ARRAY_A);
						$building_content .=  "<td>".
							$square [0]["meta_value"].
						"</td>";
						$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_7_numInSet_0'";
						$rooms = $wpdb->get_results ($sqlstr, ARRAY_A);
						$building_content .=  "<td>".
							(((int)(substr (strstr ($rooms [0]["meta_value"], "num_"), 4, strlen (strstr ($rooms [0]["meta_value"], "num_") - 4)))) - 1).
						"</td>";
						$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_8_numInSet_0'";
						$balcone = $wpdb->get_results ($sqlstr, ARRAY_A);
						$building_content .= "<td>";
						switch ($balcone [0]["meta_value"])
						{
							case "radiobutton_num_2":
								$building_content .= "Есть";
								break;
							case "radiobutton_num_3":
								$building_content .= "Нет";
								break;
						}
						$building_content .= "</td>";
						$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_9_numInSet_0'";
						$sanknote = $wpdb->get_results ($sqlstr, ARRAY_A);
						$building_content .= "<td>";
						switch ($sanknote [0]["meta_value"])
						{
							case "radiobutton_num_2":
								$building_content .= "Есть";
								break;
							case "radiobutton_num_3":
								$building_content .= "Нет";
								break;
						}
						$building_content .= "</td>";
						$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE meta_key='_wp_attached_file' AND post_id=(SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_10_numInSet_0')";
						$flate_photo = $wpdb->get_results ($sqlstr, ARRAY_A);
						$building_content .=  "<td>".
							"<img src='http://localhost/france2/wp-content/uploads/".$flate_photo [0]["meta_value"]."'/>
						</td>";
					}
			$building_content .= "</tr>
				</table>
				</form>";
		$sqlstr = "SELECT post_id FROM wp_postmeta WHERE meta_key='_simple_fields_selected_connector'";
		$posts = $wpdb->get_results ($sqlstr, ARRAY_A);
		$building_content .= "Страница: <b>1</b>";
		for ($i = 0; $i < intdiv (count ($posts), 10) - 1; $i++)
			$building_content .=  " <a id='bp-".($i + 2)."' class='pagin'><b>".($i + 2)."</b></a>";
		if (count ($post) % 10 != 0)
			$building_content .=  " <a id='bp-".(intdiv (count ($posts), 10) + 1)."' class='pagin'><b>".(intdiv (count ($posts), 10) + 1)."</b></a>";
		$building_content .= "</div>";
        $my_postarr = array
		(
			"post_title" => "Объект недвижимости",
			"post_content"  => $building_content, // контент
	        "post_status"   => "publish" // опубликованный пост
		);
 
		// добавляем пост и получаем его ID 
		$my_post_id = wp_insert_post ($my_postarr);
		$catarr = array
		(
			"cat_name" => "Район",
			"category_description" => "Район расположения недвижимости",
			"category_nicename" => "rajon"
		);
 
		$cat_id = wp_insert_category ($catarr);
 
		// присваиваем рубрику к посту (ссылка на документацию wp_set_object_terms() дана чуть выше)
		wp_set_object_terms ($my_post_id, $cat_id, "category");
    }
	
	function shortcode_buildiing ()
	{
		$i = 0;
		$building_content = "<div id='apartments'>
			<form name='duildings' id='buildings'>
				<table>
					<tr>
						<th>
							№ п/п
						</th>
						<th>
							Адрес
						</th>
						<th>
							Координаты местоположения
						</th>
						<th>
							Количество этажей
						</th>
						<th>
							Тип строения
						</th>
						<th>
							Изображение дома
						</th>
						<th>
							Площадь
						</th>
						<th>
							Количество комнат
						</th>
						<th>
							Балкон
						</th>
						<th>
							Санузел
						</th>
						<th>
							Изображение квартиры
						</th>
						</tr>";
						global $wpdb;
						global $user_ID;
						$sqlstr = "SELECT post_id FROM wp_postmeta WHERE meta_key='_simple_fields_selected_connector'";
						$posts = $wpdb->get_results ($sqlstr, ARRAY_A);
						foreach ($posts as $post)
						{
							$i++;
							$building_content .= "<tr>
								<td>".
									$i.
								"</td>";
							$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_1_numInSet_0'";
							$address = $wpdb->get_results ($sqlstr, ARRAY_A);
							$building_content .= "<td>".
								$address [0]["meta_value"].
							"</td>";
							$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_2_numInSet_0'";
							$coordinats = $wpdb->get_results ($sqlstr, ARRAY_A);
							$building_content .= "<td>".
								$coordinats [0]["meta_value"].
							"</td>";
							$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_3_numInSet_0'";
							$flors = $wpdb->get_results ($sqlstr, ARRAY_A);
							$building_content .= "<td>".
								(((int)(substr (strstr ($flors [0]["meta_value"], "num_"), 4, strlen (strstr ($flors [0]["meta_value"], "num_") - 4)))) - 1).
							"</td>";
							$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_4_numInSet_0'";
							$material = $wpdb->get_results ($sqlstr, ARRAY_A);
							$building_content .= "<td>";
								switch ($material [0]["meta_value"])
								{
									case "radiobutton_num_2":
										$building_content .= "Панель";
										break;
									case "radiobutton_num_3":
										$building_content .= "Кирпич";
										break;
									case "radiobutton_num_4":
										$building_content .= "Пенблок";
										break;
								}
							$building_content .= "</td>";
							$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE meta_key='_wp_attached_file' AND post_id=(SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_6_numInSet_0')";
							$house_photo = $wpdb->get_results ($sqlstr, ARRAY_A);
							$building_content .=  "<td>".
								"<img src='http://localhost/france2/wp-content/uploads/".$house_photo [0]["meta_value"]."'/>
							</td>";
							$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_5_numInSet_0'";
							$square = $wpdb->get_results ($sqlstr, ARRAY_A);
							$building_content .=  "<td>".
								$square [0]["meta_value"].
							"</td>";
							$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_7_numInSet_0'";
							$rooms = $wpdb->get_results ($sqlstr, ARRAY_A);
							$building_content .=  "<td>".
								(((int)(substr (strstr ($rooms [0]["meta_value"], "num_"), 4, strlen (strstr ($rooms [0]["meta_value"], "num_") - 4)))) - 1).
							"</td>";
							$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_8_numInSet_0'";
							$balcone = $wpdb->get_results ($sqlstr, ARRAY_A);
							$building_content .= "<td>";
								switch ($balcone [0]["meta_value"])
								{
									case "radiobutton_num_2":
										$building_content .= "Есть";
										break;
									case "radiobutton_num_3":
										$building_content .= "Нет";
										break;
								}
							$building_content .= "</td>";
							$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_9_numInSet_0'";
							$sanknote = $wpdb->get_results ($sqlstr, ARRAY_A);
							$building_content .= "<td>";
								switch ($sanknote [0]["meta_value"])
								{
									case "radiobutton_num_2":
										$building_content .= "Есть";
										break;
									case "radiobutton_num_3":
										$building_content .= "Нет";
										break;
								}
							$building_content .= "</td>";
							$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE meta_key='_wp_attached_file' AND post_id=(SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_10_numInSet_0')";
							$flate_photo = $wpdb->get_results ($sqlstr, ARRAY_A);
							$building_content .=  "<td>".
								"<img src='http://localhost/france2/wp-content/uploads/".$flate_photo [0]["meta_value"]."'/>
							</td>";
				}
				$building_content .= "</tr>
					</table>				
				</form>";
		$sqlstr = "SELECT post_id FROM wp_postmeta WHERE meta_key='_simple_fields_selected_connector'";
		$posts = $wpdb->get_results ($sqlstr, ARRAY_A);
		$building_content .= "Страница: <b>1</b>";
		for ($i = 0; $i < intdiv (count ($posts), 10) - 1; $i++)
			$building_content .=  " <a id='bp-".($i + 2)."' class='pagin'><b>".($i + 2)."</b></a>";
		if (count ($post) % 10 != 0)
			$building_content .=  " <a id='bp-".(intdiv (count ($posts), 10) + 1)."' class='pagin'><b>".(intdiv (count ($posts), 10) + 1)."</b></a>";
		$building_content .= "</div>";
		echo $building_content;
	}
	
	add_shortcode ("building", "shortcode_buildiing");
	class trueTopPostsWidget extends WP_Widget
	{
 
		/*
		* создание виджета
		*/
		function __construct ()
		{
			parent::__construct
			(
				"true_top_widget", 
				"Список объектов недвижимости", // заголовок виджета
			array ("description" => "Вывод всех объектов недвижимости из базы с их характеристиками.") // описание
		);
	}
 
	/*
	 * фронтэнд виджета
	 */
	public function widget ($args, $instance)
	{
		$i = 0;
		$building_content = "<form name='duildings' id='buildings'>
			<table>
				<tr>
					<th>
						№ п/п
					</th>
					<th>
						Адрес
					</th>
					<th>
						Координаты местоположения
					</th>
					<th>
						Количество этажей
					</th>
					<th>
						Тип строения
					</th>
					<th>
						Изображение дома
					</th>
					<th>
						Площадь
					</th>
					<th>
						Количество комнат
					</th>
					<th>
						Балкон
					</th>
					<th>
						Санузел
					</th>
					<th>
						Изображение квартиры
					</th>
				</tr>";
				global $wpdb;
				global $user_ID;
				$sqlstr = "SELECT post_id FROM wp_postmeta WHERE meta_key='_simple_fields_selected_connector'";
				$posts = $wpdb->get_results ($sqlstr, ARRAY_A);
				foreach ($posts as $post)
				{
					$i++;
					$building_content .= "<tr>
						<td>".
							$i.
						"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_1_numInSet_0'";
					$address = $wpdb->get_results ($sqlstr, ARRAY_A);
					$building_content .= "<td>".
						$address [0]["meta_value"].
					"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_2_numInSet_0'";
					$coordinats = $wpdb->get_results ($sqlstr, ARRAY_A);
					$building_content .= "<td>".
					$coordinats [0]["meta_value"].
					"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_3_numInSet_0'";
					$flors = $wpdb->get_results ($sqlstr, ARRAY_A);
					$building_content .= "<td>".
						(((int)(substr (strstr ($flors [0]["meta_value"], "num_"), 4, strlen (strstr ($flors [0]["meta_value"], "num_") - 4)))) - 1).
					"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_4_numInSet_0'";
					$material = $wpdb->get_results ($sqlstr, ARRAY_A);
					$building_content .= "<td>";
					switch ($material [0]["meta_value"])
					{
						case "radiobutton_num_2":
							$building_content .= "Панель";
							break;
						case "radiobutton_num_3":
							$building_content .= "Кирпич";
							break;
						case "radiobutton_num_4":
							$building_content .= "Пенблок";
							break;
					}
					$building_content .= "</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE meta_key='_wp_attached_file' AND post_id=(SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_6_numInSet_0')";
					$house_photo = $wpdb->get_results ($sqlstr, ARRAY_A);
					$building_content .=  "<td>".
						"<img src='http://localhost/france2/wp-content/uploads/".$house_photo [0]["meta_value"]."'/>
					</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_5_numInSet_0'";
					$square = $wpdb->get_results ($sqlstr, ARRAY_A);
					$building_content .=  "<td>".
						$square [0]["meta_value"].
					"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_7_numInSet_0'";
					$rooms = $wpdb->get_results ($sqlstr, ARRAY_A);
					$building_content .=  "<td>".
						(((int)(substr (strstr ($rooms [0]["meta_value"], "num_"), 4, strlen (strstr ($rooms [0]["meta_value"], "num_") - 4)))) - 1).
					"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_8_numInSet_0'";
					$balcone = $wpdb->get_results ($sqlstr, ARRAY_A);
					$building_content .= "<td>";
					switch ($balcone [0]["meta_value"])
					{
						case "radiobutton_num_2":
							$building_content .= "Есть";
							break;
						case "radiobutton_num_3":
							$building_content .= "Нет";
							break;
					}
					$building_content .= "</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_9_numInSet_0'";
					$sanknote = $wpdb->get_results ($sqlstr, ARRAY_A);
					$building_content .= "<td>";
					switch ($sanknote [0]["meta_value"])
					{
						case "radiobutton_num_2":
							$building_content .= "Есть";
							break;
						case "radiobutton_num_3":
							$building_content .= "Нет";
							break;
					}
					$building_content .= "</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE meta_key='_wp_attached_file' AND post_id=(SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_10_numInSet_0')";
					$flate_photo = $wpdb->get_results ($sqlstr, ARRAY_A);
					$building_content .=  "<td>".
						"<img src='http://localhost/france2/wp-content/uploads/".$flate_photo [0]["meta_value"]."'/>
					</td>";
				}
				$building_content .= "</tr>
			</table>
		</form>";
		echo $building_content;
	}
 
	/*
	 * бэкэнд виджета
	 */
	public function form ($instance)
	{
	}
 
	/*
	 * сохранение настроек виджета
	 */
	public function update ()
	{
	}
}
 
/*
 * регистрация виджета
 */
function true_top_posts_widget_load ()
{
	register_widget ("trueTopPostsWidget");
}

add_action ("widgets_init", "true_top_posts_widget_load");

add_action ("wp_enqueue_scripts", "myajax_data", 99);

function myajax_data ()
{
	wp_localize_script ("ajax_script", "myajax", 
		array
		(
			"url" => admin_url ("other_pages.php?page_n=".$_POST ["page_n"])
		)
	);
}

add_action ("wp_footer", "my_action_javascript", 99); // для фронта

function azzrael_ajax_post1 ()
{
	$text = $_POST["text"];
	// ... сохраняем в базу полученный контент, к примеру,
	die("Okey!");
	wp_localize_script ("ajax_script", "myajax", 
		array
		(
			"url" => admin_url ("other_pages.php?page_n=".$_POST ["page_n"])
		)
	);
}
 
add_action ("wp_ajax_nopriv_azzrael_ajax_post", "azzrael_ajax_post1");
add_action ("wp_ajax_azzrael_ajax_post", "azzrael_ajax_post1");

add_action ("wp_ajax_my_action", "my_action_callback");
add_action ("wp_ajax_nopriv_my_action", "my_action_callback");
function my_action_callback ()
{
	$whatever = intval ($_POST ["whatever"]);
	echo $whatever + 10;
	// выход нужен для того, чтобы в ответе не было ничего лишнего, только то что возвращает функция
	wp_die ();
}

add_action('init', 'my_custom_init');
function my_custom_init(){
	register_post_type('building', array(
		'labels'             => array(
			'name'               => 'Объект недвижимости', // Основное название типа записи
			'singular_name'      => 'Объект недвижимости', // отдельное название записи типа Book
			'add_new'            => 'Добавить новый',
			'add_new_item'       => 'Добавить новый объект недвижимости',
			'edit_item'          => 'Редактировать объект недвижимости',
			'new_item'           => 'Новый объект недвижимости',
			'view_item'          => 'Посмотреть объект недвижимости',
			'search_items'       => 'Найти объект недвижимости',
			'not_found'          => 'Объектов недвижимости не найдено',
			'not_found_in_trash' => 'В корзине объектов недвижимости не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Объекты недвижимости'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','editor','author','thumbnail','excerpt','comments')
	) );
}

add_action( 'init', 'create_region_taxonomies' );

function create_region_taxonomies(){

	// Добавляем древовидную таксономию 'genre' (как категории)
	register_taxonomy('region', array('building'), array(
		'hierarchical'  => true,
		'labels'        => array(
			'name'              => _x( 'Regions', 'taxonomy general name' ),
			'singular_name'     => _x( 'Region', 'taxonomy singular name' ),
			'search_items'      =>  __( 'Search Regions' ),
			'all_items'         => __( 'All Regions' ),
			'parent_item'       => __( 'Parent Region' ),
			'parent_item_colon' => __( 'Parent Region:' ),
			'edit_item'         => __( 'Edit Region' ),
			'update_item'       => __( 'Update Region' ),
			'add_new_item'      => __( 'Add New Region' ),
			'new_item_name'     => __( 'New Region Name' ),
			'menu_name'         => __( 'Region' ),
		),
		'show_ui'       => true,
		'query_var'     => true,
		//'rewrite'       => array( 'slug' => 'the_genre' ), // свой слаг в URL
	));
}