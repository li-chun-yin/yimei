<template>
  <PageWrapper>
    <a-card :bordered="false">
      <template #title>
        <span class="iconify-inline float-left" data-icon="iconoir:tiktok" data-width="24" data-height="24"></span>
        抖音账号
      </template>
      <template #extra>
        <a-button @click="openBindWindow">添加账号</a-button>
      </template>

      <a-list :grid="{ gutter: 5, xs: 1, sm: 2, md: 4, lg: 4, xl: 6, xxl: 12 }" :dataSource="douyinIds.items">
        <template #renderItem="{ item }">
          <a-list-item>
            <a-card>
              <template #cover>
                <a-image :src="item.avatar" />
              </template>
              <a-card-meta>
                <template #title>
                  <TypographyText :content="item.nickname" />
                </template>
                <template #description>
                  <Time :value="item.update_time" />
                </template>
              </a-card-meta>
            </a-card>
          </a-list-item>
        </template>
      </a-list>
    </a-card>

    <a-card :bordered="false">
      <template #title>
        今日头条账号
      </template>
      <template #extra>
        <a-button @click="openBindToutiaoWindow">添加账号</a-button>
      </template>

      <a-list :grid="{ gutter: 5, xs: 1, sm: 2, md: 4, lg: 4, xl: 6, xxl: 12 }" :dataSource="toutiaoIds.items">
        <template #renderItem="{ item }">
          <a-list-item>
            <a-card>
              <template #cover>
                <a-image :src="item.avatar" />
              </template>
              <a-card-meta>
                <template #title>
                  <TypographyText :content="item.nickname" />
                </template>
                <template #description>
                  <Time :value="item.update_time" />
                </template>
              </a-card-meta>
            </a-card>
          </a-list-item>
        </template>
      </a-list>
    </a-card>

    <a-card :bordered="false">
      <template #title>
        西瓜视频账号
      </template>
      <template #extra>
        <a-button @click="openBindXiguaWindow">添加账号</a-button>
      </template>

      <a-list :grid="{ gutter: 5, xs: 1, sm: 2, md: 4, lg: 4, xl: 6, xxl: 12 }" :dataSource="xiguaIds.items">
        <template #renderItem="{ item }">
          <a-list-item>
            <a-card>
              <template #cover>
                <a-image :src="item.avatar" />
              </template>
              <a-card-meta>
                <template #title>
                  <TypographyText :content="item.nickname" />
                </template>
                <template #description>
                  <Time :value="item.update_time" />
                </template>
              </a-card-meta>
            </a-card>
          </a-list-item>
        </template>
      </a-list>
    </a-card>
  </PageWrapper>
</template>

<script lang="ts">
  import { defineComponent } from 'vue'
  import { PageWrapper } from '/@/components/Page'
  import { Time } from '/@/components/Time';
  import { Card, CardMeta, Button, List, ListItem, Image, TypographyText } from 'ant-design-vue'
  import { getDouyinOauthFormData, getDouyinIdLists } from '/@/api/page/douyin';
  import { getToutiaoOauthFormData, getToutiaoIdLists } from '/@/api/page/toutiao';
  import { getXiguaOauthFormData, getXiguaIdLists } from '/@/api/page/xigua';
  import { GetDouyinIdResponse } from '/@/api/page/model/douyinModel';
  import { GetXiguaIdResponse } from '/@/api/page/model/xiguaModel';
  import { GetToutiaoIdResponse } from '/@/api/page/model/toutiaoModel';
  

  export default defineComponent({
    name: 'IdPage',
    components: { 
      PageWrapper, 
      Time, 
      TypographyText, 
      [List.name]: List, 
      [ListItem.name]: ListItem, 
      [Card.name]: Card, 
      [CardMeta.name]: CardMeta, 
      [Button.name]:Button, 
      [Image.name]: Image
    },
    setup() {
      const oauthFormData = getDouyinOauthFormData()
      console.log(oauthFormData)
      const openBindWindow = () => { // 抖音授权窗口
        const win = window.open(oauthFormData.url + '?api_name=' + oauthFormData.api_name, '_new_oauth', 'width=800,height=650')
        const testWinExist = () => {
          console.log('testWinExist', win)
          if( win && win.closed == false ){
            win.focus()
            setTimeout(testWinExist, 500)
          } else {
            location.reload()
          }         
        }
        testWinExist()
      }

      const oauthToutiaoFormData = getToutiaoOauthFormData()
      const openBindToutiaoWindow = () => { // 抖音授权窗口
        const win = window.open(oauthToutiaoFormData.url + '?api_name=' + oauthToutiaoFormData.api_name, '_new_oauth', 'width=800,height=650')
        const testWinExist = () => {
          console.log('testWinExist', win)
          if( win && win.closed == false ){
            win.focus()
            setTimeout(testWinExist, 500)
          } else {
            location.reload()
          }         
        }
        testWinExist()
      }

      const oauthXiguaFormData = getXiguaOauthFormData()
      const openBindXiguaWindow = () => { // 抖音授权窗口
        const win = window.open(oauthXiguaFormData.url + '?api_name=' + oauthXiguaFormData.api_name, '_new_oauth', 'width=800,height=650')
        const testWinExist = () => {
          console.log('testWinExist', win)
          if( win && win.closed == false ){
            win.focus()
            setTimeout(testWinExist, 500)
          } else {
            location.reload()
          }         
        }
        testWinExist()
      }

      return { openBindWindow, openBindToutiaoWindow, openBindXiguaWindow }
    },
    data() {
      let douyinIds : GetDouyinIdResponse = {"items": [], "total": 0}
      let toutiaoIds : GetToutiaoIdResponse = {"items": [], "total": 0}
      let xiguaIds : GetXiguaIdResponse = {"items": [], "total": 0}
      return {
        douyinIds,
        toutiaoIds,
        xiguaIds
      }
    },
    created() {
      getDouyinIdLists().then((res) => {
        this.douyinIds = res
      }),
      getToutiaoIdLists().then((res) => {
        this.toutiaoIds = res
      }),
      getXiguaIdLists().then((res) => {
        this.xiguaIds = res
      })
    }
  })
</script>