<?php
namespace model\uploadSyncDesc;

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
    const STATUSS               = [
        self::STATUS_WAIT       => '未发布', 
        self::STATUS_DONE       => '已发布', 
        self::STATUS_PART       => '部分发布', 
        self::STATUS_ING        => '发布中', 
    ];
    const STATUS_WAIT       = '0';
    const STATUS_DONE       = '1';
    const STATUS_PART       = '2';
    const STATUS_ING        = '3';
    /********************************************************************************************************/
}
