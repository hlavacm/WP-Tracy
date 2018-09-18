<?php

namespace WpTracy;

/**
 * Custom panel based on result of function get_queried_object()
 *
 * @author Martin Hlaváč
 */
class WpQueriedObjectPanel extends WpPanelBase
{
    public function getTab()
    {
        global $post;
        $queriedObject = get_queried_object();
        if (!empty($queriedObject) && $queriedObject !== $post) {
            return parent::getSimpleTab(__("Queried Object"));
        }
        return null;
    }

    public function getPanel()
    {
        $output = parent::getObjectPanel(get_queried_object());
        return $output;
    }
}
