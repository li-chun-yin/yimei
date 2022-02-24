<?php
namespace  library\model;

use asbamboo\di\ContainerAwareTrait;
use model\kuaishouId\Entity AS KuaishouIdEntity;
use library\kuaishou\ApiClient AS KuaishouApiClient;
use model\setting\Code AS SettingCode;
use model\setting\Repository AS SettingRepository;
use asbamboo\database\Factory AS DbFactory;
use model\kuaishouId\Manager AS KuaishouIdManager;
use exception\MessageException;
use model\douyinId\Entity AS DouyinIdEntity;
use model\douyinId\Manager AS DouyinIdManager;
use library\douyin\ApiClient AS DouyinApiClient;
use model\toutiaoId\Entity AS ToutiaoIdEntity;
use model\toutiaoId\Manager AS ToutiaoIdManager;

/**
 *
 * @author 李春寅 <http://licy.top>
 */
class CheckTokenStatus
{
    use ContainerAwareTrait;

    /**
     *
     * @param KuaishouIdEntity $KuaishouIdEntity
     * @throws MessageException
     */
    public function kuaishou(KuaishouIdEntity $KuaishouIdEntity) :void
    {
        $now    = time();

        /**
         * token没有过期
         */
        if($KuaishouIdEntity->getExpiresIn() > $now){
            return;
        }

        /**
         * token过期，但是允许使用refresh_token自动刷新
         */
        if($KuaishouIdEntity->getRefreshTokenExpiresIn() > $now){

            /**
             * @var DbFactory $Db
             * @var KuaishouIdManager $KuaishouIdManager
             * @var SettingRepository $SettingRepository
             */
            $Db                     = $this->Container->get(DbFactory::class);
            $KuaishouIdManager      = $this->Container->get(KuaishouIdManager::class);
            $SettingRepository      = $this->Container->get(SettingRepository::class);
            $SettingEntity          = $SettingRepository->findOneByType(SettingCode::TYPE_KUAISHOU);

            $Oauth2RefreshTokenResponse = KuaishouApiClient::request('Oauth2RefreshToken', [
                'app_id'                => $SettingEntity->getData()['app_id'],
                'app_secret'            => $SettingEntity->getData()['app_secret'],
                'refresh_token'         => $KuaishouIdEntity->getRefreshToken(),
            ]);

            $KuaishouIdManager->load($KuaishouIdEntity);
            $KuaishouIdManager->updateReauth([
                'access_token'              => $Oauth2RefreshTokenResponse->get('access_token'),
                'expires_in'                => $Oauth2RefreshTokenResponse->get('expires_in') + $now,
                'refresh_token'             => $Oauth2RefreshTokenResponse->get('refresh_token'),
                'refresh_token_expires_in'  => $Oauth2RefreshTokenResponse->get('refresh_token_expires_in') + $now,
                'scopes'                    => $Oauth2RefreshTokenResponse->get('scopes'),
            ]);

            $Db->getManager()->flush();

            return;
        }

        throw new MessageException('快手账号' . $KuaishouIdEntity->getName() . '授权已过期，需要重新登录.');
    }

    /**
     *
     * @param DouyinIdEntity $DouyinIdEntity
     * @throws MessageException
     */
    public function douyin(DouyinIdEntity $DouyinIdEntity) :void
    {
        $now    = time();

        /**
         * token没有过期
         */
        if($DouyinIdEntity->getExpiresIn() > $now){
            return;
        }

        /**
         * token过期，但是允许使用refresh_token自动刷新
         *  - token只能自动刷新5次
         */
        if($DouyinIdEntity->getRefreshExpiresIn() > $now && $DouyinIdEntity->getRefreshCount() < 5){
            /**
             * @var DbFactory $Db
             * @var DouyinIdManager $DouyinIdManager
             * @var SettingRepository $SettingRepository
             */
            $Db                     = $this->Container->get(DbFactory::class);
            $DouyinIdManager        = $this->Container->get(DouyinIdManager::class);
            $SettingRepository      = $this->Container->get(SettingRepository::class);
            $SettingEntity          = $SettingRepository->findOneByType(SettingCode::TYPE_DOUYIN);

            $OauthRenewRefreshTokenResponse = DouyinApiClient::request('OauthRenewRefreshToken', [
                'client_key'                => $SettingEntity->getData()['client_key'],
                'refresh_token'             => $DouyinIdEntity->getRefreshToken(),
            ]);

            $DouyinIdManager->load($DouyinIdEntity);
            $DouyinIdManager->updateReauth([
                'access_token'          => $DouyinIdEntity->getAccessToken(),
                'expires_in'            => $OauthRenewRefreshTokenResponse->get('expires_in') + $now,
                'refresh_token'         => $OauthRenewRefreshTokenResponse->get('refresh_token'),
                'refresh_expires_in'    => $DouyinIdEntity->getRefreshExpiresIn(),
                'scope'                 => $DouyinIdEntity->getScope(),
                'refresh_count'         => $DouyinIdEntity->getRefreshCount() + 1,
            ]);

            $Db->getManager()->flush();

            return;
        }

        throw new MessageException('抖音账号' . $DouyinIdEntity->getNickname() . '授权已过期，需要重新登录.');
    }

    /**
     *
     * @param ToutiaoIdEntity $ToutiaoIdEntity
     * @throws MessageException
     */
    public function toutiao(ToutiaoIdEntity $ToutiaoIdEntity) :void
    {
        $now    = time();

        /**
         * token没有过期
         */
        if($ToutiaoIdEntity->getExpiresIn() > $now){
            return;
        }

        /**
         * token过期，但是允许使用refresh_token自动刷新
         *  - token只能自动刷新5次
         */
        if($ToutiaoIdEntity->getRefreshExpiresIn() > $now && $ToutiaoIdEntity->getRefreshCount() < 5){
            /**
             * @var DbFactory $Db
             * @var ToutiaoIdManager $ToutiaoIdManager
             * @var SettingRepository $SettingRepository
             */
            $Db                     = $this->Container->get(DbFactory::class);
            $ToutiaoIdManager       = $this->Container->get(ToutiaoIdManager::class);
            $SettingRepository      = $this->Container->get(SettingRepository::class);
            $SettingEntity          = $SettingRepository->findOneByType(SettingCode::TYPE_DOUYIN);

            $OauthRenewRefreshTokenResponse = DouyinApiClient::request('ToutiaoOauthRenewRefreshToken', [
                'client_key'                => $SettingEntity->getData()['client_key'],
                'refresh_token'             => $ToutiaoIdEntity->getRefreshToken(),
            ]);

            $ToutiaoIdManager->load($ToutiaoIdEntity);
            $ToutiaoIdManager->updateReauth([
                'access_token'          => $ToutiaoIdEntity->getAccessToken(),
                'expires_in'            => $OauthRenewRefreshTokenResponse->get('expires_in') + $now,
                'refresh_token'         => $OauthRenewRefreshTokenResponse->get('refresh_token'),
                'refresh_expires_in'    => $ToutiaoIdEntity->getRefreshExpiresIn(),
                'scope'                 => $ToutiaoIdEntity->getScope(),
                'refresh_count'         => $ToutiaoIdEntity->getRefreshCount() + 1,
            ]);

            $Db->getManager()->flush();

            return;
        }

        throw new MessageException('今日头条账号' . $DouyinIdEntity->getNickname() . '授权已过期，需要重新登录.');
    }
}
