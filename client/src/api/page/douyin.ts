import { defHttp } from '/@/utils/http/axios';
import { 
	DouyinAppModel, 
	GetDouyinIdResponse, 
	SearchDouyinPoisParam, 
	SearchDouyinPoisResponse 
} from './model/douyinModel';
import { ErrorMessageMode } from '/#/axios';
import qs from 'qs'

/**
 * @description: Get douyin set info
 */
export const getDouyinInfo = () => {
  return defHttp.get<DouyinAppModel>({ 
    url: '',
    params: {api_name: 'douyin.get-config'}
  });
};

/**
 * @description: Post douyin set info
 */
export const postDouyinInfo = (params: DouyinAppModel, mode: ErrorMessageMode = 'modal') => {
  return defHttp.post<DouyinAppModel>(
    {
      headers: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      url: '',
      data : qs.stringify({...params, api_name: 'douyin.post-config'}),
    },
    {
      errorMessageMode: mode,
    },
  );
}

export const getDouyinOauthFormData = () => {
  console.log(defHttp)
  return {
    url: defHttp.options.requestOptions?.apiUrl,
    api_name: 'douyin.oauth',
    redirect_url: location.href,
  }
}

export const getDouyinIdLists = (params = {page : 1, limit : 999999999}) => {
  return defHttp.get<GetDouyinIdResponse>({ 
    url: '',
    params: { ...params, api_name: 'douyin.id-list' } 
  });
} 


export const searchDouyiPois = (params : SearchDouyinPoisParam = {city: "上海", count: 20, cursor: 0, keyword: "附近"}) => {
  return defHttp.get<SearchDouyinPoisResponse>({ 
    url: '',
    params: {...params, api_name: 'douyin.search-pois'}
  });
};