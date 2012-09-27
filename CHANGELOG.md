## CHANGELOG

### v0.4.1 (open source) - *09/26/2012*
- Bug in `/js/filedrop.js` which prevents file uploads. Rolled-back to old working version.
- `/recipes.php` added a tooltip to the filter recipes field.

### v0.4.0 (open source) - *09/26/2012*
- **Added Files feature. Upload, view, and download files. Supports plain text and binary.**
- **Ability to include other recipes in a recipe via rMarkup. See [README.md](https://github.com/nodesocket/commando#recipe-markup-rmarkup) for rMarkup details, syntax and specification.**
- **Added raw view and download for recipes. Raw view outputs the recipe in plain text.**
- Upgraded Bootstrap to `2.1.1`.
- Fixed a **major bug** in executions with multi-line recipes. The fix was to replace `\r\n` with `\n`.
- Filter recipes by ID, label, or interpreter.
- `/classes/Navigation.php` added icons to pages in header navigation.
- `/recipes.php` now lists the ID in the table with the option to expand.
- `/servers.php` sorts groups alphabetically by name. The `default` group is forced to the bottom of the page.
- `/servers.php` lists the number of servers in each group next to the group name.
- Added interpreter type to the list of recipes in `/execute.php`.
- Added loading progress bar when viewing a recipe (support for large recipes).
- Copy a recipe ID to your clipboard in `/view-recipe.php`.
- Fixed bug in `/classes/Error.php`, now escapes double quotes in returned JSON.
- Increased the default height of all notes text-areas.
- Updated `/index.php`, removed GitHub fork ribbon, and restyled. Added Files box. Added logo in `hero-unit.`
- Added `/img/main-header.png`.
- `/view-recipe.php` now checks to make sure the recipe exists. If not, returns an error.
- Removed connecting to MongoDB and selecting the collection in `/execute.php`. This is now handled in `/actions/ssh_execute.php`.
- Added `/timezone.php` which simply sets the timezone to UTC.
- `/settings.php` *is daylight savings* uses a new fancy toggle button.
- Fixed typo/bug in `generate_id()` in `/classes/Functions.php`.
- Added GridFS functionality to `/classes/MongoConnection.php`.
- Fixed `/classes/Functions.php` `formatBytes()` to be more accurate.
- Added `timezone_offset_in_seconds()` and `add_ellipsis_reverse()` to `/classes/Functions.php`.
- `get_recipes()` in `/classes/MySQLQueries.php` now optionally accepts an array of recipes.
- `auth()` in `/classes/SSH.php` now returns `true` if successful.
- `/classes/CSRF.php` supports multiple requests on the same page.
- `/actions/metrics.php` now sends the running version of *Commando.io*.
- `/controller.php` checks if the included page exists with `file_exists()`.
- Reordered/refactored `/classes/Requires.php`.
- `/classes/Requires.php` checks if `/app.config.php` exists with `file_exists()`.
- Reordered/refactored `/classes/Prerequisites.php`.
- `/css/additional-styles.css` refactored class `box-red` and added class `box-green`. Added class `expand-east` and `expand-west`.
- Removed the class `well-small` from `/groups.php` and `/servers.php`.
- Removed icon `chevron-up` in `/edit-recipe.php`.
- Updated `CodeMirror` and all modes to the latest version.
- Updated `/js/chosen.js` and `/css/chosen.css` to the latest versions.
- Updated `/js/bootbox.js` to the latest version.
- Updated `/js/autosize.js` to the latest version.
- Added `/js/code-pretty/lang-yaml.js`.
- Added `/js/code-pretty/lang-xq.js`.
- Added `/js/code-pretty/lang-wiki.js`.
- Added `/robots.txt` disallows all robots.

### v0.3.4 (open source) - *08/27/2012*
- Added `instance_key` which is a unique identifier *(30 characters)* generated for every open source install of Commando.io.
- Added `/actions/metrics.php` which sends the `instance_key`, number of servers added, and clients IP address to *MixPanel*. These metrics are used to help us gauge the number of open source installations of Commando.io out in the wild.
- Added `/js/index.js` which makes an AJAX request to `/actions/metrics.php`.
- Replaced `gmmktime()` with `time()` in `/classes/Functions.php` when generating a MongoDB date. Prevents PHP showing a depreciated notice about using `gmmktime()`.

### v0.3.3 (open source) - *08/26/2012*
- The ability to promote older versions of recipes to head.
- `/install.php` now generates the `CRYPTO_SEED` from a combination of 14 random characters plus `uniqid()` and then another 13 random characters. Should guarantee every `CRYPTO_SEED` is globally unique.
- Added `/classes/Curl.php` which will be used in the near future.
- Various CSS, HTML markup, and styling changes.

### v0.3.2 (open source) - *08/25/2012*
- Upgraded Bootstrap to `2.1.0`.
- A bunch of CSS cleanup and fixes in styling and HTML markup.
- Added `/classes/CSRF.php` which combats CSRF attacks.
- Added `/classes/Sessions.php` which provides session support for `/classes/CSRF.php` and future login and users.
- `/actions/ssh_execute.php` and `/actions/delete_recipe.php` implement CSRF protection.
- `/js/common.js` changed AJAX timeout from 15 seconds to 60 seconds. Allows for longer running executions.
- `/js/common.js` AJAX request wrapper only executes `POST` requests. Removed the ability to execute `GET` requests.
- Added `/defines.php` which stores common boolean flags. The result is code that is easier to read. For example, instead of passing `true` into a function, can pass flags like `MONGO_REPLICA_SET`.
- `/classes/Footer.php` added GitHub buttons *(stars and forks)*.
- Navigation re-order, now it is `Execute | Recipes | Servers | Groups | Settings`.
 
### v0.3.1 (open source) - *08/16/2012*
- Renamed `db_upgrade.php` to `db-upgrade.php` to keep with standards.
- Fix to `/actions/get_public_ssh_key.php` if the public key file can't be opened now returns an error message.
- `/classes/Prerequisites.php` is now actually called in `/classes/Requires.php`.
- `/classes/Prerequisites.php` checks for the blowfish cryptography library.
- Added `/classes/Bcrypt.php` bcrypt cryptography library.

### v0.3.0 (open source) - *08/14/2012*
- `/markdown/markdown.php` now escapes tags and entities to prevent XSS attacks.
- Added new MySQL schema `/schema/0.1.1.sql`.
- Updated `/schema/latest.sql` to `/schema/0.1.1.sql`.
- `MySQL Schema 0.1.1` - Small modification to the `settings` table; `id` is now UNSIGNED.
- `MySQL Schema 0.1.1` - Added table `db_version` which stores the current version of the MySQL schema running.
- `MySQL Schema 0.1.1` - Added current MySQL schema version *(0.1.1)* row into the `db_version` table.
- Added `/db_upgrade.php` for merging MySQL schema changes.
- Added directory `/schema/diffs` which stores the MySQL queries that `db_upgrade.php` executes.
- Added `/version.php` which simply outputs the current application version and MySQL schema version in JSON.
- `/index.php` now checks MySQL schema version, and prompts if an upgrade is needed.

### v0.2.6 (open source) - *08/13/2012*
- Piping commands to each interpreter instead of running with `-e`, or `-c` flags.

### v0.2.5 (open source) - *08/10/2012*	
- Short PHP echo tags `<?=` replaced with full definitions `<?php echo` in the entire application for maximum compatibility with different `php.ini` configurations.

### v0.2.4 (open source) - *08/09/2012*
- Fixed bugs in `/classes/Links.php` dealing with auto-detecting pretty links. The code is quite nasty, if you know of a more elegant solution please submit a pull request.

### v0.2.3 (open source) - *08/09/2012*
- Fixed bug in `/classes/Links.php` where if using pretty links, any request inside of the `/actions` directory would cause pretty links to disable.

### v0.2.2 (open source) - *08/09/2012*
- Short PHP tags `<?` replaced with full tags `<?php` in the entire application for maximum compatibility with different `php.ini` configurations.

### v0.2.1 (open source) - *08/09/2012*
- Fixed bug in `install.php` which generated a `CRYPTO_SEED` of 62 characters instead of 64. Reduced required length in `app.config.php` of `CRYPTO_SEED` to 40 characters.

### v0.2.0 (open source) - *08/09/2012*
- Removed the requirement of setting up re-write rules and created a new class `/classes/Links`. All links work either with pretty links enabled or disabled. To use pretty links, re-write rules must still be configured on the web-server. See step *#14* in the installation instructions for further details.

### v0.1.0 (open source) - *08/07/2012*
- Initial release