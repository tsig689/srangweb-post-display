Srangweb Post Display v2.1.2

= 2.1.2 =
* Added category restriction via categories attribute
* Added title-only display mode
* Added title list style options: ul, ol, div
* Improved category filter behavior for the All state
* Normalized plugin version, GitHub release version, and zip naming

วิธีใช้หลัก

[sw_posts source="latest" limit="6" columns="3"]
[sw_posts source="latest" limit="6" columns="3" pagination="true" pager_id="blog"]
[sw_posts source="latest" limit="6" columns="3" pagination="true" pager_id="blog" show_views="true"]
[sw_posts source="category" category="seo" limit="9" columns="3" pagination="true" pager_id="seo" show_filter="true"]
[sw_posts categories="seo,wordpress,hosting" limit="6" show_filter="true"]
[sw_posts categories="seo,wordpress" display="title" limit="10"]
[sw_posts categories="seo,wordpress" display="title" title_list_style="ol" limit="10"]
[sw_post_views]
[sw_post_views text="อ่านแล้ว" icon="true" label="true"]
