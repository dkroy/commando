## CHANGELOG

### v0.3.0 (open source) - *08/14/2012*
- Markdown.php now escapes tags and entities to prevent XSS attacks.
- Added new MySQL schema `/schema/0.1.1.sql`.
- Updated `/schema/latest.sql` to `/schema/0.1.1.sql`.
- `MySQL Schema 0.1.1` - Small modification to the `settings` table; `id` is now UNSIGNED.
- `MySQL Schema 0.1.1` - Added table `db_version` which stores the current version of the MySQL schema running.
- `MySQL Schema 0.1.1` - Added current MySQL schema version *(0.1.1)* row into the `db_version` table.
- Added `db_upgrade.php` for merging MySQL schema changes.
- Added directory `/schema/diffs` which stores the MySQL queries that `db_upgrade.php` executes.
- Added `version.php` which simply outputs the current application version and MySQL schema version in JSON.
- `index.php` now checks MySQL schema version, and prompts if an upgrade is needed.

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
- Removed the requirement of setting up re-write rules and created a new class `/classes/Links`. All links work either with pretty links enabled or disabled. To use pretty links, re-write rules must still be configured on the web-server. See step *#13* in the installation instructions for further details.

### v0.1.0 (open source) - *08/07/2012*
- Initial release