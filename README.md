# Tracy on Wordpress - WP-Tracy

[Tracy](https://github.com/nette/tracy) is an excellent PHP debugger bar from [Nette](https://nette.org) PHP framework. 
WP Tracy is port to [WordPress](https://wordpress.org) for test environment.
When it's activated, it automatically shows Tracy bar and displays within global WP constants and their values.

![WP Tracy](https://ktstudio.github.io/images/wp-tracy.png "Tracy bar auto-display after plugin activation")
![WP Tracy exception](https://ktstudio.github.io/images/wp-tracy-exception.png "Tracy exception dialog when is occured")

## Installation

Use command on your path:

```
composer require ktstudio/wp-tracy
```

## Configuration

### Code

1. You can optionally define PHP (boolean) constant WP_TRACY_CHECK_USER_LOGGED_IN to check only logged users...
2. You can optionally define PHP constant WP_TRACY_ENABLE_MODE to set Tracy\Debugger::enable($mode)...
3. You can optionally use wp_tracy_panels_filter to modify default panels array (full class names)

### Settings page

Composer bootstrap process for "WP Tracy" will activate the plugin and load the settings page automatically - the configuration will be acessible inside "Settings" menu.

![shop dev wmc lv_wp-admin_options-general php_page tracy_debugger settings-updated true](https://user-images.githubusercontent.com/477326/33425087-5d8c6418-d5c6-11e7-9361-de0a50f58072.png "View of settings page")
