<?php

namespace WpTracy;

/**
 * Custom panel based on global $current_screen variable
 *
 * @author Martin Hlaváč
 */
class WpCurrentScreenPanel extends WpPanelBase
{
    public function getTab()
    {
        global $current_screen;
        if (!empty($current_screen)) {
            return parent::getSimpleTab(__("Current Screen"));
        }
        return null;
    }

    public function getPanel()
    {
        /** @var $current_screen \WP_Screen */
        global $current_screen;
        $output = parent::getObjectPanel($current_screen);
        return $output;
    }
}
