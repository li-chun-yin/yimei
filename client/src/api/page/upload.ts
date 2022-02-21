import qs from 'qs'
import { UploadDataResult } from "./model/uploadModel";
import { ErrorMessageMode } from '/#/axios';
import { defHttp } from '/@/utils/http/axios';

export const postUploadDataSync = (params = {data:String}, mode: ErrorMessageMode = 'modal') => {
  return defHttp.post<UploadDataResult>(
    {
      headers: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      url: '',
      data : qs.stringify({...params, api_name: 'upload.data'}),
    },
    {
      errorMessageMode: mode,
    },
  );
}