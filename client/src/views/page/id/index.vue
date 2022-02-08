<template>
  <PageWrapper>
    <DouyinId />
    <ToutiaoId />
    <XiguaId />
  </PageWrapper>
</template>

<script lang="ts">
  import { defineComponent } from 'vue'
  import { PageWrapper } from '/@/components/Page'
  import XiguaId from './components/xigua.vue';
  import DouyinId from './components/douyin.vue';
  import ToutiaoId from './components/toutiao.vue';
  

  export default defineComponent({
    name: 'IdPage',
    components: { 
      PageWrapper, 
      DouyinId,
      ToutiaoId,
      XiguaId,
    }
  })
</script>