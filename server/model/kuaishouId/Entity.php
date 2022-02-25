<?php

namespace model\kuaishouId;

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
    private $refresh_token_expires_in = '0';

    /**
     * @var array
     */
    private $scopes;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $sex = '';

    /**
     * @var string
     */
    private $head = '';

    /**
     * @var string
     */
    private $bigHead = '';

    /**
     * @var string
     */
    private $city = '';

    /**
     * @var string
     */
    private $fan = '';

    /**
     * @var string
     */
    private $follow = '';

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
     * Set refreshTokenExpiresIn.
     *
     * @param int $refreshTokenExpiresIn
     *
     * @return Entity
     */
    public function setRefreshTokenExpiresIn($refreshTokenExpiresIn)
    {
        $this->refresh_token_expires_in = $refreshTokenExpiresIn;

        return $this;
    }

    /**
     * Get refreshTokenExpiresIn.
     *
     * @return int
     */
    public function getRefreshTokenExpiresIn()
    {
        return $this->refresh_token_expires_in;
    }

    /**
     * Set scopes.
     *
     * @param array $scopes
     *
     * @return Entity
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * Get scopes.
     *
     * @return array
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Entity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set sex.
     *
     * @param string $sex
     *
     * @return Entity
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex.
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set head.
     *
     * @param string $head
     *
     * @return Entity
     */
    public function setHead($head)
    {
        $this->head = $head;

        return $this;
    }

    /**
     * Get head.
     *
     * @return string
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * Set bigHead.
     *
     * @param string $bigHead
     *
     * @return Entity
     */
    public function setBigHead($bigHead)
    {
        $this->bigHead = $bigHead;

        return $this;
    }

    /**
     * Get bigHead.
     *
     * @return string
     */
    public function getBigHead()
    {
        return $this->bigHead;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return Entity
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set fan.
     *
     * @param string $fan
     *
     * @return Entity
     */
    public function setFan($fan)
    {
        $this->fan = $fan;

        return $this;
    }

    /**
     * Get fan.
     *
     * @return string
     */
    public function getFan()
    {
        return $this->fan;
    }

    /**
     * Set follow.
     *
     * @param string $follow
     *
     * @return Entity
     */
    public function setFollow($follow)
    {
        $this->follow = $follow;

        return $this;
    }

    /**
     * Get follow.
     *
     * @return string
     */
    public function getFollow()
    {
        return $this->follow;
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
