import { FormSchema } from '/@/components/Form'

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