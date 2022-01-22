import { MockMethod } from 'vite-plugin-mock';
import { resultSuccess } from '../_util';

const systemInfo = {
  api_url: 'localhost',
};

const douyinInfo = {
  client_key: 'testkey',
  client_secret: 'testsecret',
};

export default [
  {
    url: '/basic-api/get-system-info',
    timeout: 1000,
    method: 'get',
    response: () => {
      return resultSuccess(systemInfo)
    },
  },
  {
    url: '/basic-api/post-system-info',
    timeout: 1000,
    method: 'post',
    response: ({ body }) => {
      const { api_url } = body
      systemInfo.api_url = api_url
      return resultSuccess(systemInfo)
    },
  },
  {
    url: '/basic-api/get-douyin-info',
    timeout: 1000,
    method: 'get',
    response: () => {
      return resultSuccess(douyinInfo)
    },
  },
  {
    url: '/basic-api/post-douyin-info',
    timeout: 1000,
    method: 'post',
    response: ({ body }) => {
      const { client_key, client_secret } = body
      douyinInfo.client_key = client_key
      douyinInfo.client_secret = client_secret
      return resultSuccess(douyinInfo)
    },
  }
] as MockMethod[]