<?php
namespace model\upload;

use asbamboo\database\FactoryInterface;
use asbamboo\security\user\AnonymousUser;
use exception\MessageException;
use asbamboo\http\ServerRequest;

/**
 * 管理表的操作
 *  - 控制 增/删/改
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Manager
{
    /**
     *
     * @var FactoryInterface
     */
    private $Db;

    /**
     *
     * @var Repository
     */
    private $Repository;

    /**
     *
     * @var Entity
     */
    private $Entity;
    
    /**
     * 
     * @var ServerRequest
     */
    private $ServerRequest;

    /**
     *
     * @param FactoryInterface $Db
     */
    public function __construct(FactoryInterface $Db, Repository $Repository, ServerRequest $ServerRequest)
    {
        $this->Db               = $Db;
        $this->Repository       = $Repository;
        $this->ServerRequest    = $ServerRequest;
    }

    /**
     *
     * @param int|Entity $account
     * @throws MessageException
     * @return Entity
     */
    public function load(/*int|Entity*/ $Upload = null) : Entity
    {
        if(is_null($Upload)){
            $this->Entity   = new Entity();
        }else if($Upload instanceof Entity){
            $this->Entity   = $Upload;
        }else{
            $this->Entity   = $this->Repository->findOneByUploadId($Upload);
            if(empty($this->Entity)){
                throw new MessageException('上传信息不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     * 生成用户唯一标识
     *
     * @return string
     */
    public function generateUploadId() : string
    {
        $db_params          = $this->Db->getManager()->getConnection()->getParams();
        $encode_db_params   = md5(json_encode($db_params));
        $prefix_db          = substr($encode_db_params, -5);
        $ip                 = $this->ServerRequest->getClientIp();
        $encode_ip          = md5(json_encode($ip));
        $prefix_ip          = substr($encode_ip, -7);
        $headers            = $this->ServerRequest->getHeaders();
        $encode_headers     = md5(json_encode($headers));
        $prefix_header      = substr($encode_headers, -7);
        return uniqid($prefix_header . $prefix_ip . $prefix_db);
    }
    
    /**
     * 
     * @param string $upload_id
     * @return string
     */
    public function generateUploadPath(Entity $Entity) : string
    {
        $file_name          = $Entity->getOriginalName();
        $file_extendsion    = strrchr($file_name, '.');
        $upload_id          = $Entity->getUploadId();
        $file_name          = $upload_id . $file_extendsion;
        $file_path          = $file_name;
        return $file_path;
    }
    
    
    /**
     * 创建数据行
     *
     * @param array $data ['extension','mime_type','original_name','size','type','upload_id']
     * @return Manager
     */
    public function create(array $data) : Manager
    {
        $this->Entity->setExtension($data['extension']);
        $this->Entity->setMimeType($data['mime_type']);
        $this->Entity->setOriginalName($data['original_name']);
        $this->Entity->setSize($data['size']);
        $this->Entity->setType($data['type']);
        $this->Entity->setUploadId($this->generateUploadId());
        $this->Entity->setPath($this->generateUploadPath($this->Entity));
        
        $this->validateCreate();

        $this->Entity->setCreateIp($this->ServerRequest->getClientIp());
        $this->Entity->setCreateTime(time());
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());
        
        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     * 验证
     *
     * @throws MessageException
     */
    public function validateCreate()
    {
    }
}