<?php

namespace WpTracy;

/**
 * Custom panel based on global $wp_query variable
 * 
 * @author Martin Hlaváč
 * @link http://www.ktstudio.cz/
 */
class WpQueryPanel extends WpPanelBase {

    public function getTab() {
        return parent::getSimpleTab(__("Query"));
    }

    public function getPanel() {
        /* @var $wpdb \WP_Query */
        global $wp_query;
        $output = parent::getTablePanel(array("Request" => $wp_query->request));
        $output .= parent::getObjectPanel($wp_query);
        return $output;
    }

}
