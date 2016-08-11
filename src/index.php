<?php

add_action("init", "wp_tracy_init_action", 1);

function wp_tracy_init_action() {
    if (defined("DOING_AJAX") && DOING_AJAX) {
        return; // for IE compatibility WordPress media upload
    }

    if (defined("WP_TRACY_CHECK_USER_LOGGED_IN") && WP_TRACY_CHECK_USER_LOGGED_IN && is_user_logged_in()) {
        return; // cancel for anonymous users
    }

    Tracy\Debugger::enable(defined("WP_TRACY_ENABLE_MODE") ? WP_TRACY_ENABLE_MODE : null); // hooray, enabling debugging using Tracy
    // panels in the correct order
    $defaultPanels = array(
        "WpTracy\\WpPanel",
        "WpTracy\\WpUserPanel",
        "WpTracy\\WpPostPanel",
        "WpTracy\\WpQueryPanel",
        "WpTracy\\WpQueriedObjectPanel",
        "WpTracy\\WpDbPanel",
        "WpTracy\\WpRewritePanel",
    );
    $panels = apply_filters("wp_tracy_panels_filter", $defaultPanels);

    // panels registration
    foreach ($panels as $className) {
        Tracy\Debugger::getBar()->addPanel(new $className);
    }
}
