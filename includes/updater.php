<?php
if(!defined('ABSPATH')) exit;

class SPD_Github_Updater{

private $plugin;
private $basename;
private $slug;
private $repo;

function __construct($file,$basename,$slug,$repo){

$this->plugin=$file;
$this->basename=$basename;
$this->slug=$slug;
$this->repo=$repo;

add_filter('pre_set_site_transient_update_plugins',[$this,'check']);

}

function check($transient){

if(empty($transient->checked)) return $transient;

$response=wp_remote_get('https://api.github.com/repos/'.$this->repo.'/releases/latest');

if(is_wp_error($response)) return $transient;

$data=json_decode(wp_remote_retrieve_body($response),true);

if(empty($data['tag_name'])) return $transient;

$version=ltrim($data['tag_name'],'v');

$current=$transient->checked[$this->basename];

if(version_compare($version,$current,'>')){

$package='';

foreach($data['assets'] as $asset){
if(strpos($asset['name'],'.zip')!==false){
$package=$asset['browser_download_url'];
break;
}
}

$transient->response[$this->basename]=(object)[
'slug'=>$this->slug,
'new_version'=>$version,
'package'=>$package,
'url'=>$data['html_url']
];

}

return $transient;

}

}
