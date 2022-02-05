import { defHttp } from '/@/utils/http/axios';
import { GetToutiaoIdResponse } from './model/toutiaoModel';

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