<template>
  <PageWrapper class="c-index-page">
    <AppLogo :alwaysShowTitle="true" class="c-logo" />

    <a-card class="c-card">
      <a-card-grid class="c-grid">
        <router-link :to="{name: 'SettingPage'}">
          <span class="flex flex-col items-center">
            <Icon icon="ion:settings" color="#1fdaca" size="30" />
            <span class="text-lg mt-3">
              系统设置
            </span>
          </span>
        </router-link>
      </a-card-grid>
      <a-card-grid class="c-grid">
        <router-link :to="{name: 'IdPage'}">
          <span class="flex flex-col items-center">
            <Icon icon="ion:id-card" color="#bf0c2c" size="30" />
            <span class="text-lg mt-3">账号绑定</span>
          </span>
        </router-link>
      </a-card-grid>
      <a-card-grid class="c-grid">
        <router-link :to="{name: 'VideoCreatePage'}">
          <span class="flex flex-col items-center">
            <Icon icon="ion:videocam-outline" color="#e18525" size="30" />
            <span class="text-lg mt-3">发布视频</span>
          </span>
        </router-link>
      </a-card-grid>
      <a-card-grid class="c-grid">
        <router-link :to="{name: 'VideoIndexPage'}">
          <span class="flex flex-col items-center">
            <Icon icon="ion:videocam" color="#3fb27f" size="30" />
            <span class="text-lg mt-3">视频管理</span>
          </span>
        </router-link>
      </a-card-grid>
    </a-card>
  </PageWrapper>
</template>

<script lang="ts">
  import { defineComponent } from 'vue'
  import { PageWrapper } from '/@/components/Page'
  import { Card, CardGrid } from 'ant-design-vue'
  import { Icon } from '/@/components/Icon';
  import { AppLogo } from '/@/components/Application';
import { useUserStore } from '/@/store/modules/user';

  export default defineComponent({
    name: 'RootPage',
    components: {
      PageWrapper,
      AppLogo,
      Icon,
      [Card.name]: Card,
      [CardGrid.name]: CardGrid
    },
    setup() {

      const userStore = useUserStore();
      userStore.login({
        username: '因为我这个程序不需要登录，所以这里模拟登录，1是因为我对这个前端末班中权限登录这块东西还不是很了解，2是为了更少的修改和阅读这个模版的代码',
        password: '因为我这个程序不需要登录，所以这里模拟登录，1是因为我对这个前端末班中权限登录这块东西还不是很了解，2是为了更少的修改和阅读这个模版的代码',
        goHome: false,
      });

      return {}
    }
  })
</script>

<style lang="less">
.c-index-page {
  .c-logo {
    padding-left: calc(50% - 75px);
    padding-top: 2em;
    padding-bottom: 2em;
    &__title {
      font-size: 24px;
      color: #fff;
    }

    img {
      width: 48px;
    }
  }
  .c-card {
    margin-left: 25%;
    width: 50%;
    .c-grid {
      width: 50%;
    }
  }
}
</style>