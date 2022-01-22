<?php

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2020年6月12日
 */
class Constant
{
    /*************************************************************************************************************
     * 系统用户(非数据库中的用户)
     **************************************************************************************************************/
    const SYSTEM_CONSOLE_USER       = '1000000000000'; // 命令行执行程序时user id的值
    /*************************************************************************************************************/

    /*************************************************************************************************************
     * 账号
     **************************************************************************************************************/
    const ACCOUNT_ADMIN_PREFIX      = 'admin_'; // 后台管理员的账号都应该时admin_开头（系统处理，用户无感）
    /*************************************************************************************************************/

    /*************************************************************************************************************
     * 用户角色
     **************************************************************************************************************/
    const USER_ROLE_LOGINED         = 'logined'; // 已登录用户
    const USER_ROLE_ADMIN           = 'admin'; // 已登录用户
    /*************************************************************************************************************/

    /*************************************************************************************************************
     * 文件上传根目录
     **************************************************************************************************************/
    const UPLOAD_ROOT_PATH          = __DIR__ . DIRECTORY_SEPARATOR . 'upload';
    /*************************************************************************************************************/

    /*************************************************************************************************************
     * 评论列里面content显示的长度
     **************************************************************************************************************/
    const ARTICLE_COMMENT_LIST_CONTENT_SIZE = '200';
    /*************************************************************************************************************/
}
