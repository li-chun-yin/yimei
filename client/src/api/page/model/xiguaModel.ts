import { BasicFetchResult } from '/@/api/model/baseModel';

export interface XiguaIdModel {
  open_id: string;
  avatar: string;
  nickname: string;
  update_time: number;
}

export type GetXiguaIdResponse = BasicFetchResult<XiguaIdModel>;