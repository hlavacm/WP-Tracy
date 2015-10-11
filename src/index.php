<?php

if (defined("WP_TRACY_CHECK_USER_LOGGED_IN") && WP_TRACY_CHECK_USER_LOGGED_IN && is_user_logged_in()) {
    return; // cancel for anonymous users
}

Tracy\Debugger::enable(); // hooray, enabling debugging using Tracy

// panels in the correct order
$panels = array(
    "WpTracy\\WpPanel",
    "WpTracy\\WpUserPanel",
    "WpTracy\\WpPostPanel",
    "WpTracy\\WpQueryPanel",
    "WpTracy\\WpDbPanel",
    "WpTracy\\WpRewritePanel",
);

// panels registration
foreach ($panels as $className) {
    Tracy\Debugger::getBar()->addPanel(new $className);
}
