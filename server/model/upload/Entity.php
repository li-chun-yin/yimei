<?php

namespace model\upload;

/**
 * Entity
 */
class Entity
{
    /**
     * @var int
     */
    private $seq;

    /**
     * @var string
     */
    private $upload_id = '';

    /**
     * @var string
     */
    private $original_name = '';

    /**
     * @var string
     */
    private $extension = '';

    /**
     * @var string
     */
    private $mime_type = '';

    /**
     * @var int
     */
    private $size = '0';

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var int
     */
    private $type = '0';

    /**
     * @var int
     */
    private $create_time = '0';

    /**
     * @var string
     */
    private $create_ip = '';

    /**
     * @var int
     */
    private $update_time = '0';

    /**
     * @var string
     */
    private $update_ip = '';

    /**
     * @var int
     */
    private $version = '0';


    /**
     * Get seq.
     *
     * @return int
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * Set uploadId.
     *
     * @param string $uploadId
     *
     * @return Entity
     */
    public function setUploadId($uploadId)
    {
        $this->upload_id = $uploadId;

        return $this;
    }

    /**
     * Get uploadId.
     *
     * @return string
     */
    public function getUploadId()
    {
        return $this->upload_id;
    }

    /**
     * Set originalName.
     *
     * @param string $originalName
     *
     * @return Entity
     */
    public function setOriginalName($originalName)
    {
        $this->original_name = $originalName;

        return $this;
    }

    /**
     * Get originalName.
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->original_name;
    }

    /**
     * Set extension.
     *
     * @param string $extension
     *
     * @return Entity
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set mimeType.
     *
     * @param string $mimeType
     *
     * @return Entity
     */
    public function setMimeType($mimeType)
    {
        $this->mime_type = $mimeType;

        return $this;
    }

    /**
     * Get mimeType.
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * Set size.
     *
     * @param int $size
     *
     * @return Entity
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return Entity
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set type.
     *
     * @param int $type
     *
     * @return Entity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set createTime.
     *
     * @param int $createTime
     *
     * @return Entity
     */
    public function setCreateTime($createTime)
    {
        $this->create_time = $createTime;

        return $this;
    }

    /**
     * Get createTime.
     *
     * @return int
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * Set createIp.
     *
     * @param string $createIp
     *
     * @return Entity
     */
    public function setCreateIp($createIp)
    {
        $this->create_ip = $createIp;

        return $this;
    }

    /**
     * Get createIp.
     *
     * @return string
     */
    public function getCreateIp()
    {
        return $this->create_ip;
    }

    /**
     * Set updateTime.
     *
     * @param int $updateTime
     *
     * @return Entity
     */
    public function setUpdateTime($updateTime)
    {
        $this->update_time = $updateTime;

        return $this;
    }

    /**
     * Get updateTime.
     *
     * @return int
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    /**
     * Set updateIp.
     *
     * @param string $updateIp
     *
     * @return Entity
     */
    public function setUpdateIp($updateIp)
    {
        $this->update_ip = $updateIp;

        return $this;
    }

    /**
     * Get updateIp.
     *
     * @return string
     */
    public function getUpdateIp()
    {
        return $this->update_ip;
    }

    /**
     * Set version.
     *
     * @param int $version
     *
     * @return Entity
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }
}
