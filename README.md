# WP Tracy

[Tracy](https://github.com/nette/tracy) is an excellent PHP debugger bar from [Nette](https://nette.org) PHP framework. 

WP Tracy is port to [WordPress](https://wordpress.org) for test environment. 

When it's activated, it automatically shows Tracy bar and displays within global WP constants and their values.

## Installation & Configuration

1. Use command on your path: `composer require hlavacm/wp-tracy`
2. Profit!
3. You can optionally define custom PHP constants in the code, but they must be defined in the action `init` with priority 1.
   - WP_TRACY_ADMIN_DISABLED - `true`
   - WP_TRACY_CHECK_IS_USER_LOGGED_IN - `on`/`off`
   - WP_TRACY_ONLY_FOR_USER_ID - `some (existing) user ID (as a number)`
   - WP_TRACY_ENABLE_MODE - `detect`/`development`/`production`
   - WP_TRACY_PANELS_FILTERING_ALLOWED - `on`/`off`
4. You can optionally define custom options `wp-tracy-user-settings` as serialized array of keys and values:
   - `check-is-user-logged-in` => `on`/`off`
   - `only-for-user-id` => some (existing) user ID (as number)
   - `debugger-mode` => `detect`/`development`/`production`
   - `panels-filtering-allowed` => `on`/`off`
5. You can optionally use `wp_tracy_panels_filter` to modify default panels array (full class names)
6. The following panels are visible by default (if they are available):
   - `WpTracy\\WpPanel`
   - `WpTracy\\WpUserPanel`
   - `WpTracy\\WpPostPanel`
   - `WpTracy\\WpQueryPanel`
   - `WpTracy\\WpQueriedObjectPanel`
   - `WpTracy\\WpDbPanel`
   - `WpTracy\\WpRolesPanel`
   - `WpTracy\\WpRewritePanel`
   - `WpTracy\\WpCurrentScreenPanel`

![WP Tracy](https://hlavacm.github.io/images/wp-tracy.png "Tracy bar auto-display after plugin activation")
![WP Tracy exception](https://hlavacm.github.io/images/wp-tracy-exception.png "Tracy exception dialog when is occured")

---

Copyright © [Martin Hlaváč](https://www.hlavacm.net) 2015-2018
