import { BasicFetchResult } from "../../model/baseModel";

export interface VideoInfoModel {
  id: string;
  url: string;
}

export enum SYNC_TYPE {
  DOUYIN = 0,
  TOUTIAO = 1,
  XIGUA = 2,
  KUAISHOU = 3
}
export enum SYNC_STATUS {
  ING = 0,
  DONE = 1
}
export interface PostVideoSyncParam {
  unikey: string;
  upload_id: string;
  type: SYNC_TYPE;
  sync_request: {
    text: string|null;
    poi_id: string|null;
    poi_name: string|null;
    custom_cover_image_id: string|null;
  }
}
export interface PostVideoSyncResponse {
  status: string;
}


export enum VIDEO_INDEX_STATUS {
  WAIT = 0,
  DONE = 1,
  PART = 2,
  ING = 3
}
export interface VideoIndexModel {
  url: string;
  original_name: string;
  update_time: number;
  upload_id: string;
  status: VIDEO_INDEX_STATUS;
  status_text: string;
}
export type GetVideoIndexResponse = BasicFetchResult<VideoIndexModel>;


export interface PostVideoSyncBasicParam {
  upload_id: string;
  text: string|null;
  poi_id: string|null;
  poi_name: string|null;
  custom_cover_image_id: string|null;
}
export interface PostVideoSyncBasicResponse {
  status: string;
}
