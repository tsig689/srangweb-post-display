Srangweb Post Display v1.0.0

วิธีติดตั้ง
1. อัปโหลดไฟล์ ZIP นี้ใน WordPress > Plugins > Add New > Upload Plugin
2. กด Activate

Shortcodes หลัก
[sw_posts source="latest" limit="6" columns="3"]
[sw_posts source="category" category="seo" limit="6" columns="3"]
[sw_posts source="tag" tag="wordpress" limit="6"]
[sw_posts source="related" limit="3" columns="3"]
[sw_posts source="ids" ids="12,25,31" limit="3"]
[sw_posts source="latest" limit="9" columns="3" pagination="true" pager_id="blog"]
[sw_posts source="latest" limit="6" columns="3" show_views="true"]

แสดงวิวของโพสต์ปัจจุบัน
[sw_post_views]
[sw_post_views icon="true" label="true"]
[sw_post_views text="อ่านแล้ว" icon="true" label="true"]

หมายเหตุ
- ถ้ามีหลายบล็อกที่เปิด pagination ในหน้าเดียวกัน ควรตั้ง pager_id คนละค่า
- ระบบวิวเป็นตัวนับภายในเว็บ ไม่ใช่ analytics แบบ GA4
