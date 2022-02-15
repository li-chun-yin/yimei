<template>
  <a-card title="抖音开放平台参数" :bordered="false" class="!mt-5">
    <BasicForm @register="registerDouyinForm" />
  </a-card>
</template>

<script lang="ts">
  import { BasicForm, useForm } from '/@/components/Form'
  import { defineComponent } from 'vue'
  import { Card } from 'ant-design-vue'
  import { getDouyinInfo, postDouyinInfo } from '/@/api/page/douyin'
  import { douyinSchemas } from '../data/DouyinData'
  import { useMessage } from '/@/hooks/web/useMessage';

  export default defineComponent({
    components: { BasicForm, [Card.name]: Card },
    setup() {

      const { notification } = useMessage();

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

      return { registerDouyinForm }
    }
  })
</script>