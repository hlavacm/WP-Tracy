<?php

namespace WpTracy;

/**
 * Custom panel based on global $wp_rewrite variable
 *
 * @author Martin Hlaváč
 */
class WpRewritePanel extends WpPanelBase
{
    public function getTab()
    {
        return parent::getSimpleTab(__("Rewrite"));
    }

    public function getPanel()
    {
        /* @var $wp_rewrite \WP_Rewrite */
        global $wp_rewrite;
        $output = parent::getObjectPanel($wp_rewrite);
        return $output;
    }
}
