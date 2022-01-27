<?php
namespace controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\RedirectResponse;
use asbamboo\http\ServerRequestInterface;
use library\douyin\ApiClient;
use model\setting\Manager AS SettingManager;
use model\setting\Code;
use model\douyinId\Manager AS DouyinIdManager;
use model\douyinId\Repository AS DouyinIdRepository;
use asbamboo\database\FactoryInterface;
use asbamboo\http\Response;
use asbamboo\http\Stream;

class Douyin extends ControllerAbstract
{
    public function code()
    {
        /**
         * @var FactoryInterface $Db
         * @var DouyinIdManager $DouyinIdManager
         * @var SettingManager $SettingManager
         * @var ServerRequestInterface $Request
         */
        $Request            = $this->Container->get(ServerRequestInterface::class);
        $SettingManager     = $this->Container->get(SettingManager::class);
        $DouyinIdManager    = $this->Container->get(DouyinIdManager::class);
        $DouyinIdRepository = $this->Container->get(DouyinIdRepository::class);
        $Db                 = $this->Container->get(FactoryInterface::class);
        $target_uri         = $Request->getRequestParam('state');
        $code               = $Request->getRequestParam('code');
        $time               = time();
        
        $DouyinSettingEntity    = $SettingManager->loadByType(Code::TYPE_DOUYIN);
        
        $OauthAccessTokenResponse   = ApiClient::request('OauthAccessToken', [
            'client_key'            => $DouyinSettingEntity->getData()['client_key'],
            'client_secret'         => $DouyinSettingEntity->getData()['client_secret'],
            'code'                  => $code,
            'grant_type'            => 'authorization_code',
        ]);
        
        $DouyinIdEntity             = $DouyinIdRepository->findOneByOpenId($OauthAccessTokenResponse->get('open_id'));
        
        if(empty($DouyinIdEntity)){
            $DouyinIdEntity             = $DouyinIdManager->load();
            $DouyinIdManager->create([
                'open_id'               => $OauthAccessTokenResponse->get('open_id'),
                'scope'                 => $OauthAccessTokenResponse->get('scope'),
                'access_token'          => $OauthAccessTokenResponse->get('access_token'),
                'expires_in'            => $time + $OauthAccessTokenResponse->get('expires_in'),
                'refresh_expires_in'    => $time + $OauthAccessTokenResponse->get('refresh_expires_in'),
                'refresh_token'         => $OauthAccessTokenResponse->get('refresh_token'),
            ]);
        }else{
            $DouyinIdManager->load($DouyinIdEntity);
            $DouyinIdManager->updateReauth([
                'open_id'               => $OauthAccessTokenResponse->get('open_id'),
                'scope'                 => $OauthAccessTokenResponse->get('scope'),
                'access_token'          => $OauthAccessTokenResponse->get('access_token'),
                'expires_in'            => $time + $OauthAccessTokenResponse->get('expires_in'),
                'refresh_expires_in'    => $time + $OauthAccessTokenResponse->get('refresh_expires_in'),
                'refresh_token'         => $OauthAccessTokenResponse->get('refresh_token'),
            ]);
        }
        
        
        $OauthUserInfoResponse      = ApiClient::request('OauthUserInfo', [
            'open_id'               => $DouyinIdEntity->getOpenId(),
            'access_token'          => $DouyinIdEntity->getAccessToken(),
        ]);
        
        $DouyinIdManager->updateUserInfo([
            'avatar'                => $OauthUserInfoResponse->get('avatar'),
            'city'                  => $OauthUserInfoResponse->get('city'),
            'country'               => $OauthUserInfoResponse->get('country'),
            'e_account_role'        => $OauthUserInfoResponse->get('e_account_role'),
            'gender'                => $OauthUserInfoResponse->get('gender'),
            'nickname'              => $OauthUserInfoResponse->get('nickname'),
            'province'              => $OauthUserInfoResponse->get('province'),
            'union_id'              => $OauthUserInfoResponse->get('union_id'),
        ]);

        $Db->getManager()->flush();
        
        if(empty($target_uri)){
            $Stream     = new Stream('php://temp', 'w+b');
            $Stream->write('<script type="text/javascript">window.close();</script>');
            return new Response($Stream);
        }
        return new RedirectResponse($target_uri);
    }
}
