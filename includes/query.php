<?php
if(!defined('ABSPATH')) exit;

class SPD_Query{

static function get($atts,$paged){

$args=[
'post_type'=>'post',
'post_status'=>'publish',
'posts_per_page'=>$atts['limit'],
'paged'=>$paged,
'ignore_sticky_posts'=>true
];

if($atts['source']=='category' && $atts['category'])
$args['category_name']=$atts['category'];

if($atts['source']=='tag' && $atts['tag'])
$args['tag']=$atts['tag'];

if($atts['source']=='ids' && $atts['ids'])
$args['post__in']=explode(',',$atts['ids']);

return new WP_Query($args);

}

}
