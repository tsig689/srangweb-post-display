# Srangweb Post Display

A WordPress plugin for displaying posts with category filters, pagination, post views, and a title-only mode.

## Version
2.1.4

## New in 2.1.2
- Added `categories` attribute to limit output to selected category slugs
- Added `display="title"` mode to show titles only
- Added `title_list_style="ul|ol|div"`
- Improved category filter behavior for the **All** state
- Normalized versioning and release naming for GitHub release packaging

## Shortcode examples

```text
[sw_posts source="latest" limit="6" columns="3"]

[sw_posts source="category" category="seo" limit="9" columns="3" pagination="true" pager_id="seo" show_filter="true"]

[sw_posts categories="seo,wordpress,hosting" limit="6" show_filter="true"]

[sw_posts categories="seo,wordpress" display="title" title_list_style="ol" limit="10"]

[sw_post_views]
[sw_post_views text="อ่านแล้ว" icon="true" label="true"]
```
