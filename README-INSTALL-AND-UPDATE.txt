Srangweb Post Display v1.1.8

ระบบที่มีในเวอร์ชันนี้
- Post grid
- Source: latest / category / tag / ids / related
- Category filter (front-end, non-AJAX)
- Pagination with pager_id
- Views counter
- [sw_post_views]
- GitHub Release auto-update

วิธีใช้หลัก
[sw_posts source="latest" limit="6" columns="3"]
[sw_posts source="latest" limit="6" columns="3" pagination="true" pager_id="blog"]
[sw_posts source="latest" limit="6" columns="3" pagination="true" pager_id="blog" show_views="true"]
[sw_posts source="category" category="seo" limit="9" columns="3" pagination="true" pager_id="seo" show_filter="true"]
[sw_posts source="latest" limit="6" columns="3" pagination="true" pager_id="blog" show_views="true"]
[sw_post_views]
[sw_post_views text="อ่านแล้ว" icon="true" label="true"]



Fix in 1.1.5
- Fixed category filter 'All' option so it shows posts from the full filter set instead of returning empty results.
