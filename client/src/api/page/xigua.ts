import { GetXiguaIdResponse } from './model/xiguaModel';
import { defHttp } from '/@/utils/http/axios';

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