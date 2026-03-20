Srangweb Post Display - Install and Update
===========================================

Version: 2.1.6

Install
-------
1. Upload the `srangweb-post-display` folder to `/wp-content/plugins/`
2. Activate the plugin in WordPress

GitHub auto-update
------------------
This package includes a GitHub release updater.

For auto-update to work:
- The plugin version in `srangweb-post-display.php` must match the release version
- GitHub release tag should be like `v2.1.6`
- Attach a zip asset named `srangweb-post-display-v2.1.6.zip`
- The zip must contain a top-level folder named `srangweb-post-display`

Recommended release flow
------------------------
1. Commit code changes
2. Bump version to 2.1.6
3. Create tag `v2.1.6`
4. Upload `srangweb-post-display-v2.1.6.zip` to the GitHub release

Notes
-----
- `title_list_style` supports: `ul`, `ol`, `none`
- `none` means show titles without bullets or numbers
