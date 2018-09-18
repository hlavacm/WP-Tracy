<?php

namespace WpTracy;

/**
 * Custom panel based on global $wpdb variable
 *
 * @author Martin Hlaváč
 */
class WpDbPanel extends WpPanelBase
{
    public function getTab()
    {
        return parent::getSimpleTab(__("DB"));
    }

    public function getPanel()
    {
        /* @var $wpdb \WPDB */
        global $wpdb;
        $output = parent::getTablePanel([
            "Last Query" => $wpdb->last_query,
        ]);
        $output .= parent::getObjectPanel($wpdb);
        return $output;
    }
}
