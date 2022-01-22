import { BasicFetchResult, SampleFetchResult } from '/@/api/model/baseModel';

export interface DouyinAppModel {
  client_key: string;
  client_secret: string;
}

export interface DouyinIdModel {
  open_id: string;
  avatar: string;
  nickname: string;
  update_time: number;
}

export type GetDouyinIdResponse = BasicFetchResult<DouyinIdModel>;


export interface SearchDouyinPoisParam {
  city: string;
  count: number;
  cursor: number;
  keyword: string;
}

export interface DouyinPoisModel {
  address: string;
  city: string;
  city_code: string;
  country: string;
  country_code: string;
  district: string;
  location: string;
  poi_id: string;
  poi_name: string;
  province: string;
}
 
export type SearchDouyinPoisResponse = SampleFetchResult<DouyinPoisModel>;