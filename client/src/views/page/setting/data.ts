import { FormSchema } from '/@/components/Form'

const SYSTEM_API_URL = localStorage.getItem('SYSTEM_API_URL')

export const systemSchemas: FormSchema[] = [
  {
    field: 'api_url',
    component: 'Input',
    label: 'API URL',
    required: true,
    defaultValue: SYSTEM_API_URL
  }
]

export const douyinSchemas: FormSchema[] = [
  {
    field: 'client_key',
    component: 'Input',
    label: 'client_key',
    required: true,
  },
  {
    field: 'client_secret',
    component: 'Input',
    label: 'client_secret',
    required: true,
  }
]