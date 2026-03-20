# Srangweb Post Display

A WordPress plugin for displaying posts with category filters, pagination, post views, and a title-only mode.

## Version
<<<<<<< HEAD
2.1.5

2.1.4
>>>>>>> 6a470887110bb0c5111bb3ef9f2927f8d97d3af7

## New in 2.1.5
- Cleaned the release package and removed release-only files
- Standardized `title_list_style` options to `ul|ol|none`
- Added plain title output without bullets or numbers using `title_list_style="none"`
- Added plain title text output using `show_title_link="false"`
- Kept package contents focused on plugin runtime files and documentation

## Shortcode examples

```text
[sw_posts source="latest" limit="6" columns="3"]
[sw_posts source="category" category="seo" limit="9" columns="3" pagination="true" pager_id="seo" show_filter="true"]
[sw_posts categories="seo,wordpress,hosting" limit="6" show_filter="true"]
[sw_posts categories="seo,wordpress" display="title" title_list_style="ul" limit="10"]
[sw_posts categories="seo,wordpress" display="title" title_list_style="ol" limit="10"]
[sw_posts categories="seo,wordpress" display="title" title_list_style="none" limit="10"]
[sw_posts categories="seo,wordpress" display="title" title_list_style="none" show_title_link="false" limit="10"]
[sw_post_views]
[sw_post_views text="อ่านแล้ว" icon="true" label="true"]
```
