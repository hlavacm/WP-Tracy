<?php
use Tracy\Debugger;

if (function_exists('add_action')) {
    
    // Init tracy
    add_action("init", "wp_tracy_init_action", 1);
    
    // Default options
    $options = get_option('tracyDebugger_settings');
    $changes = false;
    if (!isset($options['tracyDebugger_checkbox_disableForGuests'])) {
        $options['tracyDebugger_checkbox_disableForGuests'] = 1;
        $changes = true;
    }
    if (!isset($options['tracyDebugger_checkbox_strictMode'])) {
        $options['tracyDebugger_checkbox_strictMode'] = 0;
        $changes = true;
    }
    if (!isset($options['tracyDebugger_checkbox_showBar'])) {
        $options['tracyDebugger_checkbox_showBar'] = 0;
        $changes = true;
    }
    if (!isset($options['tracyDebugger_text_email'])) {
        $options['tracyDebugger_text_email'] = '';
        $changes = true;
    }
    if (!isset($options['tracyDebugger_text_maxDepth'])) {
        $options['tracyDebugger_text_maxDepth'] = 3;
        $changes = true;
    }
    if (!isset($options['tracyDebugger_text_maxLength'])) {
        $options['tracyDebugger_text_maxLength'] = 150;
        $changes = true;
    }
    if (!isset($options['tracyDebugger_checkbox_showLocation'])) {
        $options['tracyDebugger_checkbox_showLocation'] = 0;
        $changes = true;
    }
    if (!isset($options['tracyDebugger_text_logDirectory'])) {
        $options['tracyDebugger_text_logDirectory'] = 'logs';
        $changes = true;
    }
    if (!isset($options['tracyDebugger_select_debuggerMode'])) {
        $options['tracyDebugger_select_debuggerMode'] = '1';
        $changes = true;
    }
    if (!isset($options['tracyDebugger_select_enabledPanels'])) {
        $options['tracyDebugger_select_enabledPanels'] = [
            'WpPanel',
            'WpUserPanel',
            'WpPostPanel',
            'WpQueryPanel',
            'WpQueriedObjectPanel',
            'WpDbPanel',
            'WpRewritePanel',
        ];
        $changes = true;
    }
    if ($changes) {
        update_option('tracyDebugger_settings', $options);
    }
    
    // Configure
    Debugger::$strictMode = ($options['tracyDebugger_checkbox_strictMode'] == 1);
    Debugger::$showBar = ($options['tracyDebugger_checkbox_showBar'] == 1);
    if (strlen($options['tracyDebugger_text_email']) > 0) {
        Debugger::$email = $options['tracyDebugger_text_email'];
    }
    Debugger::$showLocation = ($options['tracyDebugger_checkbox_showLocation'] == 1);
    if (strlen($options['tracyDebugger_text_maxDepth']) > 0) {
        Debugger::$maxDepth = $options['tracyDebugger_text_maxDepth'];
    }
    if (strlen($options['tracyDebugger_text_maxLength']) > 0) {
        Debugger::$maxLength = $options['tracyDebugger_text_maxLength'];
    }
    if (strlen($options['tracyDebugger_text_logDirectory']) > 0) {
        $logDirectory = realpath(ABSPATH . $options['tracyDebugger_text_logDirectory']);
        if (is_dir($logDirectory)) {
            Debugger::$logDirectory = $logDirectory;
        }
    }
    switch ($options['tracyDebugger_select_debuggerMode']) {
        case '1':
            if (WP_DEBUG) {
                define('WP_TRACY_ENABLE_MODE', Debugger::DEVELOPMENT);
            } else {
                define('WP_TRACY_ENABLE_MODE', Debugger::PRODUCTION);
            }
            break;
        case '2':
            define('WP_TRACY_ENABLE_MODE', Debugger::PRODUCTION);
            break;
        case '3':
            define('WP_TRACY_ENABLE_MODE', Debugger::DEVELOPMENT);
            break;
        case '4':
        default:
            define('WP_TRACY_ENABLE_MODE', Debugger::DETECT);
            break;
    }
    
    // Settings page
    add_action('admin_menu', 'tracyDebugger_add_admin_menu');
    add_action('admin_init', 'tracyDebugger_settings_init');
}


function wp_tracy_init_action()
{
    if (defined("DOING_AJAX") && DOING_AJAX) {
        return; // for IE compatibility WordPress media upload
    }
    $options = get_option('tracyDebugger_settings');
    if (
        (
            $options['tracyDebugger_checkbox_disableForGuests'] == 1 ||
            (defined("WP_TRACY_CHECK_USER_LOGGED_IN") && WP_TRACY_CHECK_USER_LOGGED_IN)
        ) &&
        !is_user_logged_in()
    ) {
        return; // cancel for anonymous users
    }
    Debugger::enable(defined("WP_TRACY_ENABLE_MODE") ? WP_TRACY_ENABLE_MODE :
        null); // hooray, enabling debugging using Tracy
    // panels in the correct order
    $defaultPanels = [];
    if (tracyDebugger_isPanelSelected('WpPanel', $options)) {
        $defaultPanels[] = "WpTracy\\WpPanel";
    }
    if (tracyDebugger_isPanelSelected('WpUserPanel', $options)) {
        $defaultPanels[] = "WpTracy\\WpUserPanel";
    }
    if (tracyDebugger_isPanelSelected('WpPostPanel', $options)) {
        $defaultPanels[] = "WpTracy\\WpPostPanel";
    }
    if (tracyDebugger_isPanelSelected('WpQueryPanel', $options)) {
        $defaultPanels[] = "WpTracy\\WpQueryPanel";
    }
    if (tracyDebugger_isPanelSelected('WpQueriedObjectPanel', $options)) {
        $defaultPanels[] = "WpTracy\\WpQueriedObjectPanel";
    }
    if (tracyDebugger_isPanelSelected('WpDbPanel', $options)) {
        $defaultPanels[] = "WpTracy\\WpDbPanel";
    }
    if (tracyDebugger_isPanelSelected('WpRewritePanel', $options)) {
        $defaultPanels[] = "WpTracy\\WpRewritePanel";
    }
    $panels = apply_filters("wp_tracy_panels_filter", $defaultPanels);
    // panels registration
    foreach ($panels as $className) {
        Debugger::getBar()->addPanel(new $className);
    }
}

function tracyDebugger_add_admin_menu()
{
    add_options_page('Tracy debugger', 'Tracy debugger', 'manage_options', 'tracy_debugger',
        'tracyDebugger_options_page');
}

function tracyDebugger_settings_init()
{
    register_setting('pluginPage', 'tracyDebugger_settings');
    add_settings_field(
        'tracyDebugger_select_debuggerMode',
        __('Debugger mode', 'tracyDebugger'),
        'tracyDebugger_select_debuggerMode_render',
        'pluginPage',
        'tracyDebugger_pluginPage_section'
    );
    add_settings_section(
        'tracyDebugger_pluginPage_section',
        __('Configuration', 'tracyDebugger'),
        'tracyDebugger_settings_section_callback',
        'pluginPage'
    );
    add_settings_field(
        'tracyDebugger_checkbox_disableForGuests',
        __('Disable for guests', 'tracyDebugger'),
        'tracyDebugger_checkbox_disableForGuests_render',
        'pluginPage',
        'tracyDebugger_pluginPage_section'
    );
    add_settings_field(
        'tracyDebugger_checkbox_showBar',
        __('Show bar', 'tracyDebugger'),
        'tracyDebugger_checkbox_showBar_render',
        'pluginPage',
        'tracyDebugger_pluginPage_section'
    );
    add_settings_field(
        'tracyDebugger_checkbox_strictMode',
        __('Strict mode', 'tracyDebugger'),
        'tracyDebugger_checkbox_strictMode_render',
        'pluginPage',
        'tracyDebugger_pluginPage_section'
    );
    add_settings_field(
        'tracyDebugger_text_email',
        __('E-mail', 'tracyDebugger'),
        'tracyDebugger_text_email_render',
        'pluginPage',
        'tracyDebugger_pluginPage_section'
    );
    add_settings_field(
        'tracyDebugger_text_maxDepth',
        __('Max depth', 'tracyDebugger'),
        'tracyDebugger_text_maxDepth_render',
        'pluginPage',
        'tracyDebugger_pluginPage_section'
    );
    add_settings_field(
        'tracyDebugger_text_maxLength',
        __('Max length', 'tracyDebugger'),
        'tracyDebugger_text_maxLength_render',
        'pluginPage',
        'tracyDebugger_pluginPage_section'
    );
    add_settings_field(
        'tracyDebugger_checkbox_showLocation',
        __('Show file location', 'tracyDebugger'),
        'tracyDebugger_checkbox_showLocation_render',
        'pluginPage',
        'tracyDebugger_pluginPage_section'
    );
    add_settings_field(
        'tracyDebugger_text_logDirectory',
        __('Log directory', 'tracyDebugger'),
        'tracyDebugger_text_logDirectory_render',
        'pluginPage',
        'tracyDebugger_pluginPage_section'
    );
    add_settings_field(
        'tracyDebugger_select_enabledPanels',
        __('Additional Wordpress panels', 'tracyDebugger'),
        'tracyDebugger_select_enabledPanels_render',
        'pluginPage',
        'tracyDebugger_pluginPage_section'
    );
}

function tracyDebugger_checkbox_disableForGuests_render()
{
    $options = get_option('tracyDebugger_settings');
    ?>
    <input type='hidden' name='tracyDebugger_settings[tracyDebugger_checkbox_disableForGuests]' value='0' />
    <input type='checkbox'
           name='tracyDebugger_settings[tracyDebugger_checkbox_disableForGuests]' <?php checked($options['tracyDebugger_checkbox_disableForGuests'],
        1); ?> value='1'>
    <p class="description"><?php
        echo __(
            'Disables Debugger for unauthorised users.',
            'tracyDebugger'
        );
        ?></p>
    <?php
}

function tracyDebugger_checkbox_showBar_render()
{
    $options = get_option('tracyDebugger_settings');
    ?>
    <input type='checkbox'
           name='tracyDebugger_settings[tracyDebugger_checkbox_showBar]' <?php checked($options['tracyDebugger_checkbox_showBar'],
        1); ?> value='1'>
    <p class="description"><?php
        echo __(
            'The Debugger Bar is a floating panel. It is displayed in the bottom right corner of a page. You can move it using the mouse. It will remember its position after the page reloading.',
            'tracyDebugger'
        );
        ?></p>
    <?php
}

function tracyDebugger_checkbox_strictMode_render()
{
    $options = get_option('tracyDebugger_settings');
    ?>
    <input type='checkbox'
           name='tracyDebugger_settings[tracyDebugger_checkbox_strictMode]' <?php checked($options['tracyDebugger_checkbox_strictMode'],
        1); ?> value='1'>
    <p class="description"><?php
        echo __(
            'Errors like a typo in a variable name or an attempt to open a nonexistent file generate reports of E_NOTICE or E_WARNING level. These can be easily overlooked and/or can be completely hidden in a web page graphic layout. Let Tracy manage them or enable strict mode and they will be displayed like errors.',
            'tracyDebugger'
        );
        ?></p>
    <?php
}

function tracyDebugger_checkbox_showLocation_render()
{
    $options = get_option('tracyDebugger_settings');
    ?>
    <input type='checkbox'
           name='tracyDebugger_settings[tracyDebugger_checkbox_showLocation]' <?php checked($options['tracyDebugger_checkbox_showLocation'],
        1); ?> value='1'>
    <p class="description"><?php
        echo __(
            'The <code>dump()</code> function can display other useful information. Enabling this, adds a tooltip to every dumped object containing additional location information in file system.',
            'tracyDebugger'
        );
        ?></p>
    <?php
}

function tracyDebugger_text_email_render()
{
    $options = get_option('tracyDebugger_settings');
    ?>
    <input type='text' name='tracyDebugger_settings[tracyDebugger_text_email]'
           value='<?php echo $options['tracyDebugger_text_email']; ?>'>
    <p class="description"><?php
        echo __(
            'For a real professional the error log is a crucial source of information and he or she wants to be notified about any new error immediately. Tracy helps him. She is capable of sending an email for every new error record.',
            'tracyDebugger'
        );
        ?></p>
    <?php
}

function tracyDebugger_text_maxDepth_render()
{
    $options = get_option('tracyDebugger_settings');
    ?>
    <input type='text' name='tracyDebugger_settings[tracyDebugger_text_maxDepth]'
           value='<?php echo $options['tracyDebugger_text_maxDepth']; ?>'>
    <p class="description"><?php
        echo __(
            'For variable dumping, you can change the nesting depth. Naturally, lower values accelerate Tracy rendering.',
            'tracyDebugger'
        );
        ?></p>
    <?php
}

function tracyDebugger_text_maxLength_render()
{
    $options = get_option('tracyDebugger_settings');
    ?>
    <input type='text' name='tracyDebugger_settings[tracyDebugger_text_maxLength]'
           value='<?php echo $options['tracyDebugger_text_maxLength']; ?>'>
    <p class="description"><?php
        echo __(
            'For variable dumping, you can change the displayed strings length. Naturally, lower values accelerate Tracy rendering.',
            'tracyDebugger'
        );
        ?></p>
    <?php
}

function tracyDebugger_text_logDirectory_render()
{
    $options = get_option('tracyDebugger_settings');
    ?>
    <input type='text' name='tracyDebugger_settings[tracyDebugger_text_logDirectory]'
           value='<?php echo $options['tracyDebugger_text_logDirectory']; ?>'>
    <p class="description"><?php
        echo __(
            'Name of the directory where errors should be logged (relative to the project root).',
            'tracyDebugger'
        );
        ?></p>
    <p class="description"><?php
        echo __(
            'Current location',
            'tracyDebugger'
        );
        ?>: <?php
            if (strlen($options['tracyDebugger_text_logDirectory']) > 0) {
                $logDirectory = realpath(ABSPATH . $options['tracyDebugger_text_logDirectory']);
                if (is_dir($logDirectory)) {
                    echo '<code>' . $logDirectory . '</code>';
                } else {
                    echo '<code>' . ABSPATH . $options['tracyDebugger_text_logDirectory'] . '</code> (' . __(
                        'does not exist!',
                        'tracyDebugger'
                    ) . ')';
                }
            } else {
                echo '-';
            }
            ?></p>
    <?php
}

function tracyDebugger_select_debuggerMode_render()
{
    $options = get_option('tracyDebugger_settings');
    ?>
    <select name='tracyDebugger_settings[tracyDebugger_select_debuggerMode]'>
        <option value='1' <?php selected($options['tracyDebugger_select_debuggerMode'],
            1); ?>><?php echo __('Depends on WP_DEBUG', 'tracyDebugger') ?></option>
        <option value='2' <?php selected($options['tracyDebugger_select_debuggerMode'],
            2); ?>><?php echo __('Production', 'tracyDebugger') ?></option>
        <option value='3' <?php selected($options['tracyDebugger_select_debuggerMode'],
            3); ?>><?php echo __('Development', 'tracyDebugger') ?></option>
        <option value='4' <?php selected($options['tracyDebugger_select_debuggerMode'],
            3); ?>><?php echo __('Auto-detect - enabled only on localhost', 'tracyDebugger') ?></option>
    </select>
    <?php
}

function tracyDebugger_isPanelSelected($name, &$options)
{
    return in_array($name, $options['tracyDebugger_select_enabledPanels']);
}

function tracyDebugger_select_enabledPanels_render()
{
    $options = get_option('tracyDebugger_settings');
    ?>
    <select name="tracyDebugger_settings[tracyDebugger_select_enabledPanels][]" size="7" multiple>
        <option value="WpPanel" <?php selected(tracyDebugger_isPanelSelected('WpPanel', $options), true); ?>><?php echo __('WP', 'tracyDebugger') ?></option>
        <option value="WpUserPanel" <?php selected(tracyDebugger_isPanelSelected('WpUserPanel', $options), true); ?>><?php echo __('User', 'tracyDebugger') ?></option>
        <option value="WpPostPanel" <?php selected(tracyDebugger_isPanelSelected('WpPostPanel', $options), true); ?>><?php echo __('Post', 'tracyDebugger') ?></option>
        <option value="WpQueryPanel" <?php selected(tracyDebugger_isPanelSelected('WpQueryPanel', $options), true); ?>><?php echo __('Query', 'tracyDebugger') ?></option>
        <option value="WpQueriedObjectPanel" <?php selected(tracyDebugger_isPanelSelected('WpQueriedObjectPanel', $options), true); ?>><?php echo __('Queried object', 'tracyDebugger') ?></option>
        <option value="WpDbPanel" <?php selected(tracyDebugger_isPanelSelected('WpDbPanel', $options), true); ?>><?php echo __('DB', 'tracyDebugger') ?></option>
        <option value="WpRewritePanel" <?php selected(tracyDebugger_isPanelSelected('WpRewritePanel', $options), true); ?>><?php echo __('Rewrite', 'tracyDebugger') ?></option>
    </select>
    <p class="description"><?php
        echo __(
            'Use <kbd>Ctrl</kbd> or <kbd>Shift</kbd> to select multiple options or drag over with mouse.',
            'tracyDebugger'
        );
        ?></p>
    <?php
}

function tracyDebugger_settings_section_callback()
{
    echo __(
        '<p>Tracy library is a useful helper for everyday PHP programmers. It helps you&nbsp;to:</p><ol><li>quickly detect and correct errors</li><li>log errors</li><li>dump variables</li><li>measure execution time and memory consumption</li></ol><p>Read more about Tracy in their <a href="https://tracy.nette.org/" target="_blank">website</a>.</p>',
        'tracyDebugger'
    );
}

function tracyDebugger_options_page()
{
    ?>
    <form action='options.php' method='post'>
        <h2><?php echo __('Tracy debugger', 'tracyDebugger') ?></h2>
        <?php
        settings_fields('pluginPage');
        do_settings_sections('pluginPage');
        submit_button();
        ?>
    </form>
    <?php
}
