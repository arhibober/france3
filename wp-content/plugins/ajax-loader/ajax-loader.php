<?php
/*
Plugin Name: Ajax Loader
Plugin URI: https://example.com/
Description: Load More Posts with AJAX
Version: 1.0
Author: M.A.I.
Author URI: https://example.com/
License: GPL2
 */
add_action('wp_ajax_loadmore', 'bootkit_my_load_more_scripts');
add_action('wp_ajax_nopriv_loadmore', 'bootkit_my_load_more_scripts');

function ajax_loader()
{

    global $wp_query;

// don't display the button if there are not enough posts
    if ($wp_query->max_num_pages > 1) {
        echo '<div class="container"><div class="bootkit_loadmore btn btn-primary">More posts</div></div>';
    }

}
add_action('get_footer', 'ajax_loader');

function bootkit_my_load_more_scripts()
{

    global $wp_query;

    // In most cases it is already included on the page and this line can be removed
    wp_enqueue_script('jquery');

    // register our main script but do not enqueue it yet
    wp_register_script('my_loadmore', plugin_dir_url(__FILE__) . 'js/myloadmore.js', array('jquery'));

    // now the most interesting part
    // we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
    // you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()
    wp_localize_script('my_loadmore', 'bootkit_loadmore_params', array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
        'posts' => json_encode($wp_query->query_vars), // everything about your loop is here
        'current_page' => get_query_var('paged') ? get_query_var('paged') : 1,
        'max_page' => $wp_query->max_num_pages,
    ));

    wp_enqueue_script('my_loadmore');
}

add_action('wp_enqueue_scripts', 'bootkit_my_load_more_scripts');