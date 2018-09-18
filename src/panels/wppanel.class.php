<?php

namespace WpTracy;

use Tracy\Debugger;

/**
 * Custom panel based on global $wp variable + other global (versions) variables
 *
 * @author Martin Hlaváč
 */
class WpPanel extends WpPanelBase
{
    public function getTab()
    {
        return parent::getSimpleTab(__("WP"), null, "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAHdElNRQfiCQkLEyXB/kfmAAABbklEQVQozz3RPUjUcQCA4ef/Oy89yCO7LA61oVsqOKzLoMUCU3NosJCG3BpqC8KltraWiqMpaJKWimgo+vCOK7jQ6OOwpYQEG0yTSJLI6zK9Goz2l3d5IuinUZ8h7eoIPrmr6FcBUT+kjUh5oOIbWuxz1Bd5C+tB2iWTbqhSWP/R6rq40xZimQ0uWnJTJKYtk8gkpXw3YERWo1KDw4b91OWyGWf1+e2WvLgmDHocHDepw2YV065J2GnKsqIJdW8cC7bLm7NbDjOeaTAgsmhU1SMdQeSV55ocwZoSDmrDtPfGhYCa+/7o0YptqnY4gK3GzYuCIKXsg11ymm1yW9yASKenmoVgVpd5RY16Zc0ZVdWt0xYv7Tcby9QNKqg5IWlN2Vs9slbNmXDOvWDMojMqJu2R9c6yh+KGvHbKorFgxVV7DSvjhR944qslh+RcsRIw77x23ZZM2Sip5qMWCRd8JvrP3euktNV/3HeU1rn/Av+FclJF48KxAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTA5LTA5VDExOjE5OjM3LTA3OjAwvabE/AAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOC0wOS0wOVQxMToxOTozNy0wNzowMMz7fEAAAAAZdEVYdFNvZnR3YXJlAEFkb2JlIEltYWdlUmVhZHlxyWU8AAAAAElFTkSuQmCC");
    }

    public function getPanel()
    {
        /* @var $wpdb \WP */
        global $wp;
        global $wp_version;
        global $wp_db_version;
        global $tinymce_version;
        global $required_php_version;
        global $required_mysql_version;
        global $pagenow;
        $output = parent::getTablePanel([
            __("WP Version") => $wp_version,
            __("WP DB Version ") => $wp_db_version,
            __("TinyMCE Version ") => $tinymce_version,
            __("Required PHP Version") => $required_php_version,
            __("Required MySQL Version") => $required_mysql_version,
            __("Page Now") => $pagenow,
            __("WP") => Debugger::dump($wp, true),
        ]);
        return $output;
    }
}
