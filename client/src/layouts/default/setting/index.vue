<template>
  <!-- <div @click="openDrawer(true)"> 做了一些调整，现在这个设置项没用 -->
  <div @click="openDrawer(true)" style="display:none;">
    <Icon icon="ion:settings-outline" />
    <SettingDrawer @register="register" />
  </div>
</template>
<script lang="ts">
  import { defineComponent } from 'vue';
  import SettingDrawer from './SettingDrawer';
  import Icon from '/@/components/Icon';

  import { useDrawer } from '/@/components/Drawer';

  export default defineComponent({
    name: 'SettingButton',
    components: { SettingDrawer, Icon },
    setup() {
      const [register, { openDrawer }] = useDrawer();

      return {
        register,
        openDrawer,
      };
    },
  });
</script>
