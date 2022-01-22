import { defHttp } from '/@/utils/http/axios';
import { GetPoiListParam, GetPoiListResponse, LocationModel } from './model/amapModel';

/**
 * @description: Get poi lists
 */
export const getPoiList = (params: GetPoiListParam) => {
  return defHttp.get<GetPoiListResponse>({ 
    url: '',
    params: {...params, api_name: 'amap.place-around'}
  });
};

/**
 * @description: Get poi lists
 */
 export const getLocationByIp = (params = {ip: ''}) => {
  return defHttp.get<LocationModel>({ 
    url: '',
    params: {...params, api_name: 'amap.ip-location'}
  });
};