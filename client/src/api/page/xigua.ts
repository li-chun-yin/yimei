import { GetXiguaIdResponse } from './model/xiguaModel';
import { defHttp } from '/@/utils/http/axios';
import qs from 'qs'

export const getXiguaOauthFormData = () => {
  console.log(defHttp)
  return {
    url: defHttp.options.requestOptions?.apiUrl,
    api_name: 'xigua.oauth',
    redirect_url: location.href,
  }
}

export const getXiguaIdLists = (params = {page : 1, limit : 999999999}) => {
  return defHttp.get<GetXiguaIdResponse>({ 
    url: '',
    params: { ...params, api_name: 'xigua.id-list' } 
  });
}

/**
 * @description: Post xigua set info
 */
 export const postXiguaIdDisabled = (params : { open_id: string, disabled : number }, mode: ErrorMessageMode = 'modal') => {
  return defHttp.post<{status:String}>(
    {
      headers: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      url: '',
      data : qs.stringify({...params, api_name: 'xigua.id-disabled'}),
    },
    {
      errorMessageMode: mode,
    },
  );
}