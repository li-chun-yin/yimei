<template>
    <a-card title="系统参数" :bordered="false">
        <BasicForm @register="registerSystemForm" />
    </a-card>
</template>

<script lang="ts">
  import { BasicForm, useForm } from '/@/components/Form'
  import { defineComponent } from 'vue'
  import { Card } from 'ant-design-vue'
  import { systemSchemas } from '../data/SystemData'
  import { useMessage } from '/@/hooks/web/useMessage';

  export default defineComponent({
    components: { BasicForm, [Card.name]: Card },
    setup() {

      const { notification } = useMessage();

      const [registerSystemForm, { validate: systemValidate, setProps: setSystemFormProps }] = useForm({
        labelCol: {
          span: 3,
        },
        wrapperCol: {
          span: 10,
        },
        actionColOptions: {
          span: 13,
        },
        schemas: systemSchemas,
        showResetButton: false,
        submitButtonOptions: {
          text: '提交',
        },
        submitFunc: doSubmitSystemForm
      })

      async function doSubmitSystemForm() {
        try {

          const [values] = await Promise.all([systemValidate()])

          setSystemFormProps({
            submitButtonOptions: { loading: true }
          })

          localStorage.setItem('SYSTEM_API_URL', values.api_url)

          setSystemFormProps({
            submitButtonOptions: { loading: false }
          })

          notification.success({
            message: '修改成功',
            duration: 3,
          });

          console.log('form data:', values)

          location.reload()

        } catch (error) {

          setSystemFormProps({
            submitButtonOptions: { loading: false }
          })          
          
        }
      }
      return { registerSystemForm }
    }
  })
</script>