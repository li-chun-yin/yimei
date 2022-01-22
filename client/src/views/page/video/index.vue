<template>
  <PageWrapper class="video-index">
    <a-card :bordered="false" title="视频列表">
      <a-list :dataSource="items" :pagination="paginationProp">
        <template #renderItem="{ item }">
          <a-list-item class="list-item">
            <a-card>
              <template #cover>
                <video :src="item.url" controls="controls" class="video" />
              </template>
              <a-card-meta>
                <template #title>
                  <router-link :to="{name: 'VideoSyncPage', params: {id: item.upload_id}}"><TypographyText :content="item.original_name" /></router-link>
                </template>
                <template #description>
                  <a-tag v-if="item.status == VIDEO_INDEX_STATUS.WAIT" color="default">{{ item.status_text }}</a-tag>
                  <a-tag v-if="item.status == VIDEO_INDEX_STATUS.ING" color="processing">{{ item.status_text }}</a-tag>
                  <a-tag v-if="item.status == VIDEO_INDEX_STATUS.DONE" color="success">{{ item.status_text }}</a-tag>
                  <a-tag v-if="item.status == VIDEO_INDEX_STATUS.PART" color="warning">{{ item.status_text }}</a-tag>
                  <Time :value="item.update_time" />
                </template>
              </a-card-meta>
            </a-card>
          </a-list-item>
        </template>
        <div style="clear:both;"></div>
      </a-list>
    </a-card>
  </PageWrapper>
</template>

<script lang="ts">
  import { defineComponent, ref } from 'vue'
  import { PageWrapper } from '/@/components/Page'
  import { Time } from '/@/components/Time';
  import { Card, CardMeta, List, ListItem, TypographyText, Tag } from 'ant-design-vue'
  import { getVideoIndex } from '/@/api/page/video'
  import { VIDEO_INDEX_STATUS } from '/@/api/page/model/videoModel';

  export default defineComponent({
    name: 'VideoIndexPage',
    components: { 
      PageWrapper, 
      Time, 
      TypographyText,
      [List.name]: List,
      [ListItem.name]: ListItem, 
      [Card.name]: Card, 
      [CardMeta.name]: CardMeta,
      [Tag.name]: Tag
    },
    setup() {
      return { VIDEO_INDEX_STATUS }
    },
    data() {
      return {
        items: [],
        paginationProp: {
          total: 0,
          current: 0,
          pageSize: 0,
          showSizeChanger: false,
          showQuickJumper: true,
          showTotal: (total:number) => `总 ${total} 条`,
          onChange: this.fetchData,
        }
      }
    },
    created() {
      this.fetchData(1, 16);
    },
    methods: {
      fetchData(page : number, limit: number){
        this.paginationProp.pageSize = limit
        getVideoIndex({page, limit}).then((res) => {
          this.items = res.items
          this.paginationProp.total = res.total
        })
      },
    }
  })
</script>

<style lang="less">
.video-index{
  .list-item {
    float: left;
    margin-right: 15px;
    width: calc(720px * 0.275);
    overflow-x: scroll;
    
    .video {
      width: calc(720px * 0.275);
      height: calc(1280px * 0.275);
      background: black;
      border: 1px black solid;
      border-radius: 15px;
    }
  }
}
</style>