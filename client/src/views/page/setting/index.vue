<template>
  <PageWrapper>
    <SystemForm />
    <DouyinForm />
  </PageWrapper>
</template>

<script lang="ts">
  import { defineComponent } from 'vue'
  import { PageWrapper } from '/@/components/Page';
  import SystemForm from './components/SystemForm.vue';
  import DouyinForm from './components/DouyinForm.vue';

  export default defineComponent({
    name: 'SettingPage',
    components: { SystemForm, DouyinForm, PageWrapper },
  })
</script>