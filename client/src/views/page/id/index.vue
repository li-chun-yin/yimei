<template>
  <PageWrapper>
    <DouyinId />
    <KuaishouId />
    <ToutiaoId />
    <XiguaId />
  </PageWrapper>
</template>

<script lang="ts">
  import { defineComponent } from 'vue'
  import { PageWrapper } from '/@/components/Page'
  import DouyinId from './components/douyin.vue';
  import KuaishouId from './components/kuaishou.vue';
  import XiguaId from './components/xigua.vue';
  import ToutiaoId from './components/toutiao.vue';
  

  export default defineComponent({
    name: 'IdPage',
    components: { 
      PageWrapper, 
      DouyinId,
      KuaishouId,
      ToutiaoId,
      XiguaId,
    }
  })
</script>