<?php
	function other_pages ()
	{
		$i = 0;
		echo "fff<table>
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
				$sqlstr = "SELECT post_id FROM wp_postmeta WHERE meta_key='_simple_fields_selected_connector' LIMIT ".($_GET ["page_b"] * 10 + 1).", ".($_GET ["page_b"] * 10 + 10);
				$posts = $wpdb->get_results ($sqlstr, ARRAY_A);
				foreach ($posts as $post)
				{
					$i++;
					echo "<tr>
						<td>".
							$i.
						"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_1_numInSet_0'";
					$address = $wpdb->get_results ($sqlstr, ARRAY_A);
					echo "<td>".
						$address [0]["meta_value"].
					"</td>";
					$sqlstr = "SSELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_2_numInSet_0'";
					$coordinats = $wpdb->get_results ($sqlstr, ARRAY_A);
					echo "<td>".
						$coordinats [0]["meta_value"].					"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_3_numInSet_0'";
					$flors = $wpdb->get_results ($sqlstr, ARRAY_A);
					echo "<td>".
						(((int)(substr (strstr ($flors [0]["meta_value"], "num_"), 4, strlen (strstr ($flors [0]["meta_value"], "num_") - 4)))) - 1).
					"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_4_numInSet_0'";
					$material = $wpdb->get_results ($sqlstr, ARRAY_A);
					echo "<td>";
					switch ($material [0]["meta_value"])
					{
						case "radiobutton_num_2":
							echo "Панель";
							break;
						case "radiobutton_num_3":
							echo "Кирпич";
							break;
						case "radiobutton_num_4":
							echo "Пенблок";
							break;
					}
					echo	"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE meta_key='_wp_attached_file' AND post_id=(SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_6_numInSet_0')";
					$house_photo = $wpdb->get_results ($sqlstr, ARRAY_A);
					echo  "<td>".
						"<img src='http://localhost/france2/wp-content/uploads/".$house_photo [0]["meta_value"]."'/>
					</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_5_numInSet_0'";
					$square = $wpdb->get_results ($sqlstr, ARRAY_A);
					echo  "<td>".
						$square [0]["meta_value"].
					"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_7_numInSet_0'";
					$rooms = $wpdb->get_results ($sqlstr, ARRAY_A);
					echo  "<td>".
						(((int)(substr (strstr ($rooms [0]["meta_value"], "num_"), 4, strlen (strstr ($rooms [0]["meta_value"], "num_") - 4)))) - 1).
					"</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_8_numInSet_0'";
					$balcone = $wpdb->get_results ($sqlstr, ARRAY_A);
					echo "<td>";
					switch ($balcone [0]["meta_value"])
					{
						case "radiobutton_num_2":
							echo "Есть";
							break;
						case "radiobutton_num_3":
							echo "Нет";
							break;
					}
					echo "</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_9_numInSet_0'";
					$sanknote = $wpdb->get_results ($sqlstr, ARRAY_A);
					echo "<td>";
					switch ($sanknote [0]["meta_value"])
					{
						case "radiobutton_num_2":
							$building_content .= "Есть";
							break;
						case "radiobutton_num_3":
							$building_content .= "Нет";
							break;
					}
					echo "</td>";
					$sqlstr = "SELECT meta_value FROM wp_postmeta WHERE meta_key='_wp_attached_file' AND post_id=(SELECT meta_value FROM wp_postmeta WHERE post_id=".$post ["post_id"]." AND meta_key='_simple_fields_fieldGroupID_4_fieldID_10_numInSet_0')";
					$flate_photo = $wpdb->get_results ($sqlstr, ARRAY_A);
					echo  "<td>".
						"<img src='http://localhost/france2/wp-content/uploads/".$flate_photo [0]["meta_value"]."'/>
					</td>";
				}
				echo "</td>
				</table>";
		$sqlstr = "SELECT post_id FROM wp_postmeta WHERE meta_key='_simple_fields_selected_connector'";
		$posts = $wpdb->get_results ($sqlstr, ARRAY_A);
		echo "Страница: ";
		for ($i = 0; $i < intdiv (count ($posts), 10); $i++)
		{
			if ($i > 0)
				echo " ";
			if ($i != $_GET ["page_b"] - 1)
				echo  "<a class='pagin' id='".($i + 1)."'>";
			echo  "<b>".($i + 1)."</b>";					
			if ($i != $_GET ["page_b"] - 1)
				echo "</a>";
		}
		if (count ($post) % 10 != 0)
		{
			if ( intdiv (count ($posts), 10) != $_GET ["page_b"] - 1)
				echo  " <a class='pagin' id='".(intdiv (count ($posts), 10) + 1)."'>";
			echo  "<b>".(intdiv (count ($posts), 10) + 1)."</b>";					
			if ( intdiv (count ($posts), 10) != $_GET ["page_b"] - 1)
				echo  "</a>";
		}
	}
	add_action ("wp_ajax_nopriv_subscribe_cat", "other_pages");
	add_action ("wp_ajax_subscribe_cat", "other_pages");
?>