<template>          
  <a-list-item class="c-list-item">
    <a-card class="c-card">
      <a-card-meta :title="item.id.name">
        <template #avatar>
          <a-avatar :src="item.id.head" />
        </template>
        <template #description>
          <a-tag color="orange">快手</a-tag>
          <Time :value="item.id.update_time" />
        </template>
      </a-card-meta>
      
      <a-form :model="item" :label-col="{span: 7}" class="c-sync-form">

        <a-form-item label="视频封面" class="c-sync-video-cover-image">
          <template v-if="item.sync_status != -1">
            <div class="c-image-box">
              <a-image v-if="item.cover_image_url" :src="item.cover_image_url" class="c-image" />
            </div>
          </template>
          <a-upload
            v-else
            name="file"
            accept="image/*"
            list-type="picture-card"
            class="c-cover-image-uploader"
            :show-upload-list="false"
            :action="UPLOAD_URL"
            @change="item.onChangeCustomCoverImage"
          >
            <img v-if="item.cover_image_url" :src="item.cover_image_url" alt="avatar" />
            <div v-else>
              <loading-outlined v-if="item.loading"></loading-outlined>
              <plus-outlined v-else></plus-outlined>
              <div class="ant-upload-text">上传封面</div>
            </div>
          </a-upload>
          <a-button v-if="item.sync_status == -1" @click="item.onRemoveCoverImage">重新设置</a-button>
        </a-form-item>

        <a-form-item label="视频标题" required>
          <template v-if="item.sync_status != -1">{{item.caption}}</template>
          <a-textarea v-else v-model:value="item.caption" showCount :autoSize="{minRows: 3, maxRows: 3}" />
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
  Image,
  Upload
} from 'ant-design-vue';
import { PlusOutlined, LoadingOutlined } from '@ant-design/icons-vue';
import { Time } from '/@/components/Time';
import{ SYNC_STATUS } from '/@/api/page/model/videoModel'
import { useGlobSetting } from '/@/hooks/setting';

export default defineComponent({
  components: { 
    Time,
    PlusOutlined,
    LoadingOutlined,
    [Upload.name]: Upload,
    [Card.name]: Card,
    [CardMeta.name]: CardMeta,
    [List.name]: List,
    [ListItem.name]: ListItem,
    [Avatar.name]: Avatar,
    [Form.name]: Form,
    [FormItem.name]: FormItem,
    [Input.TextArea.name]: Input.TextArea,
    [Image.name]: Image,
    [Button.name]: Button,
    [Tag.name]: Tag
  },
  props: {
    item: [Object]
  },
  setup() {
    return {
      SYNC_STATUS,
      UPLOAD_URL: useGlobSetting().uploadUrl,
    }
  }
})
</script>