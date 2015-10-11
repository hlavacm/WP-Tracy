<?php

namespace WpTracy;

use Tracy\Debugger;

/**
 * Custom panel based on global $wp variable + other global (versions) variables
 * 
 * @author Martin Hlaváč
 * @link http://www.ktstudio.cz/
 */
class WpPanel extends WpPanelBase {

    public function getTab() {
        return parent::getSimpleTab(__("WP"), null, "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABtUlEQVR42nXTXyjdYRzH8XN0TqZ2oUV2pDXadKwVyR3TtHGlhBuJ3BjGKIq4UBZtDZ3ajb8XUv7kjuGKFEVpKf9KmlxQRgm1FJua97c+v/pp89Src3p+3+f7PL/v8/15Pf+OVKTjj9jwwY/vWHUHe13/H6AaO4hDHhL17AemsY9k9ODSncAWN2IZg0jw/H8coAyvELIkToJ6rOMbHnruHxMoRjg+oMsSvEQsDhGDfAW1Ygt/8RYf0aZfj+Z+WYIa7GIBYfiJKFwjgHOsqbhHCNpClCDSElTiDQYwrxqUa5cq9KMZnzXXgCGMYcoSVOA9NlUgO9qcgi1htoq6p6KPYAbt+OIkqNSVPcaVXiMaX9GBU/VAGrrxHEnodF7hNYpQqh36NF+HRypck+2IQh3fTjnrVfNcYFSTOarJMMZ1K88Qr1O0qF7vEOFVVYM6qv0+wbECrZhP1dorqEWuElofnDiNZEfdwCQ+WYMgE4t63q8bCajVC5Bi3egk8OuqlnQjRSpgnZ6faXEWfiNDm1y5Pya/Fh+opUPqeYuxj6lRlX+BXt3Wna/RGUEV8UZt7MT59Erb7uBbAThl6Btg2vYAAAAASUVORK5CYII=");
    }

    public function getPanel() {
        /* @var $wpdb \WP */
        global $wp;
        global $wp_version;
        global $wp_db_version;
        global $tinymce_version;
        global $required_php_version;
        global $required_mysql_version;
        global $pagenow;
        $output = parent::getTablePanel(array(
                    __("WP Version") => $wp_version,
                    __("WP DB Version ") => $wp_db_version,
                    __("TinyMCE Version ") => $tinymce_version,
                    __("Required PHP Version") => $required_php_version,
                    __("Required MySQL Version") => $required_mysql_version,
                    __("Page Now") => $pagenow,
                    __("WP") => Debugger::dump($wp, true),
        ));
        return $output;
    }

}
