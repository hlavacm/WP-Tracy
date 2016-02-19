<?php

namespace WpTracy;

/**
 * Custom panel based on result of function get_queried_object()
 * 
 * @author Martin Hlaváč
 * @link http://www.ktstudio.cz/
 */
class WpQueriedObjectPanel extends WpPanelBase {

    public function getTab() {
        $queriedObject = get_queried_object();
        if (self::issetAndNotEmpty($queriedObject)) {
            return parent::getSimpleTab(__("Queried Object"));
        }
        return null;
    }

    public function getPanel() {
        $output = parent::getObjectPanel(get_queried_object());
        return $output;
    }

}
