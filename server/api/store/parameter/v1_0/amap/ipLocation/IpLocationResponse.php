<?php
namespace api\store\parameter\v1_0\amap\ipLocation;

use asbamboo\api\apiStore\ApiResponseParams;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2020年6月11日
 */
class IpLocationResponse extends ApiResponseParams
{   
    /**
     * @desc  国家
     * @example 国家（或地区），中文
     * @var string
     */
    public $country = '';
    
    /**
     * @desc  省份
     * @example 省（二级），中文
     * @var string
     */
    public $province = '';
    
    /**
     * @desc 城市
     * @example 市（三级），中文
     * @var string
     */
    public $city = '';
    
    /**
     * @desc 区县
     * @example 区（四级），中文
     * @var string
     */
    public $district = '';

    /**
     * @desc 运营商
     * @example 如电信、联通、移动
     * @var string
     */
    public $isp = '';
    
    /**
     * @desc 经纬度
     * @example 116.480881,39.989410
     * @var string
     */
    public $location = '';

    /**
     * @desc ip
     * @example 0
     * @var string
     */
    public $ip = '';
}