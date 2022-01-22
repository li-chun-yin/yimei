<template>
  <PageWrapper>
    <a-card title="系统参数" :bordered="false">
      <BasicForm @register="registerSystemForm" />
    </a-card>
    <a-card title="抖音开放平台参数" :bordered="false" class="!mt-5">
      <BasicForm @register="registerDouyinForm" />
    </a-card>
  </PageWrapper>
</template>

<script lang="ts">
  import { BasicForm, useForm } from '/@/components/Form'
  import { defineComponent } from 'vue'
  import { PageWrapper } from '/@/components/Page';
  import { Card } from 'ant-design-vue'
  import { getDouyinInfo, postDouyinInfo } from '/@/api/page/douyin'
  import { systemSchemas, douyinSchemas } from './data'
  import { useMessage } from '/@/hooks/web/useMessage';

  export default defineComponent({
    name: 'SettingPage',
    components: { BasicForm, PageWrapper, [Card.name]: Card },
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

      const [registerDouyinForm, { setFieldsValue: setDouyinFormData, validate: douyinValidate, setProps: setDouyinFormProps }] = useForm({
        labelCol: {
          span: 3,
        },
        wrapperCol: {
          span: 10,
        },
        actionColOptions: {
          span: 13,
        },
        schemas: douyinSchemas,
        showResetButton: false,
        submitButtonOptions: {
          text: '提交',
        },
        submitFunc: doSubmitDouyinForm
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

      async function doSubmitDouyinForm() {
        try {

          const [values] = await Promise.all([douyinValidate()])

          console.log('form data:', values);
          
          setDouyinFormProps({
            submitButtonOptions: { loading: true }
          })

          await postDouyinInfo(values)

          notification.success({
            message: '修改成功',
            duration: 3,
          });

          setDouyinFormProps({
            submitButtonOptions: { loading: false }
          })

        } catch (error) {
          setDouyinFormProps({
            submitButtonOptions: { loading: false }
          })
        }
      }

      getDouyinInfo().then(data => {
        console.log(data)
        setDouyinFormData(data)
      })

      return { registerSystemForm, registerDouyinForm }
    }
  })
</script>