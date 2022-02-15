<template>
  <a-list-item class="c-list-item">
    <a-card class="c-card">
      <a-card-meta :title="item.id.nickname">
        <template #avatar>
          <a-avatar :src="item.id.avatar" />
        </template>
        <template #description>
          <a-tag color="#2db7f5">抖音</a-tag>
          <Time :value="item.id.update_time" />
        </template>
      </a-card-meta>
      
      <a-form :model="item" :label-col="{span: 7}" class="c-sync-form">

        <a-form-item label="视频标题" required>
          <template v-if="item.sync_status != -1">{{item.text}}</template>
          <a-textarea v-else v-model:value="item.text" showCount :autoSize="{minRows: 3, maxRows: 3}" />
        </a-form-item>

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

        <a-form-item label="位置">
          <template v-if="item.sync_status != -1">{{item.poi_name}}</template>
          <ApiSelect
            v-else
            :api="searchDouyiPois"
            :params="searchPoiParams"
            showSearch
            v-model:value="item.poi_id"
            :filterOption="false"
            resultField="items"
            labelField="poi_name"
            valueField="poi_id"
            @search="onSearchPoiList"
            @change="item.onChangePoi"
          />
        </a-form-item>

        <a-button v-if="item.sync_status == SYNC_STATUS.ING" disabled block shape="round">发布中...</a-button>
        <a-button v-else-if="item.sync_status == SYNC_STATUS.DONE" disabled block shape="round">已经发布</a-button>
        <a-button v-else @click="item.onSync" :loading="item.loading" block shape="round">立即发布</a-button>
      </a-form>
    </a-card>
  </a-list-item>
</template>

<script lang="ts">
import { defineComponent, computed, ref, unref} from 'vue'
import { useDebounceFn } from '@vueuse/core';
import { 
  Card, 
  CardMeta, 
  ListItem, 
  Avatar, 
  Upload, 
  Form, 
  FormItem, 
  Input, 
  Button, 
  Image, 
  Tag
} from 'ant-design-vue'
import { PlusOutlined, LoadingOutlined } from '@ant-design/icons-vue';
import { Time } from '/@/components/Time';
import { ApiSelect } from '/@/components/Form'
import { searchDouyiPois } from '/@/api/page/douyin'
import{ SYNC_STATUS } from '/@/api/page/model/videoModel'
import { useGlobSetting } from '/@/hooks/setting';

export default defineComponent({
  components: { 
    ApiSelect,
    Time,
    PlusOutlined,
    LoadingOutlined,
    [Card.name]: Card,
    [CardMeta.name]: CardMeta,
    [ListItem.name]: ListItem,
    [Avatar.name]: Avatar,
    [Upload.name]: Upload,
    [Form.name]: Form,
    [FormItem.name]: FormItem,
    [Input.TextArea.name]: Input.TextArea,
    [Button.name]: Button,
    [Image.name]: Image,
    [Tag.name]: Tag
  },
  props: {
    item: [Object]
  },
  setup() {
  	const keyword = ref<string>('附近');
    const searchPoiParams = computed<Recordable>(() => {
      return { 
      	city: '',
        keyword: unref(keyword),
        cursor: 0,
        count: 20,
      }
    });
    const onSearchPoiList = (value: string) => {
      keyword.value = value;
    }

    return {
      SYNC_STATUS,
      UPLOAD_URL: useGlobSetting().uploadUrl,
      searchDouyiPois,
      searchPoiParams,
      onSearchPoiList: useDebounceFn(onSearchPoiList, 300)
    }
  }
})
</script>