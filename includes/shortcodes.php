<?php
if(!defined('ABSPATH')) exit;

add_shortcode('sw_posts',function($atts){

$atts=shortcode_atts([
'source'=>'latest',
'category'=>'',
'tag'=>'',
'ids'=>'',
'limit'=>6,
'columns'=>3,
'show_views'=>'false'
],$atts);

$paged=get_query_var('paged')?get_query_var('paged'):1;

$query=SPD_Query::get($atts,$paged);

$out=SPD_Render::posts($query,$atts);

wp_reset_postdata();

return $out;

});

add_shortcode('sw_post_views',function(){

if(!is_singular('post')) return '';

$id=get_the_ID();

$views=SPD_Views::get($id);

return '<span class="spd-post-views">👁 '.number_format_i18n($views).'</span>';

});
