import { FormSchema } from '/@/components/Form'

const SYSTEM_API_URL = localStorage.getItem('SYSTEM_API_URL')

export const systemSchemas: FormSchema[] = [
  {
    field: 'api_url',
    component: 'Input',
    label: '服务端API url',
    required: true,
    defaultValue: SYSTEM_API_URL
  }
]