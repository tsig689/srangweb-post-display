<?php
if(!defined('ABSPATH')) exit;

class SPD_Render{

static function posts($query,$atts){

if(!$query->have_posts()) return '';

$out='<div class="spd-grid columns-'.$atts['columns'].'">';

while($query->have_posts()){
$query->the_post();
$out.=self::card(get_the_ID(),$atts);
}

$out.='</div>';

return $out;

}

static function card($id,$atts){

$title=get_the_title($id);
$link=get_permalink($id);
$views=SPD_Views::get($id);

ob_start(); ?>

<article class="spd-card">
<a href="<?php echo esc_url($link); ?>">
<?php if(has_post_thumbnail($id)): ?>
<div class="spd-thumb"><?php echo get_the_post_thumbnail($id,'large'); ?></div>
<?php endif; ?>

<div class="spd-content">

<h3><?php echo esc_html($title); ?></h3>

<?php if($atts['show_views']=='true'): ?>
<div class="spd-views">👁 <?php echo number_format_i18n($views); ?></div>
<?php endif; ?>

</div>
</a>
</article>

<?php return ob_get_clean();

}

}
