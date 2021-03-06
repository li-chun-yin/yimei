<?php
namespace model\uploadSync;

/**
 * 数据表枚举字段值
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Code
{
    /*********************************************************************************************************
     * status 字段
    *********************************************************************************************************/
    const STATUSS              = [
        self::STATUS_ING       => '发布中',
        self::STATUS_DONE      => '已发布',
    ];
    const STATUS_ING            = '0';
    const STATUS_DONE           = '1';
    /********************************************************************************************************/

    /*********************************************************************************************************
     * type 字段 不能轻易改变, command\VideoSyncCommand 以type类型定义 sync0，sync1， ... syncn方法
     *********************************************************************************************************/
    const TYPES                 = [
        self::TYPE_DOUYIN       => '抖音',
        self::TYPE_TOUTIAO      => '今日头条',
        self::TYPE_XIGUA        => '西瓜视频',
        self::TYPE_KUAISHOU     => '快手',
    ];
    const TYPE_DOUYIN   = '0';
    const TYPE_TOUTIAO  = '1';
    const TYPE_XIGUA    = '2';
    const TYPE_KUAISHOU = '3';
    /********************************************************************************************************/
}
