<?php
/**
 * Created by IntelliJ IDEA.
 * User: heweijun
 * Date: 2018/3/16
 * Time: 20:58
 */
$mall_okay_path = __DIR__.'/upload_mall/';
$mall_okay = array(

  'okaymall_order_goods' => array(
      'id',
      'goodsThums',
  ),

  'okaymall_goods'=>array(
      'goodsId',
      'goodsImg',
      'goodsThums'
  ),
  'okaymall_banner_image' => array(
      'id',
      'imageUrl'
  ),
  
  'okaymall_goods_gallerys_h5' => array(
      'id',
      'goodsImg'
  ),
  'okaymall_goods_gallerys' => array(
      'id',
      'goodsImg',
      'goodsThumbs'
  ),
  'okaymall_goods_cats' => array(
      'catId',
      'cateShowImg',
      'mobileCateShowImg',
      'cateShowImg_380_790',
      'cateShowImg_380_380',
      'cateShowImg_790_380'
   )
);

$cms_okay_path = __DIR__.'/upload_cms/';
$cms_okay = array(
   //文章数据表
  'cms_posts'=>array(
         'id',
         'smeta',  //{"thumb":"portal\/20170508\/591008d6b3098.jpg","template":"","photo":[{"url":"http:\/\/web.okay.cn\/data\/upload\/portal\/20170508\/59100478cc46d.jpg","alt":"5908544a35135.jpg"}]}
         'post_content' //文本编辑器
  ),

  
  'cms_slide'=>array(
       'slide_id',
       'slide_pic'
  ),
  
  //文章数据表
  'cms_posts'=>array(
         'id',
         'smeta',  //{"thumb":"portal\/20170508\/591008d6b3098.jpg","template":"","photo":[{"url":"http:\/\/web.okay.cn\/data\/upload\/portal\/20170508\/59100478cc46d.jpg","alt":"5908544a35135.jpg"}]}
         'post_content' //文本编辑器
  ),
  //机构表
  'cms_institude_coroperate' => array(
    'id',
    'logo_image',
    'logo_circle_image',
    'backgroud_header_image',
    'about_us_image',
    'smeta'  //四个小图
  ),

   //央视栏目
  'cms_television_column'=> array(
        'id',
        'content',  //文本内容
        'show_image_url',
        'thumb_show_image_url',
        'background_image',

  ),

  
   'cms_television_column_introduce'=>array(
        'id',
        'background_image_url',
        'mobile_background_image_url',
        'thumb_mobile_header_image_url',
        'thumb_mobile_detail_image_url'
  ),

  //文章数据表
  'cms_posts'=>array(
         'id',
         'smeta',  //{"thumb":"portal\/20170508\/591008d6b3098.jpg","template":"","photo":[{"url":"http:\/\/web.okay.cn\/data\/upload\/portal\/20170508\/59100478cc46d.jpg","alt":"5908544a35135.jpg"}]}
         'post_content' //文本编辑器
  ),

  

  //直播
  'cms_ad' => array(
    'id',
    'ad_content'  //文本内容
  )

);

