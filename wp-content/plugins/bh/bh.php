<?php

/*
Plugin Name: BargainHunter
Plugin URI: http://mirazalmamun.wordpress.com
Description: Customization of BargainHunter Application
Author: Miraz Al-Mamun
Version: 0.9
Author URI: http://mirazalmamun.wordpress.com
*/

//the filters
add_filter('sidebars_widgets', 'bh_sidebar_widget');
add_filter('wp_get_nav_menu_items','bh_filter_get_nav_menu_items');

//the action hooks
add_action('wp_enqueue_scripts', 'bh_hook_wp_enqueue_scripts');
add_action('wp_ajax_nopriv_bh_register_user','bh_register_user'); //ajax callback for user registration
add_action('wp_ajax_nopriv_bh_registration_form','bh_registration_form'); //ajax callback for user registration form

register_activation_hook(__FILE__, 'bh_plugin_activation_hook');
register_deactivation_hook(__FILE__, 'bh_plugin_deactivation_hook');
register_uninstall_hook(__FILE__, 'bh_plugin_uninstall_hook');

/**
 * Activation hook
 * 
 * @param type $name Description
 * @return type Description
 */
function bh_plugin_activation_hook(){
    //TODO:
}

/**
 * Deactivation hook
 * 
 * @param type $name Description
 * @return type Description
 */
function bh_plugin_deactivation_hook(){
    //TODO:
}

/**
 * Activation hook
 * 
 * @param type $name Description
 * @return type Description
 */
function bh_plugin_uninstall_hook(){
    //TODO:
}

/**
 * The wp_meta action hook handler
 * 
 * @param type $name Description
 * @return type Description
 */
function bh_sidebar_widget($widgets){
    global $sidebars_widgets;
    return $sidebars_widgets;
}

/**
 * Update the top menu items, add additional register menu item to the list
 * 
 * @param type $name Description
 * @return type Description
 */
function bh_filter_get_nav_menu_items($items){
    //TODO: get the arguments of the function
    if(!empty($items)){
       foreach($items as $item){
           //$items is an object of post attributes
           if(get_option('users_can_register')){
               if(is_object($item)){
                   if( strtolower($item->title) == 'register'){
                       $item->url = get_bloginfo('wpurl').'/wp-login.php?action=register';
                       $item->classes = array('menu-item-register');
                   }   
               }
           }else{
               //TODO:
           }
       } 
    }
    return $items;
}

/**
 * Add the footer scripts for bootstrap
 * 
 * @param
 * @return type Description
 */
function bh_hook_wp_enqueue_scripts(){
    //load the bootstrap styles and scripts
    wp_register_script('bootstrap', plugin_dir_url(__FILE__).'script/bootstrap-3.0.3/js/bootstrap.min.js', array('jquery'));
    wp_register_style('bootstrap', plugin_dir_url(__FILE__).'css/bootstrap-3.0.3/css/bootstrap.min.css');
    wp_register_style('bootstrap-theme', plugin_dir_url(__FILE__).'css/bootstrap-3.0.3/css/bootstrap-theme.min.css');
    wp_enqueue_script('bootstrap');
    wp_enqueue_style('bootstrap');
    wp_enqueue_style('bootstrap-theme');
    //the local scripts
    wp_enqueue_script('bh_registration', plugin_dir_url(__FILE__).'script/bh_registration.js', array('jquery','bootstrap'));
    //send some local scripts
    //get the post menu item with 'registration' title
    $posts = get_posts(array('post_type'=>'nav_menu_item'));
    if(!empty($posts)){
        foreach($posts as $post){
            if($post->post_name == 'register'){
               $ID = $post->ID; 
            }
        }
    }
    wp_localize_script('bh_registration', 'bh_registration', array('url'=>get_bloginfo('wpurl').'/wp-login.php?action=register', 'ID'=>$ID, 'ajaxUrl'=>admin_url('admin-ajax.php')));
}

/**
 * Action hook handler for user registration AJAX callback
 * 
 * @param type $name Description
 * @return
 * 
 */
function bh_register_user(){
    //TODO handle the POST data
    
}

/**
 * Get the registration form for the user registration
 * 
 * @param type $name Description
 * @return type Description
 */
function bh_registration_form(){
    $html .= '<form class="form-horizontal" method="post" id="user-registration-form" role="form">';
    
    //the user name
    $html .= '<div class="form-group">
                <label for="user-name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="user-name" placeholder="Full Name">
                </div>
            </div>';
   
    //email
    $html .= '<div class="form-group">
            <label for="user-email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="user-email" placeholder="Email">
            </div>
        </div>';
    
    //password
    $html .= '<div class="form-group">
                <label for="user-password" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="user-password" placeholder="Password">
                </div>
            </div>';
    
    //password confirm
    $html .= '<div class="form-group">
                <label for="user-password-confirm" class="col-sm-2 control-label">Retype Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="user-password-confirm" placeholder="Retype Password">
                </div>
            </div>';
    //close the form
    $html .= '</form>';
    
    //encode the array to produce JSON
    echo json_encode(array(
                'response'=>'success',
                'data'=>array(
                    'type'=>'html',
                    'content'=>$html,
                    )
                )
            );
    die();
}