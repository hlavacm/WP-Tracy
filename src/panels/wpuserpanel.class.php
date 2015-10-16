<?php

namespace WpTracy;

use Tracy\Debugger;

/**
 * Custom panel based on global $wp_user variable
 * 
 * @author Martin Hlaváč
 * @link http://www.ktstudio.cz/
 */
class WpUserPanel extends WpPanelBase {

    public function getTab() {
        if (is_user_logged_in()) {
            return parent::getSimpleTab(__("User"));
        }
        return null;
    }

    public function getPanel() {
        $output = null;
        if (is_user_logged_in()) {
            $currentUser = wp_get_current_user();
            $output = parent::getTablePanel(array(
                        __("ID") => $currentUser->ID,
                        __("Login") => $currentUser->user_login,
                        __("E-mail") => $currentUser->user_email,
                        __("Display Name") => $currentUser->display_name,
                        __("First Name") => $currentUser->first_name,
                        __("Last Name") => $currentUser->last_name,
                        __("Roles") => Debugger::dump($currentUser->roles, true),
                        __("Allcaps") => Debugger::dump($currentUser->allcaps, true),
            ));
        }
        return $output;
    }

}
