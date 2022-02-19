import { BasicFetchResult } from '/@/api/model/baseModel';

export interface KuaishouAppModel {
  app_id: string;
  app_secret: string;
}

export interface KuaishouIdModel {
  open_id: string;
  head: string;
  name: string;
  update_time: number;
}

export type GetKuaishouIdResponse = BasicFetchResult<KuaishouIdModel>;
