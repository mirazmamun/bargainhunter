<?php

/**
 * Description of class-bh-form
 *  @see elementName
 * @since version
 * @author malmamun
 */
class bh_form {
    
    /**
     * @var type Description
     */
    
    /**
     * The constructor
     * 
     * @param type $name Description
     * @return type Description
     */
    public function __construct() {
        
    }
    
    /**
     * Get HTML text input form
     * 
     * @param array $params The array of text input element attributes and data
     * @return
     * 
     */
    public function get_text_input_html($params = array()){
        $defaults = array(
            'class'=>'',
            'id'=>'',
            'type'=>'text', //other options,
            'required'=>false,
            'value'=>'',
            'div'=>array(
                'class'=>'',
                'id'=>'',
            ),
            'label'=>array(
                'class'=>'',
                'id'=>'',
                'text'=>'',
            ),
            'wrap'=>array(
                'tag'=>'div',
                'class'=>'',
                'id'=>''
            )
        );
        
        //if the passed array is not empty, then do the merge
        $defaults = wp_parse_args($params, $defaults);
        $html .= '<div class="'.$defaults['wrap']['class'].'"  id="'.$defaults['wrap']['id'].'">';
        if($defaults['label'] !== false or !empty($defaults['label'])){
            $html .= '  <label for="'.$defaults['id'].'">';   
        }
        if($defaults['div'] !== false or !empty($defaults['div'])){
            $html .= '<div class="'.$defaults['div']['class'].'" id="'.$defaults['div']['id'].'">';
        }
        $html .=            '<input class="'.$defaults['class'].'" id="'.$defaults['id'].'" value="'.$defaults['value'].'" type="'.$defaults['type'].'">';
        
        if($defaults['div'] !== false or !empty($defaults['div'])){
            $html .= '</div>';
        }
        $html .= '</div>';
    }
}
?>
