import { FormSchema } from '/@/components/Form'

export const kuaishouSchemas: FormSchema[] = [
  {
    field: 'app_id',
    component: 'Input',
    label: 'app_id',
    required: true,
  },
  {
    field: 'app_secret',
    component: 'Input',
    label: 'app_secret',
    required: true,
  }
]