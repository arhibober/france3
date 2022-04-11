<?php
/*
	Plugin Name: Test Ajax
	Plugin URI: http://страница_с_описанием_плагина_и_его_обновлений
	Description: Плагин выводит список квартир и домов с параметрами, заполненными через плагин Simple Fields.
	Version: 1.0
	Author: arhibober
	Author URI: http://bobrydrova.zzz.com
*/
?>

<?php
/*  Copyright 2018  arhibober  (email: arhibober@gmail.com)

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
       // Обработчик Ajax
    add_action( 'wp_ajax_spyr_adp_ajax_delete_post', 'spyr_adp_ajax_delete_post' );
    function spyr_adp_ajax_delete_post() {
        // Получаем ID поста из URL
        $pid = $_REQUEST['pid'];
     
        // Подтверждение WP_Ajax_Response
        $response = new WP_Ajax_Response;
     
        // Обработка, мы снова проверяем права доступа
        if( current_user_can( 'manage_options' ) && 
            // Верификация Nonces
            wp_verify_nonce( $_REQUEST['nonce'], 'spyr_adp-delete-' . $pid ) &&
            // Удаление записи
            wp_delete_post( $pid ) ) {
            // Формирование ответа, если все прошло без ошибок
            $response->add( array(
                'data'  => 'success',
                'supplemental' => array(
                    'pid' => $pid,
                    'message' => 'This post has been deleted',
                ),
            ) );
        } else {
            // Формирование ответа, если возникли ошибки
            $response->add( array(
                'data'  => 'error',
                'supplemental' => array(
                    'pid' => $pid,
                    'message' => 'Error deleting this post ('. $pid .')',
                ),
            ) );
        }
        // В любом случае отправляем ответ
        $response->send();
     
        // Всегда выходим, когда Ajax выполнен
        exit();
    }
	
add_action ("wp_footer", "my_action_javascript1", 100);
?>

<div class="spyr_adp_link" style="background-color: #ffffff">gg</div>

<?php
 function my_action_javascript1 ()
{
	echo "nnn";
	?> 
<script type="text/javascript">// <![CDATA[
    jQuery( document ).ready( function() {
		console.log ("rrer");
        jQuery( '.spyr_adp_link' ).click( function() {
			console.log ("ooo");
            var link = this;
            var id   = jQuery( link ).attr( 'data-id' );
            var nonce = jQuery( link ).attr( 'data-nonce' );
     
            // Это то, что мы отправляем на сервер
            var data = {
                action: 'spyr_adp_ajax_delete_post',
                pid: id,
                nonce: nonce
            }
            // Изменяем текст анкора ссылки
            // Чтобы предоставить пользователю некоторую немедленную информацию
            jQuery( link ).text( 'Trying to delete post' );
     
            // Отправляем на сервер
            jQuery.post( spyr_params.ajaxurl, data, function( data ) {
                // Разбираем XML-ответ с помощью jQuery
                // Получаем статус
                var status = jQuery( data ).find( 'response_data' ).text();
                // Получаем сообщение
                var message = jQuery( data ).find( 'supplemental message' ).text();
                // Если все прошло без сбоев, выводим сообщение об этом и удаляем ссылку
                if( status == 'success' ) {
                    jQuery( link ).parent().after( '<p><strong>' + message + '</strong></p>').remove();
                } else {
                    // Если возникла ошибка, выводим сообщение об ошибке
                    alert( message );
                }
            });
            // Блокируем поведение ссылки по умолчанию
            //e.preventDefault();
        });
    });
	// ---
// ]]></script>
<?php
}