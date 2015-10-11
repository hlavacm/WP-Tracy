<?php

namespace WpTracy;

/**
 * Custom panel based on global $wp_rewrite variable
 * 
 * @author Martin Hlaváč
 * @link http://www.ktstudio.cz/
 */
class WpRewritePanel extends WpPanelBase {

    public function getTab() {
        return parent::getSimpleTab(__("Rewrite"));
    }

    public function getPanel() {
        /* @var $wpdb \WP_Rewrite */
        global $wp_rewrite;
        $output = parent::getObjectPanel($wp_rewrite);
        return $output;
    }

}
