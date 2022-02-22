<template>
  <a-card title="快手开放平台参数" :bordered="false" class="!mt-5">
      <template #extra>
        <a href="https://open.kuaishou.com" target="_blank">通过快手开放平台创建应用获取参数</a>
      </template>
    <BasicForm @register="registerKuaishouForm" />
  </a-card>
</template>

<script lang="ts">
  import { BasicForm, useForm } from '/@/components/Form'
  import { defineComponent } from 'vue'
  import { Card } from 'ant-design-vue'
  import { getKuaishouInfo, postKuaishouInfo } from '/@/api/page/kuaishou'
  import { kuaishouSchemas } from '../data/KuaishouData'
  import { useMessage } from '/@/hooks/web/useMessage';

  export default defineComponent({
    components: { BasicForm, [Card.name]: Card },
    setup() {

      const { notification } = useMessage();

      const [registerKuaishouForm, { setFieldsValue: setKuaishouFormData, validate: douyinValidate, setProps: setDouyinFormProps }] = useForm({
        labelCol: {
          span: 3,
        },
        wrapperCol: {
          span: 10,
        },
        actionColOptions: {
          span: 13,
        },
        schemas: kuaishouSchemas,
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

          await postKuaishouInfo(values)

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

      getKuaishouInfo().then(data => {
        console.log(data)
        setKuaishouFormData(data)
      })

      return { registerKuaishouForm }
    }
  })
</script>