import { defHttp } from '/@/utils/http/axios';
import { ErrorMessageMode } from '/#/axios';
import qs from 'qs'
import { KuaishouAppModel, GetKuaishouIdResponse } from './model/kuaishouModel';

/**
 * @description: Get kuaishou set info
 */
export const getKuaishouInfo = () => {
  return defHttp.get<KuaishouAppModel>({ 
    url: '',
    params: {api_name: 'kuaishou.get-config'}
  });
};

/**
 * @description: Post kuaishou set info
 */
export const postKuaishouInfo = (params: KuaishouAppModel, mode: ErrorMessageMode = 'modal') => {
  return defHttp.post<KuaishouAppModel>(
    {
      headers: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      url: '',
      data : qs.stringify({...params, api_name: 'kuaishou.post-config'}),
    },
    {
      errorMessageMode: mode,
    },
  );
}

export const getKuaishouOauthFormData = () => {
  console.log(defHttp)
  return {
    url: defHttp.options.requestOptions?.apiUrl,
    api_name: 'kuaishou.oauth',
    redirect_url: location.href,
  }
}

export const getKuaishouIdLists = (params = {page : 1, limit : 999999999}) => {
  return defHttp.get<GetKuaishouIdResponse>({ 
    url: '',
    params: { ...params, api_name: 'kuaishou.id-list' }
  });
}  
