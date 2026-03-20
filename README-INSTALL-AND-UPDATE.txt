Srangweb Post Display v2.1.5

= 2.1.5 =
* Cleaned release package and removed unnecessary release-only files
* Standardized title list style options to: ul, ol, none
* Added support for title-only output without bullets or numbers
* Added support for plain title output with show_title_link="false"
* Aligned plugin package contents for GitHub release downloads

วิธีใช้หลัก

[sw_posts source="latest" limit="6" columns="3"]
[sw_posts source="latest" limit="6" columns="3" pagination="true" pager_id="blog"]
[sw_posts source="latest" limit="6" columns="3" pagination="true" pager_id="blog" show_views="true"]
[sw_posts source="category" category="seo" limit="9" columns="3" pagination="true" pager_id="seo" show_filter="true"]
[sw_posts categories="seo,wordpress,hosting" limit="6" show_filter="true"]
[sw_posts categories="seo,wordpress" display="title" limit="10"]
[sw_posts categories="seo,wordpress" display="title" title_list_style="ul" limit="10"]
[sw_posts categories="seo,wordpress" display="title" title_list_style="ol" limit="10"]
[sw_posts categories="seo,wordpress" display="title" title_list_style="none" limit="10"]
[sw_posts categories="seo,wordpress" display="title" title_list_style="none" show_title_link="false" limit="10"]
[sw_post_views]
[sw_post_views text="อ่านแล้ว" icon="true" label="true"]
