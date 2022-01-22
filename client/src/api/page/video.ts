import { defHttp } from '/@/utils/http/axios';
import { VideoInfoModel, PostVideoSyncParam, PostVideoSyncResponse, GetVideoIndexResponse, PostVideoSyncBasicParam, PostVideoSyncBasicResponse } from './model/videoModel';
import { ErrorMessageMode } from '/#/axios';
import qs from 'qs'
import { useGlobSetting } from '/@/hooks/setting';

export const getVideoCreateUrl = () => {
  return useGlobSetting().apiUrl + '?api_name=video.create';
}

export const getVideoInfo = (params : {id : string}) => {
  return defHttp.get<VideoInfoModel>({ 
    url: '',
    params: {...params, api_name: 'video.info'}
  });
};

 export const getVideoIndex = (params = {page : 1, limit : 36}) => {
  return defHttp.get<GetVideoIndexResponse>({ 
    url: '',
    params: {...params, api_name: 'video.index'}
  });
};

export const postVideoSync = (params: PostVideoSyncParam, mode: ErrorMessageMode = 'modal') => {
  return defHttp.post<PostVideoSyncResponse>(
    {
      headers: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      url: '',
      data : qs.stringify({...params, api_name: 'video.sync'}),
    },
    {
      errorMessageMode: mode,
    },
  );
}

export const postVideoSyncBasic = (params: PostVideoSyncBasicParam, mode: ErrorMessageMode = 'modal') => {
  return defHttp.post<PostVideoSyncBasicResponse>(
    {
      headers: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      url: '',
      data : qs.stringify({...params, api_name: 'video.sync-basic'}),
    },
    {
      errorMessageMode: mode,
    },
  );
}