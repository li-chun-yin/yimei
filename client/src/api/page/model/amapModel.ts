import { BasicFetchResult } from '/@/api/model/baseModel';

export interface PoiItem {
  label: string;
  value: string;
}

export interface GetPoiListParam {
  keywords: string;
  latitude: number|null;
  longitude: number|null;
}

export interface LocationModel {
  country: string,
  province: string,
  city: string,
  district: string,
  isp: string,
  location: string,
  ip: string,
}

/**
 * @description: Request list return value
 */
export type GetPoiListResponse = BasicFetchResult<PoiItem>;
