<?php

namespace WpTracy;

/**
 * Custom panel based on global $wpdb variable
 * 
 * @author Martin Hlaváč
 * @link http://www.ktstudio.cz/
 */
class WpDbPanel extends WpPanelBase {

    public function getTab() {
        return parent::getSimpleTab(__("DB"));
    }

    public function getPanel() {
        /* @var $wpdb \WPDB */
        global $wpdb;
        $output = parent::getTablePanel(array(
                    "Last Query" => $wpdb->last_query,
                    "Func Call" => $wpdb->func_call,
        ));
        $output .= parent::getObjectPanel($wpdb);
        return $output;
    }

}
