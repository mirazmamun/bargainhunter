<?php

/*
  Plugin Name: Bargain Hunter
  Plugin URI: http://mirazalmamun.wordpress.com
  Description: Create the post type garage sale
  Author: Miraz Al-Mamun
  Version: 0.9
  Author URI: http://mirazalmamun.wordpress.com
 */

class BH_GS {

    public $plugin_url;
    
    /**
     * The constructor
     * 
     * @param  null
     * @return null
     */
    public function __construct() {
        $this->plugin_url = plugin_dir_url(__FILE__);

        //Do the action hooks
        add_action('init', array($this, 'bh_gs_add_custom_post_type'));
        add_action('add_meta_boxes', array($this, 'bh_gs_meta_boxes'));
        add_action('init', array($this, 'bh_gs_create_taxonomies'), 0);
    }

    public function wpq_add_custom_post_type() {

        $labels = array(
            'name' => _x('Questions', 'wptuts_quiz'),
            'menu_name' => _x('WPTuts Quiz', 'wptuts_quiz'),
            'add_new' => _x('Add New ', 'wptuts_quiz'),
            'add_new_item' => _x('Add New Question', 'wptuts_quiz'),
            'new_item' => _x('New Question', 'wptuts_quiz'),
            'all_items' => _x('All Questions', 'wptuts_quiz'),
            'edit_item' => _x('Edit Question', 'wptuts_quiz'),
            'view_item' => _x('View Question', 'wptuts_quiz'),
            'search_items' => _x('Search Questions', 'wptuts_quiz'),
            'not_found' => _x('No Questions Found', 'wptuts_quiz'),
        );



        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'description' => 'WP Tuts Quiz',
            'supports' => array('title', 'editor'),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => true,
            'capability_type' => 'post'
        );

        register_post_type('wptuts_quiz', $args);
    }

}

$bh_gs = new BH_GS();























