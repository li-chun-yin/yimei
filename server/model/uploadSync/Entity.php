<?php

namespace model\uploadSync;

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
    private $unikey = '';

    /**
     * @var int
     */
    private $type = '0';

    /**
     * @var int
     */
    private $status = '0';

    /**
     * @var array
     */
    private $sync_request;

    /**
     * @var array
     */
    private $sync_response;

    /**
     * @var int
     */
    private $create_time = '0';

    /**
     * @var int
     */
    private $update_time = '0';

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
     * Set unikey.
     *
     * @param string $unikey
     *
     * @return Entity
     */
    public function setUnikey($unikey)
    {
        $this->unikey = $unikey;

        return $this;
    }

    /**
     * Get unikey.
     *
     * @return string
     */
    public function getUnikey()
    {
        return $this->unikey;
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
     * Set status.
     *
     * @param int $status
     *
     * @return Entity
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set syncRequest.
     *
     * @param array $syncRequest
     *
     * @return Entity
     */
    public function setSyncRequest($syncRequest)
    {
        $this->sync_request = $syncRequest;

        return $this;
    }

    /**
     * Get syncRequest.
     *
     * @return array
     */
    public function getSyncRequest()
    {
        return $this->sync_request;
    }

    /**
     * Set syncResponse.
     *
     * @param array $syncResponse
     *
     * @return Entity
     */
    public function setSyncResponse($syncResponse)
    {
        $this->sync_response = $syncResponse;

        return $this;
    }

    /**
     * Get syncResponse.
     *
     * @return array
     */
    public function getSyncResponse()
    {
        return $this->sync_response;
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
