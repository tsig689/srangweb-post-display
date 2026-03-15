<?php
if(!defined('ABSPATH')) exit;

class SPD_Views{

const META_KEY='spd_post_views';

static function increment($post_id){

if(!$post_id) return;

$cookie='spd_view_'.$post_id;

if(isset($_COOKIE[$cookie])) return;

$count=(int)get_post_meta($post_id,self::META_KEY,true);
$count++;

update_post_meta($post_id,self::META_KEY,$count);

setcookie($cookie,'1',time()+3600,'/');

}

static function get($post_id){
return (int)get_post_meta($post_id,self::META_KEY,true);
}

}
