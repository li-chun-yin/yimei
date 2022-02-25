<?php

namespace model\xiguaId;

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
    private $open_id = '';

    /**
     * @var string
     */
    private $access_token = '';

    /**
     * @var int
     */
    private $expires_in = '0';

    /**
     * @var string
     */
    private $refresh_token = '';

    /**
     * @var int
     */
    private $refresh_expires_in = '0';

    /**
     * @var array
     */
    private $scope;

    /**
     * @var string
     */
    private $avatar = '';

    /**
     * @var string
     */
    private $nickname = '';

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
     * Set openId.
     *
     * @param string $openId
     *
     * @return Entity
     */
    public function setOpenId($openId)
    {
        $this->open_id = $openId;

        return $this;
    }

    /**
     * Get openId.
     *
     * @return string
     */
    public function getOpenId()
    {
        return $this->open_id;
    }

    /**
     * Set accessToken.
     *
     * @param string $accessToken
     *
     * @return Entity
     */
    public function setAccessToken($accessToken)
    {
        $this->access_token = $accessToken;

        return $this;
    }

    /**
     * Get accessToken.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * Set expiresIn.
     *
     * @param int $expiresIn
     *
     * @return Entity
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expires_in = $expiresIn;

        return $this;
    }

    /**
     * Get expiresIn.
     *
     * @return int
     */
    public function getExpiresIn()
    {
        return $this->expires_in;
    }

    /**
     * Set refreshToken.
     *
     * @param string $refreshToken
     *
     * @return Entity
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refresh_token = $refreshToken;

        return $this;
    }

    /**
     * Get refreshToken.
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    /**
     * Set refreshExpiresIn.
     *
     * @param int $refreshExpiresIn
     *
     * @return Entity
     */
    public function setRefreshExpiresIn($refreshExpiresIn)
    {
        $this->refresh_expires_in = $refreshExpiresIn;

        return $this;
    }

    /**
     * Get refreshExpiresIn.
     *
     * @return int
     */
    public function getRefreshExpiresIn()
    {
        return $this->refresh_expires_in;
    }

    /**
     * Set scope.
     *
     * @param array $scope
     *
     * @return Entity
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope.
     *
     * @return array
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set avatar.
     *
     * @param string $avatar
     *
     * @return Entity
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set nickname.
     *
     * @param string $nickname
     *
     * @return Entity
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname.
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
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
    /**
     * @var int
     */
    private $refresh_count = '0';


    /**
     * Set refreshCount.
     *
     * @param int $refreshCount
     *
     * @return Entity
     */
    public function setRefreshCount($refreshCount)
    {
        $this->refresh_count = $refreshCount;

        return $this;
    }

    /**
     * Get refreshCount.
     *
     * @return int
     */
    public function getRefreshCount()
    {
        return $this->refresh_count;
    }
    /**
     * @var int
     */
    private $disabled = '0';


    /**
     * Set disabled.
     *
     * @param int $disabled
     *
     * @return Entity
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled.
     *
     * @return int
     */
    public function getDisabled()
    {
        return $this->disabled;
    }
}
