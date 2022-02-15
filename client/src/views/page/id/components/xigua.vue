<template>
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
</template>

<script lang="ts">
  import { defineComponent } from 'vue'
  import { Time } from '/@/components/Time';
  import { Card, CardMeta, Button, List, ListItem, Image, TypographyText } from 'ant-design-vue'
  import { getXiguaOauthFormData, getXiguaIdLists } from '/@/api/page/xigua';
  import { GetXiguaIdResponse } from '/@/api/page/model/xiguaModel';
  

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

      return { openBindXiguaWindow }
    },
    data() {
      let xiguaIds : GetXiguaIdResponse = {"items": [], "total": 0}
      return {
        xiguaIds
      }
    },
    created() {
      getXiguaIdLists().then((res) => {
        this.xiguaIds = res
      })
    }
  })
</script>