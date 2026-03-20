# Srangweb Post Display

WordPress plugin for displaying posts with category filtering, pagination, view counts, and title-only mode.

## Version 2.1.6

### Fixed
- Added a working GitHub updater class for release-based auto-update.
- Added `Update URI`, `Requires at least`, and `Requires PHP` headers.
- Cleaned release package contents.

### Features
- Category restriction via `categories="slug-a,slug-b"`
- Title-only mode via `display="title"`
- Title list styles: `ul`, `ol`, `none`
- Optional filter UI
- Optional view counter shortcode

## Shortcodes

```text
[sw_posts categories="seo,wordpress" limit="6" show_filter="true"]
[sw_posts display="title" title_list_style="ul" limit="10"]
[sw_posts display="title" title_list_style="ol" limit="10"]
[sw_posts display="title" title_list_style="none" show_title_link="false" limit="10"]
[sw_post_views icon="true" label="true"]
```
