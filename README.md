# WP Tracy

[Tracy](https://github.com/nette/tracy) is an excellent PHP debugger bar from [Nette](https://nette.org) PHP framework. 
WP Tracy is port to [WordPress](https://wordpress.org) for test environment.
When it's activated, it automatically shows Tracy bar and displays within global WP constants and their values.

## Installation

1. Use command on your path: composer require ktstudio/wp-tracy
2. Profit!
3. You can optionally define PHP (boolean) constant WP_TRACY_CHECK_USER_LOGGED_IN to check only logged users...
4. You can optionally define PHP constant WP_TRACY_ENABLE_MODE to set Tracy\Debugger::enable($mode)...
5. You can optionally use wp_tracy_panels_filter to modify default panels array (full class names)

![WP Tracy](https://ktstudio.github.io/images/wp-tracy.png "Tracy bar auto-display after plugin activation")
![WP Tracy exception](https://ktstudio.github.io/images/wp-tracy-exception.png "Tracy exception dialog when is occured")

---

Copyright © KTStudio.cz 2015
