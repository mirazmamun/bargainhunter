<?php

/*
  Plugin Name: WP Quiz v2
  Plugin URI: -
  Description: Create dynamic multiple choice quizes for WordPress.
  Author: Rakhitha Nimesh
  Version: 1.0
  Author URI: http://www.innovativephp.com/
 */

class WP_Quiz {

    public $plugin_url;

    public function __construct() {
        $this->plugin_url = plugin_dir_url(__FILE__);


        add_action('init', array($this, 'wpq_add_custom_post_type'));
        add_action('add_meta_boxes', array($this, 'wpq_quiz_meta_boxes'));
        add_action('init', array($this, 'wpq_create_taxonomies'), 0);


        add_action('admin_enqueue_scripts', array($this, 'wpq_admin_scripts'));
        add_action('save_post', array($this, 'wpq_save_quizes'));
        add_action('admin_menu', array($this, 'wpq_plugin_settings'));



        add_action('wp_enqueue_scripts', array($this, 'wpq_frontend_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'wpq_frontend_styles'));
        add_action('wp_ajax_nopriv_get_quiz_results', array($this, 'get_quiz_results'));
        add_action('wp_ajax_get_quiz_results', array($this, 'get_quiz_results'));

        add_shortcode("wpq_show_quiz", array($this, "wpq_show_quiz"));
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

    function wpq_admin_scripts() {

        wp_enqueue_script('jQuery');

        wp_register_script('quiz-admin', plugins_url('js/quiz.js', __FILE__), array('jquery'));
        wp_enqueue_script('quiz-admin');
    }

    function wpq_frontend_scripts() {

        wp_enqueue_script('jQuery');



        wp_register_script('rhino', plugins_url('js/rhinoslider-1.05.min.js', __FILE__), array('jquery'));
        wp_enqueue_script('rhino');

        wp_register_script('rhino-mousewheel', plugins_url('js/mousewheel.js', __FILE__), array('jquery'));
        wp_enqueue_script('rhino-mousewheel');

        wp_register_script('rhino-easing', plugins_url('js/easing.js', __FILE__), array('jquery'));
        wp_enqueue_script('rhino-easing');


        $quiz_duration = get_option('wpq_duration');
        $quiz_duration = ($quiz_duration != "") ? $quiz_duration : 300;



        wp_register_script('quiz', plugins_url('js/quiz.js', __FILE__), array('jquery'));
        wp_enqueue_script('quiz');

        $config_array = array(
            'ajaxURL' => admin_url('admin-ajax.php'),
            'quizNonce' => wp_create_nonce('quiz-nonce'),
            'quizDuration' => $quiz_duration,
            'plugin_url' => $this->plugin_url
        );

        wp_localize_script('quiz', 'quiz', $config_array);
    }

    function wpq_frontend_styles() {

        wp_register_style('rhino-base', plugins_url('css/rhinoslider-1.05.css', __FILE__));
        wp_enqueue_style('rhino-base');
    }

    function wpq_quiz_meta_boxes() {

        add_meta_box("quiz-answers-info", "Quiz Answers Info", array($this, 'wpq_quiz_answers_info'), "wptuts_quiz", "normal", "high");
    }

    function wpq_quiz_answers_info() {

        global $post;

        $question_answers = get_post_meta($post->ID, "_question_answers", true);
        $question_answers = ($question_answers == '') ? array("", "", "", "", "") : json_decode($question_answers);

        $question_correct_answer = trim(get_post_meta($post->ID, "_question_correct_answer", true));



        $html = '<input type="hidden" name="question_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '" />';
        $html .= '<table class="form-table">';
        $html .= '<tr><th><label>Correct Answer  </label></th>';
        $html .= '<td><select name="correct_answer" id="correct_answer" >';

        for ($i = 1; $i <= 5; $i++) {
            if ($question_correct_answer == $i) {
                $html .= "<option value='{$i}' selected >Answer {$i}</option>";
            } else {
                $html .= "<option value='{$i}'>Answer {$i}</option>";
            }
        }


        $html .= "</select></td></tr>";

        $index = 1;
        foreach ($question_answers as $question_answer) {

            $html .= "<tr><th style=''>
            <label for='Price'>Answer {$index}</label>
            </th>
            <td>
            <textarea name='quiz_answer[]' id='quiz_answer{$index}' >" . trim($question_answer) . "</textarea>
            </td></tr>";
            $index++;
        }




        $html .= "</tr>";
        $html .= '</table>';

        echo $html;
    }

    function wpq_save_quizes($post_id) {

        if (!wp_verify_nonce($_POST['question_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if ('wptuts_quiz' == $_POST['post_type'] && current_user_can('edit_post', $post_id)) {

            $question_answers = isset($_POST['quiz_answer']) ? ($_POST['quiz_answer']) : array();
            $filtered_answers = array();
            foreach ($question_answers as $answer) {
                array_push($filtered_answers, trim($answer));
            }
            $question_answers = json_encode($filtered_answers);


            $correct_answer = isset($_POST['correct_answer']) ? $_POST['correct_answer'] : "";

            update_post_meta($post_id, "_question_answers", $question_answers);
            update_post_meta($post_id, "_question_correct_answer", $correct_answer);
        } else {
            return $post_id;
        }
    }

    function wpq_plugin_settings() {

        //create new top-level menu
        add_menu_page('WPTuts Quiz Settings', 'WPTuts Quiz Settings', 'administrator', 'quiz_settings', array($this, 'wpq_display_settings'));
    }

    function wpq_display_settings() {


        $html = '<div class="wrap">

            <form method="post" name="options" action="options.php">

            <h2>Select Your Settings</h2>' . wp_nonce_field('update-options') . '
            <table width="100%" cellpadding="10" class="form-table">
                <tr>
                    <td align="left" scope="row">
                    <label>Number of Questions</label><input type="text" name="wpq_num_questions" 
                        value="' . get_option('wpq_num_questions') . '" />

                    </td> 
                </tr>
                <tr>
                    <td align="left" scope="row">
                    <label>Duration (Mins)</label><input type="text" name="wpq_duration" 
                    value="' . get_option('wpq_duration') . '" />

                    </td> 
                </tr>
            </table>
            <p class="submit">
                <input type="hidden" name="action" value="update" />  
                <input type="hidden" name="page_options" value="wpq_num_questions,wpq_duration" /> 
                <input type="submit" name="Submit" value="Update" />
            </p>
            </form>

        </div>';
        echo $html;
    }

    function wpq_create_taxonomies() {
        register_taxonomy(
                'quiz_categories', 'wptuts_quiz', array(
            'labels' => array(
                'name' => 'Quiz Category',
                'add_new_item' => 'Add New Quiz Category',
                'new_item_name' => "New Quiz Category"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
                )
        );
    }

    function get_quiz_results() {
        $score = 0;
        $question_answers = $_POST["data"];
        $question_results = array();
        foreach ($question_answers as $ques_id => $answer) {
            $question_id = trim(str_replace("qid_", "", $ques_id)) . ",";

            $correct_answer = get_post_meta($question_id, '_question_correct_answer', true);
            if ($answer == $correct_answer) {
                $score++;
                $question_results["$question_id"] = array("answer" => $answer, "correct_answer" => $correct_answer, "mark" => "correct");
            } else {
                $question_results["$question_id"] = array("answer" => $answer, "correct_answer" => $correct_answer, "mark" => "incorrect");
            }
        }

        $total_questions = count($question_answers);

        $quiz_result_data = array(
            "total_questions" => $total_questions,
            "score" => $score,
            "result" => $question_results
        );
        echo json_encode($quiz_result_data);
        exit;
    }

    function wpq_show_quiz($atts) {
        global $post;


        $html = "<div id='quiz_panel'><form action='' method='POST' >";
        $html .= "<div class='toolbar'>";
        $html .= "<div class='toolbar_item'><select name='quiz_category' id='quiz_category'>";

        $quiz_categories = get_terms('quiz_categories', 'hide_empty=1');
        foreach ($quiz_categories as $quiz_category) {
            $html .= "<option value='{$quiz_category->term_id}'>{$quiz_category->name}</option>";
        }

        $html .= "</select></div>";
        $html .= "<input type='hidden' value='select_quiz_cat' name='wpq_action' />";
        $html .= "<div class='toolbar_item'><input type='submit' value='Select Quiz Category' /></div>";
        $html .= "</form>";


        $html .= "<div class='complete toolbar_item' ><input type='button' id='completeQuiz' value='Get Results' /></div>";


        $questions_str = "";
        if (isset($_POST['wpq_action']) && $_POST['wpq_action'] == 'select_quiz_cat') {

            $html .= "<div id='timer' style='display:block' ></div>";
            $html .= "<div style='clear:both'></div></div>";

            $quiz_category_id = $_POST['quiz_category'];
            $quiz_num = get_option('wpq_num_questions');
            $args = array(
                'post_type' => 'wptuts_quiz',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'quiz_categories',
                        'field' => 'id',
                        'terms' => $quiz_category_id
                    )
                ),
                'orderby' => 'rand',
                'post_status' => 'publish',
                'posts_per_page' => $quiz_num
            );

            $query = null;
            $query = new WP_Query($args);
            $quiz_index = 1;
            while ($query->have_posts()) : $query->the_post();

                $question_id = get_the_ID();
                $question = the_title("", "", FALSE) . " " . get_the_content();

                $question_answers = json_decode(get_post_meta($question_id, "_question_answers", true));

                $questions_str .= "<li>";
                $questions_str .= "<div class='ques_title'><span class='quiz_num'>{$quiz_index}</span>{$question}</div>";
                $questions_str .= "<div class='ques_answers' data-quiz-id='{$question_id}' >";

                $quiestion_index = 1;
                foreach ($question_answers as $key => $value) {

                    if ($value != "") {
                        $questions_str .= "{$quiestion_index} <input type='radio' value='{$quiestion_index}' name='ans_{$question_id}[]' />{$value}<br/>";
                    }
                    $quiestion_index++;
                }

                $questions_str .= "</div></li>";

                $quiz_index++;

            endwhile;


            wp_reset_query();



            $html .= "<ul id='slider'>{$questions_str}";
            $html .= "<li id='quiz_result_page'><div class='ques_title'>Quiz Results <span id='score'></span></div>";
            $html .= "<div id='quiz_result'></div>";
            $html .= "</li></ul></div>";
        } else {
            $html .= "<div id='timer' style='display:none' ></div>";
            $html .= "<div style='clear:both'></div></div>";
        }
        return $html;
    }

}

$quiz = new WP_Quiz();























