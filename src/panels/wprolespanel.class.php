<?php

namespace WpTracy;

/**
 * Custom panel based on global $wp_roles variable
 *
 * @author Martin Hlaváč
 */
class WpRolesPanel extends WpPanelBase
{
    public function getTab()
    {
        return parent::getSimpleTab(__("Roles"));
    }

    public function getPanel()
    {
        /* @var $wp_roles \WP_Roles */
        global $wp_roles;
        $output = parent::getObjectPanel($wp_roles);
        return $output;
    }
}
