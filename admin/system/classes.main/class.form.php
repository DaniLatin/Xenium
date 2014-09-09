<?php

/**
 * @author Danijel
 * @copyright 2013
 */

class Form
{
    public function populate_admin_form($html, $populate_array)
    {
        $populate_fields = $populate_array[0];
        //print_r($populate_fields);
        foreach ($populate_fields as $field => $field_value)
        {
            $set_value['[' . $field . ']'] = $field_value;
        }
        $html = strtr($html, $set_value);
        //$html_clean = preg_replace("/\[[^\]]*\]/","",$html);
        $html_clean = $html;
        return $html_clean;
    }
    
    public function generate_admin_form($values_array, $populate_array, $editing, $project_id, $id)
    {
        $static_area = false;
        
        $html = '<form id="EditContent_' . $editing . '_' . $project_id . '_' . $id . '"><ul id="language-tabs" class="nav nav-tabs">';
        $tab_counter = 0;
        $dynamic_area = '<div id="language-tabsContent" class="tab-content">';
        $static_content = '';
        
        foreach ($values_array['static_fields'] as $static_field => $static_field_settings)
        {
            if ($static_field_settings['admin_editable'] == true )
            {
                $static_area = true;
                
                $type = $static_field_settings['html']['type'];
                $label = $static_field_settings['html']['label'];
                
                $static_content .= '<label>' . $label . '</label>';
                
                $attributes = '';
                if (isset($static_field_settings['html_attributes']) && is_array($static_field_settings['html_attributes']))
                {
                    foreach ($static_field_settings['html_attributes'] as $attribute => $attr_value)
                    {
                        $attributes .= ' ' . $attribute . '="' . $attr_value . '"';
                    }
                }
                
                switch ($type)
                {
                    case 'input':
                        $static_content .= '<input id="' . $static_field . '_' . $id . '_' . $project_id . '" name="' . $static_field . '" value="[' . $static_field . ']"' . $attributes . ' />';
                        break;
                    case 'textarea':
                        $static_content .= '<textarea id="' . $static_field . '_' . $id . '_' . $project_id . '" name="' . $static_field . '"' . $attributes . '>[' . $static_field . ']</textarea>';
                        break;
                    case 'image':
                        //print_r($populate_array);
                        $image_json = str_replace('&quot;', '"', $populate_array[0][$static_field]);
                        $static_content .= '<textarea style="display: none;" id="' . $static_field . '_' . $id . '_' . $project_id . '" name="' . $static_field . '">[' . $static_field . ']</textarea>';
                        
                        $images = json_decode($image_json, true);
                        $populate_image = '';
                        if (count($images))
                        {
                            foreach ($images as $image)
                            {
                                $populate_image .= '<div class="slideshow-image" id="' . $image['id'] . '" year="' . $image['fileYear'] . '" month="' . $image['fileMonth'] . '" image="' . $image['fileName'] . '">
                                    <div class="img" style="background: url(/images/1000x200/' . $image['fileYear'] . '/' . $image['fileMonth'] . '/' . $image['fileName'] . ')"></div>
                                </div>';
                            }
                        }
                        $static_content .= '<div id="' . $static_field . '_' . $id . '_' . $project_id . '-plupload"  class="slideshow-image-block">' . $populate_image . '</div>';
                        
                        
                }
            }
            else
            {
                //$html .= '<input id="' . $static_field . '_' . $id . '_' . $project_id . '" name="' . $static_field . '" value="[' . $static_field . ']" style="display: none;" />';
                $html .= '<textarea id="' . $static_field . '_' . $id . '_' . $project_id . '" name="' . $static_field . '" class="not-editable">[' . $static_field . ']</textarea>';
            }
        }
        
        if (isset($values_array['dynamic_fields']))
        {
            $languages = Languages::get_all_www_languages($project_id);
            
            
            if ($static_area)
            {
                $html .= '<li class="active"><a data-toggle="tab" no-follow="true" href="#content_info"><i class="icon-info-circled icon-2x"></i></a></li>';
                $dynamic_area .= '<div id="content_info" class="tab-pane fade active in">' . $static_content . '</div>';
            }
            
            foreach ($languages as $language)
            {
                if ($tab_counter == 0 && !$static_area)
                {
                    $html .= '<li class="active"><a data-toggle="tab" no-follow="true" href="#content_' . $language['code'] . '"><img src="/admin/theme/img/flags/32/' . $language['icon'] . '" /></a></li>';
                    $tab_class = 'tab-pane fade active in';
                }
                else
                {
                    $html .= '<li><a data-toggle="tab" no-follow="true" href="#content_' . $language['code'] . '"><img src="/admin/theme/img/flags/32/' . $language['icon'] . '" /></a></li>';
                    $tab_class = 'tab-pane fade';
                }
                    
                    $dynamic_area .= '<div id="content_' . $language['code'] . '" class="' . $tab_class . '">';
                    foreach ($values_array['dynamic_fields'] as $dynamic_field => $dynamic_field_settings)
                    {
                        if ($dynamic_field_settings['admin_editable'] == true)
                        {
                            $type = $dynamic_field_settings['html']['type'];
                            $label = $dynamic_field_settings['html']['label'];
                            
                            $dynamic_area .= '<label>' . $label . '</label>';
                            
                            $attributes = '';
                            foreach ($dynamic_field_settings['html_attributes'] as $attribute => $attr_value)
                            {
                                $attributes .= ' ' . $attribute . '="' . $attr_value . '"';
                            }
                            
                            switch ($type)
                            {
                                case 'input';
                                    $dynamic_area .= '<input id="' . $dynamic_field . '_' . $language['code'] . '_' . $id . '_' . $project_id . '" name="' . $dynamic_field . '_' . $language['code'] . '" value="[' . $dynamic_field . '_' . $language['code'] . ']"' . $attributes . ' />';
                                    break;
                                case 'textarea':
                                    $dynamic_area .= '<textarea id="' . $dynamic_field . '_' . $language['code'] . '_' . $id . '_' . $project_id . '" name="' . $dynamic_field . '_' . $language['code'] . '"' . $attributes . '>[' . $dynamic_field . '_' . $language['code'] . ']</textarea>';
                                    break;
                            }
                        }
                    }
                    $dynamic_area .= '</div>';
                    
                    $tab_counter++;
            }
            $dynamic_area .= '</div>';
            $html .= '</ul>';
        }
        /*
        foreach ($values_array['static_fields'] as $static_field => $static_field_settings)
        {
            if ($static_field_settings['admin_editable'] == true )
            {
                $type = $static_field_settings['html']['type'];
                $label = $static_field_settings['html']['label'];
                
                $html .= '<label>' . $label . '</label>';
                
                $attributes = '';
                foreach ($static_field_settings['html_attributes'] as $attribute => $attr_value)
                {
                    $attributes .= ' ' . $attribute . '="' . $attr_value . '"';
                }
                
                switch ($type)
                {
                    case 'input':
                        $html .= '<input id="' . $static_field . '" name="' . $static_field . '" value="[' . $static_field . ']"' . $attributes . ' />';
                        break;
                    case 'textarea':
                        $html .= '<textarea id="' . $static_field . '" name="' . $static_field . '"' . $attributes . '>[' . $static_field . ']</textarea>';
                        break;
                }
            }
            else
            {
                $html .= '<input id="' . $static_field . '" name="' . $static_field . '" value="[' . $static_field . ']" style="display: none;" />';
            }
        }
        */
        $html .= $dynamic_area;
        
        $html .= '</form>';
        
        $form = Form::populate_admin_form($html, $populate_array);
        
        return $form;
    }
}

?>