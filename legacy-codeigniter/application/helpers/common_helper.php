<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
function app_pic($id,$type,$size)
{
   $longid=str_pad($id,9,'0',STR_PAD_LEFT);
   $uri='/upload/'.$type.'/'.substr($longid,0,3).'/'.substr($longid,3,2).'/'.substr($longid,5,2).'/'.substr($longid,7,2).'_'.$type.'_'.$size.'.jpg';
   return $uri;

}

function avatar($uid,$size='mid')
{
	$longid=str_pad($uid,9,'0',STR_PAD_LEFT);
	$exts=array('.jpg','.gif','.png');
	$path='upload/avatar/'.substr($longid,0,3).'/'.substr($longid,3,2).'/'.substr($longid,5,2).'/'.substr($longid,7,2).'_avatar_'.$size;
	foreach($exts as $ext)
	{
		
		if(file_exists(FCPATH.$path.$ext))
		return $path.$ext;
	}
	return 'images/default_avatar_'.$size.'.gif';
}
function recipe_img($rid,$size='mid')
{
	$longid=str_pad($rid,9,'0',STR_PAD_LEFT);
	$exts=array('.jpg','.gif','.png');
	$path='upload/recipe/'.substr($longid,0,3).'/'.substr($longid,3,2).'/'.substr($longid,5,2).'/'.substr($longid,7,2).'_recipe_'.$size;
	foreach($exts as $ext)
	{
		
		if(file_exists(FCPATH.$path.$ext))
		
		return $path.$ext;
	}
	//return 'images/default_avatar_'.$size.'.gif';
}

function mkdirs($pathname, $mode = 0755) 
{
  is_dir(dirname($pathname)) || mkdirs(dirname($pathname), $mode);
  return is_dir($pathname) || @mkdir($pathname, $mode);
}
 function get_user_photo_count($uid,$bizid)
{
	$CI =& get_instance();
	$CI->load->model('photos');
	return $CI->photos->get_user_photo_count($uid,$bizid);
}

function get_user_first_photo($uid,$bizid)
{
	$CI =& get_instance();
	$CI->load->model('photos');
	return $CI->photos->get_user_first_photo($uid,$bizid);
}

//return @biz

function getBizById($bizid)
{
	$CI =& get_instance();
	$CI->load->model('bizs');
	//$CI->load->model('reviews');
	$biz = $CI->bizs->get($bizid);
	//$biz->rating_stats = $CI->reviews->get_rating_stats($biz->id);
	if(!$biz)
	{
		return false;
	}
	
	//get biz category info 
	$biz = biz_cat_info($biz);
	
	// get biz rating stats
	//$biz = biz_rate_stats($biz);
	
	// get photo stats
	$biz = biz_photo_stats($biz);

	return $biz;
	
}

//get biz category and city info 
function biz_cat_info($biz)
{
	$CI =& get_instance();
	$CI->load->model('catsAndCities','cc');
	$CI->cc->set_table_name('city');
	$cities=$CI->cc->get_all();
	$CI->cc->set_table_name('category');
	$cats=$CI->cc->get_all();
	$biz->city=isset($cities[$biz->city_id]) ? $cities[$biz->city_id] : null;
	$biz->district=isset($cities[$biz->city_id]) && $biz->district_id ? $cities[$biz->district_id] : null;
	$biz->category_1=isset($cats[$biz->catid_1]) ? $cats[$biz->catid_1] : null;
	$biz->category_2=isset($cats[$biz->catid_2]) ? $cats[$biz->catid_2] : null;
	return $biz;

}

// get biz rating stats
function biz_rate_stats($biz)
{
	
	$biz->review_num = array_sum($biz->rating_stats);
	$biz->rating = 0;
	$biz->star_1 = 0;
	$biz->star_2 = 0;
	$biz->star_3 = 0;
	$biz->star_4 = 0;
	$biz->star_5 = 0;
	
	foreach($biz->rating_stats as $k => $v)
	{
		$var = 'star_'.$k;
		$biz->{$var} = $v;
	}
	$biz->rating= 0;
	$biz->rating_1= '0%';
	$biz->rating_2= '0%';
	$biz->rating_3= '0%';
	$biz->rating_4= '0%';
	$biz->rating_5= '0%';
	if($biz->review_num > 0)
	{
		$biz->rating=(round(($biz->star_1+$biz->star_2*2+$biz->star_3*3+$biz->star_4*4+$biz->star_5*5)/($biz->review_num*5),2)*100).'%';
		$biz->rating_1=(round($biz->star_1/($biz->review_num),2)*100).'%';
		$biz->rating_2=(round($biz->star_2/($biz->review_num),2)*100).'%';
		$biz->rating_3=(round($biz->star_3/($biz->review_num),2)*100).'%';
		$biz->rating_4=(round($biz->star_4/($biz->review_num),2)*100).'%';
		$biz->rating_5=(round($biz->star_5/($biz->review_num),2)*100).'%';
	}
	
	return $biz;
}

// get photo stats
function biz_photo_stats($biz)
{
	$CI =& get_instance();
	$CI->load->model('photos');
	$biz->first_photo = $CI->photos->get_first($biz->id);
	$biz->photo_count = $CI->photos->getCount($biz->id);

	return $biz;
}
// get user flower

function get_user_flowers($uid)
{
	$CI =& get_instance();
	$CI->load->model('flowers');
	return $CI->flowers->getCount(array('receiver'=>$uid));
}

function get_user_review_count($uid)
{
	$CI =& get_instance();
	$CI->load->model('reviews');
	return $CI->reviews->getCount(array('uid'=>$uid));
}

function get_cities()
{

	$CI =& get_instance();
	$CI->load->model('catsAndCities','cc');
	$CI->cc->set_table_name('city');
	return $CI->cc->get_top();

}

function get_news_cats()
{

	$CI =& get_instance();
	$CI->load->model('catsAndCities','cc');
	$CI->cc->set_table_name('news_cats');
	return $CI->cc->get_top();

}

function get_catorcity_by_field($value,$table='city',$field = 'id')
{
	$CI =& get_instance();
	$CI->load->model('catsAndCities','cc');
	$CI->cc->set_table_name($table);
	return $CI->cc->get($value,$field);
}


 function js_window_location($url) 
 {

    echo "window.location='$url';";

 }
function is_bot(){
  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
  $botchar = "/(bot|crawl|spider|slurp|yahoo|sohu-search|lycos|robozilla)/i";
  if(preg_match($botchar, $ua)) {
	return true;
  }
  return false;
}

function time_format($time)
{
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();

   $difference     = $now - $time;
   $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) 
   {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1)
   {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j] ago";
}

function biz_photo($photo,$type='')
{
	$photo_path='/upload/biz_photos/'.$photo->folder.'/'.$photo->id;
	if($type=='thumb')
	{
		$photo_path.='.thumb';
	}
	return $photo_path.'.jpg';
}
?>