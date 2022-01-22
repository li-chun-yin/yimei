<template>
  <PageWrapper>
    <a-card :bordered="false">
      <template #title>
        <span class="iconify-inline float-left" data-icon="iconoir:tiktok" data-width="24" data-height="24"></span>
        抖音账号
      </template>
      <template #extra>
        <form :action="oauthFormData.url">
          <input type="hidden" :value="oauthFormData.api_name" name="api_name" />
          <input type="hidden" :value="oauthFormData.redirect_url" name="redirect_url" />
          <a-button htmlType="submit">添加账号</a-button>
        </form>
      </template>

      <a-list :grid="{ gutter: 5, xs: 1, sm: 2, md: 4, lg: 4, xl: 6, xxl: grid }" :dataSource="douyinIds.items">
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
  import { getDouyinOauthFormData, getDouyinIdLists } from '/@/api/page/douyin'
  import { GetDouyinIdResponse } from '/@/api/page/model/douyinModel';

  export default defineComponent({
    name: 'IdPage',
    components: { PageWrapper, Time, TypographyText, [List.name]: List, [ListItem.name]: ListItem, [Card.name]: Card, [CardMeta.name]: CardMeta, [Button.name]:Button, [Image.name]: Image },
    setup() {
      const oauthFormData = getDouyinOauthFormData()
      console.log(oauthFormData)

      return { oauthFormData }
    },
    data() {
      let douyinIds : GetDouyinIdResponse = {"items": [], "total": 0}      
      return {
        douyinIds: douyinIds
      }
    },
    created() {
      getDouyinIdLists().then((res) => {
        this.douyinIds = res
      })
    }
  })
</script>