<template>
  <a-card :bordered="false">
    <template #title>
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
</template>

<script lang="ts">
  import { defineComponent } from 'vue'
  import { Time } from '/@/components/Time';
  import { Card, CardMeta, Button, List, ListItem, Image, TypographyText } from 'ant-design-vue'
  import { getDouyinOauthFormData, getDouyinIdLists } from '/@/api/page/douyin';
  import { GetDouyinIdResponse } from '/@/api/page/model/douyinModel';
  

  export default defineComponent({
    components: { 
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

      return { openBindWindow }
    },
    data() {
      let douyinIds : GetDouyinIdResponse = {"items": [], "total": 0}
      return {
        douyinIds
      }
    },
    created() {
      getDouyinIdLists().then((res) => {
        this.douyinIds = res
      })
    }
  })
</script>