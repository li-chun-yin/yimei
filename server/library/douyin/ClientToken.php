<?php
namespace library\douyin; 

use model\setting\Manager AS SettingManager;
use model\setting\Code;

class ClientToken
{
    public static function getFreshAccessToken(SettingManager $SettingManager) : string
    {
        $time           = time();
        $SettingEntity  = $SettingManager->loadByType(Code::TYPE_DOUYIN);
        $data           = $SettingEntity->getData();
        if(!isset( $data['access_token'] ) || !isset($data['expires_in']) || $data['expires_in'] < $time ){
            $OauthClientTokenResponse       = ApiClient::request('OauthClientToken', [
                'client_key'                => $data['client_key'],
                'client_secret'             => $data['client_secret'],
            ]);
            
            $SettingManager->updateByDouyin([
                'client_key'    => $data['client_key'],
                'client_secret' => $data['client_secret'],
                'access_token'  => $OauthClientTokenResponse->get('access_token'),
                'expires_in'    => $OauthClientTokenResponse->get('expires_in') + $time,                
            ]);
        }
        
        return $SettingEntity->getData()['access_token'];
    }
}