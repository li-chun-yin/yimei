<?php
namespace controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\RedirectResponse;
use asbamboo\http\ServerRequestInterface;
use library\kuaishou\ApiClient;
use model\setting\Manager AS SettingManager;
use model\setting\Code;
use model\kuaishouId\Manager AS KuaishouIdManager;
use model\kuaishouId\Repository AS KuaishouIdRepository;
use asbamboo\database\FactoryInterface;
use asbamboo\http\Response;
use asbamboo\http\Stream;

class Kuaishou extends ControllerAbstract
{
    public function code()
    {
        /**
         * @var FactoryInterface $Db
         * @var KuaishouIdManager $KuaishouIdManager
         * @var SettingManager $SettingManager
         * @var ServerRequestInterface $Request
         */
        $Request                = $this->Container->get(ServerRequestInterface::class);
        $SettingManager         = $this->Container->get(SettingManager::class);
        $KuaishouIdManager      = $this->Container->get(KuaishouIdManager::class);
        $KuaishouIdRepository   = $this->Container->get(KuaishouIdRepository::class);
        $Db                     = $this->Container->get(FactoryInterface::class);
        $target_uri             = $Request->getRequestParam('state');
        $code                   = $Request->getRequestParam('code');
        $time                   = time();

        $KuaishouSettingEntity    = $SettingManager->loadByType(Code::TYPE_KUAISHOU);

        $OauthAccessTokenResponse   = ApiClient::request('Oauth2AccessToken', [
            'app_id'                => $KuaishouSettingEntity->getData()['app_id'],
            'app_secret'            => $KuaishouSettingEntity->getData()['app_secret'],
            'code'                  => $code,
            'grant_type'            => 'authorization_code',
        ]);

        $KuaishouIdEntity             = $KuaishouIdRepository->findOneByOpenId($OauthAccessTokenResponse->get('open_id'));

        if(empty($KuaishouIdEntity)){
            $KuaishouIdEntity             = $KuaishouIdManager->load();
            $KuaishouIdManager->create([
                'open_id'                   => $OauthAccessTokenResponse->get('open_id'),
                'scopes'                    => $OauthAccessTokenResponse->get('scopes'),
                'access_token'              => $OauthAccessTokenResponse->get('access_token'),
                'expires_in'                => $time + $OauthAccessTokenResponse->get('expires_in'),
                'refresh_token_expires_in'  => $time + $OauthAccessTokenResponse->get('refresh_token_expires_in'),
                'refresh_token'             => $OauthAccessTokenResponse->get('refresh_token'),
            ]);
        }else{
            $KuaishouIdManager->load($KuaishouIdEntity);
            $KuaishouIdManager->updateReauth([
                'open_id'                   => $OauthAccessTokenResponse->get('open_id'),
                'scopes'                    => $OauthAccessTokenResponse->get('scopes'),
                'access_token'              => $OauthAccessTokenResponse->get('access_token'),
                'expires_in'                => $time + $OauthAccessTokenResponse->get('expires_in'),
                'refresh_token_expires_in'  => $time + $OauthAccessTokenResponse->get('refresh_token_expires_in'),
                'refresh_token'             => $OauthAccessTokenResponse->get('refresh_token'),
            ]);
        }


        $OauthUserInfoResponse  = ApiClient::request('OpenapiUserInfo', [
            'app_id'            => $KuaishouSettingEntity->getData()['app_id'],
            'access_token'      => $KuaishouIdEntity->getAccessToken(),
        ]);

        $KuaishouIdManager->updateUserInfo([
            'name'              => $OauthUserInfoResponse->get('user_info')['name'],
            'sex'               => $OauthUserInfoResponse->get('user_info')['sex'],
            'fan'               => $OauthUserInfoResponse->get('user_info')['fan'],
            'follow'            => $OauthUserInfoResponse->get('user_info')['follow'],
            'head'              => $OauthUserInfoResponse->get('user_info')['head'],
            'bigHead'           => $OauthUserInfoResponse->get('user_info')['bigHead'],
            'city'              => $OauthUserInfoResponse->get('user_info')['city'],
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
