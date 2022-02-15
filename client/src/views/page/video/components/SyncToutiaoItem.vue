<template>
  <a-list-item class="c-list-item">
    <a-card class="c-card">
      <a-card-meta :title="item.id.nickname">
        <template #avatar>
          <a-avatar :src="item.id.avatar" />
        </template>
        <template #description>
          <a-tag color="#87d068">今日头条</a-tag>
          <Time :value="item.id.update_time" />
        </template>
      </a-card-meta>
      
      <a-form :model="item" :label-col="{span: 7}" class="c-sync-form">

        <a-form-item label="视频标题" required>
          <template v-if="item.sync_status != -1">{{item.text}}</template>
          <a-textarea v-else v-model:value="item.text" showCount :autoSize="{minRows: 3, maxRows: 3}" />
        </a-form-item>

        <a-button v-if="item.sync_status == SYNC_STATUS.ING" disabled block shape="round">发布中...</a-button>
        <a-button v-else-if="item.sync_status == SYNC_STATUS.DONE" disabled block shape="round">已经发布</a-button>
        <a-button v-else @click="item.onSync" :loading="item.loading" block shape="round">立即发布</a-button>
      </a-form>
    </a-card>
  </a-list-item>
</template>

<script lang="ts">
import { defineComponent} from 'vue'
import { 
  Card, 
  CardMeta, 
  List, 
  ListItem, 
  Avatar, 
  Form, 
  FormItem, 
  Input, 
  Button, 
  Tag, 
} from 'ant-design-vue'
import { Time } from '/@/components/Time';
import{ SYNC_STATUS } from '/@/api/page/model/videoModel'

export default defineComponent({
  components: { 
    Time,
    [Card.name]: Card,
    [CardMeta.name]: CardMeta,
    [List.name]: List,
    [ListItem.name]: ListItem,
    [Avatar.name]: Avatar,
    [Form.name]: Form,
    [FormItem.name]: FormItem,
    [Input.TextArea.name]: Input.TextArea,
    [Button.name]: Button,
    [Tag.name]: Tag
  },
  props: {
    item: [Object]
  },
  setup() {
    return {
      SYNC_STATUS
    }
  }
   
})
</script>