<?php
namespace controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\RedirectResponse;
use asbamboo\http\ServerRequestInterface;
use library\douyin\ApiClient;
use model\setting\Manager AS SettingManager;
use model\setting\Code;
use model\toutiaoId\Manager AS ToutiaoIdManager;
use model\toutiaoId\Repository AS ToutiaoIdRepository;
use asbamboo\database\FactoryInterface;
use asbamboo\http\Response;
use asbamboo\http\Stream;

class Toutiao extends ControllerAbstract
{
    public function code()
    {
        /**
         * @var FactoryInterface $Db
         * @var ToutiaoIdManager $ToutiaoIdManager
         * @var SettingManager $SettingManager
         * @var ServerRequestInterface $Request
         */
        $Request            = $this->Container->get(ServerRequestInterface::class);
        $SettingManager     = $this->Container->get(SettingManager::class);
        $ToutiaoIdManager   = $this->Container->get(ToutiaoIdManager::class);
        $ToutiaoIdRepository= $this->Container->get(ToutiaoIdRepository::class);
        $Db                 = $this->Container->get(FactoryInterface::class);
        $target_uri         = $Request->getRequestParam('state');
        $code               = $Request->getRequestParam('code');
        $time               = time();
        
        $ToutiaoSettingEntity    = $SettingManager->loadByType(Code::TYPE_DOUYIN);
        
        $OauthAccessTokenResponse   = ApiClient::request('ToutiaoOauthAccessToken', [
            'client_key'            => $ToutiaoSettingEntity->getData()['client_key'],
            'client_secret'         => $ToutiaoSettingEntity->getData()['client_secret'],
            'code'                  => $code,
            'grant_type'            => 'authorization_code',
        ]);
        
        $ToutiaoIdEntity             = $ToutiaoIdRepository->findOneByOpenId($OauthAccessTokenResponse->get('open_id'));
        
        if(empty($ToutiaoIdEntity)){
            $ToutiaoIdEntity             = $ToutiaoIdManager->load();
            $ToutiaoIdManager->create([
                'open_id'               => $OauthAccessTokenResponse->get('open_id'),
                'scope'                 => $OauthAccessTokenResponse->get('scope'),
                'access_token'          => $OauthAccessTokenResponse->get('access_token'),
                'expires_in'            => $time + $OauthAccessTokenResponse->get('expires_in'),
                'refresh_expires_in'    => $time + $OauthAccessTokenResponse->get('refresh_expires_in'),
                'refresh_token'         => $OauthAccessTokenResponse->get('refresh_token'),
            ]);
        }else{
            $ToutiaoIdManager->load($ToutiaoIdEntity);
            $ToutiaoIdManager->updateReauth([
                'open_id'               => $OauthAccessTokenResponse->get('open_id'),
                'scope'                 => $OauthAccessTokenResponse->get('scope'),
                'access_token'          => $OauthAccessTokenResponse->get('access_token'),
                'expires_in'            => $time + $OauthAccessTokenResponse->get('expires_in'),
                'refresh_expires_in'    => $time + $OauthAccessTokenResponse->get('refresh_expires_in'),
                'refresh_token'         => $OauthAccessTokenResponse->get('refresh_token'),
            ]);
        }
        
        
        $OauthUserInfoResponse      = ApiClient::request('ToutiaoOauthUserInfo', [
            'open_id'               => $ToutiaoIdEntity->getOpenId(),
            'access_token'          => $ToutiaoIdEntity->getAccessToken(),
        ]);
        
        $ToutiaoIdManager->updateUserInfo([
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
