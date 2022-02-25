import { defHttp } from '/@/utils/http/axios';
import { GetToutiaoIdResponse } from './model/toutiaoModel';
import qs from 'qs'

export const getToutiaoOauthFormData = () => {
  console.log(defHttp)
  return {
    url: defHttp.options.requestOptions?.apiUrl,
    api_name: 'toutiao.oauth',
    redirect_url: location.href,
  }
}

export const getToutiaoIdLists = (params = {page : 1, limit : 999999999}) => {
  return defHttp.get<GetToutiaoIdResponse>({ 
    url: '',
    params: { ...params, api_name: 'toutiao.id-list' } 
  });
}

/**
 * @description: Post toutiao set info
 */
 export const postToutiaoIdDisabled = (params : { open_id: string, disabled : number }, mode: ErrorMessageMode = 'modal') => {
  return defHttp.post<{status:String}>(
    {
      headers: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      url: '',
      data : qs.stringify({...params, api_name: 'toutiao.id-disabled'}),
    },
    {
      errorMessageMode: mode,
    },
  );
}