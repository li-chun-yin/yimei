import { BasicFetchResult } from '/@/api/model/baseModel';

export interface ToutiaoIdModel {
  open_id: string;
  avatar: string;
  nickname: string;
  update_time: number;
}

export type GetToutiaoIdResponse = BasicFetchResult<ToutiaoIdModel>;