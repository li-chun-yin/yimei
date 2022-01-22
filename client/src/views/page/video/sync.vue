<template>
  <PageWrapper class="video-sync">
    <a-card title="基本信息" :bordered="false">
      <video v-if="video.url" :src="video.url" controls="controls" class="video" />
      <a-form :model="syncDescFormConfig" :label-col="{span: 3}" :wrapper-col="{span: 10}" class="batch-form">
        <a-form-item label="视频封面" class="batch-video-cover-image">
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">
            <div class="image-box">
              <a-image v-if="syncDescFormConfig.cover_image_url" :src="syncDescFormConfig.cover_image_url" class="image"></a-image>
            </div>
          </template>
          <a-upload
            v-else
            name="file"
            accept="image/*"
            list-type="picture-card"
            class="cover-image-uploader"
            :show-upload-list="false"
            :action="UPLOAD_URL"
            @change="syncDescFormConfig.onChangeCustomCoverImage"
          >
            <img v-if="syncDescFormConfig.cover_image_url" :src="syncDescFormConfig.cover_image_url" alt="avatar" />
            <div v-else>
              <loading-outlined v-if="syncDescFormConfig.loading"></loading-outlined>
              <plus-outlined v-else></plus-outlined>
              <div class="ant-upload-text">上传封面</div>
            </div>
          </a-upload>
          <template v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE">
            <a-button @click="syncDescFormConfig.onBatchSetCoverImage">批量设置</a-button>
            <br/>
            <a-button @click="syncDescFormConfig.onBatchRemoveCoverImage">重新设置</a-button>
          </template>
        </a-form-item>

        <a-form-item label="视频标题" class="batch-video-text" required>
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">{{syncDescFormConfig.text}}</template>
          <a-textarea v-else v-model:value="syncDescFormConfig.text" @change="syncDescFormConfig.doPostVideoSyncBasic" showCount :autosize="{minRows: 3, maxRows: 3}" class="batch-video-text-area" />
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSetText" class="batch-video-text-button">批量设置</a-button>
        </a-form-item>

        <a-form-item label="抖音位置" class="batch-video-poi">
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">{{syncDescFormConfig.poi_name}}</template>
          <ApiSelect
            v-else
            :api="searchDouyiPois"
            :params="searchPoiParams"
            showSearch
            v-model:value="syncDescFormConfig.poi_id"
            :filterOption="false"
            resultField="items"
            labelField="poi_name"
            valueField="poi_id"
            @search="onSearchPoiList"
            @change="syncDescFormConfig.onChangePoi"
            class="batch-video-poi-select"
          />
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSetPoi" class="batch-video-poi-button">批量设置</a-button>
        </a-form-item>
        <a-form-item :colon="false" label=" ">
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSync" type="primary" shape="round" size="large" block>一键发布</a-button>
        </a-form-item>
      </a-form>
    </a-card>

    <a-card title="自媒体账号" :bordered="false" class="!mt-5 sync-ids">
      <a-list :dataSource="douyins">
        <template #renderItem="{ item }">
          <a-list-item class="list-item">
            <a-card class="card">
              <a-card-meta :title="item.id.nickname">
                <template #avatar>
                  <a-avatar :src="item.id.avatar" />
                </template>
                <template #description>
                  <Time :value="item.id.update_time" />
                </template>
              </a-card-meta>
              
              <a-form :model="item" :label-col="{span: 7}" class="sync-form">
                <a-form-item label="视频封面" class="sync-video-cover-image">
                  <template v-if="item.sync_status != -1">
                    <div class="image-box">
                      <a-image v-if="item.cover_image_url" :src="item.cover_image_url" class="image" />
                    </div>
                  </template>
                  <a-upload
                    v-else
                    name="file"
                    accept="image/*"
                    list-type="picture-card"
                    class="cover-image-uploader"
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
                  <template v-if="item.sync_status != -1">{{item.text}}</template>
                  <a-textarea v-else v-model:value="item.text" showCount :autosize="{minRows: 3, maxRows: 3}" />
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
      </a-list>
    </a-card>
  </PageWrapper>
</template>

<script lang="ts">
import { defineComponent, computed, ref, unref } from 'vue'
import { useDebounceFn } from '@vueuse/core';
import { Card, CardMeta, Tabs, List, ListItem, Avatar, Upload, Form, FormItem, Textarea, Button, Image } from 'ant-design-vue'
import { PlusOutlined, LoadingOutlined } from '@ant-design/icons-vue';
import { PageWrapper } from '/@/components/Page'
import { Time } from '/@/components/Time';
import { useRouter } from 'vue-router'
import { ApiSelect } from '/@/components/Form'
import { getDouyinIdLists, searchDouyiPois } from '/@/api/page/douyin'
import { getVideoInfo, postVideoSync, postVideoSyncBasic } from '/@/api/page/video'
import{ SYNC_TYPE, SYNC_STATUS, VIDEO_INDEX_STATUS } from '/@/api/page/model/videoModel'
import { useGlobSetting } from '/@/hooks/setting';

export default defineComponent({
  name: 'VideoSyncPage',
  components: { 
    PageWrapper,
    ApiSelect,
    Time,
    PlusOutlined,
    LoadingOutlined,
    [Card.name]: Card,
    [CardMeta.name]: CardMeta,
    [Tabs.name]: Tabs,
    [Tabs.TabPane.name]: Tabs.TabPane,
    [List.name]: List,
    [ListItem.name]: ListItem,
    [Avatar.name]: Avatar,
    [Upload.name]: Upload,
    [Form.name]: Form,
    [FormItem.name]: FormItem,
    [Textarea.name]: Textarea,
    [Button.name]: Button,
    [Image.name]: Image
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
      VIDEO_INDEX_STATUS,
      UPLOAD_URL: useGlobSetting().uploadUrl,
      searchDouyiPois,
      searchPoiParams,
      onSearchPoiList: useDebounceFn(onSearchPoiList, 300),
    }
  },
  data() {
    const { currentRoute } = useRouter()
    const params = unref( currentRoute ).params
    return {
      video: {
        url: '',
        id: params.id
      },
      syncs: {},
      douyins: [],
      syncDescFormConfig: {
        sync_status: -1,
        text: '',
        cover_image_url: '',
        cover_image_upload_id: undefined,
        poi_id: undefined,
        poi_name: '',
        loading: false,
        doPostVideoSyncBasic: () => {
          postVideoSyncBasic({
            upload_id: this.video.id,
            text: this.syncDescFormConfig.text,
            cover_image_upload_id: this.syncDescFormConfig.cover_image_upload_id,
            poi_id: this.syncDescFormConfig.poi_id,
            poi_name: this.syncDescFormConfig.poi_name
          })
        },
        onChangeCustomCoverImage: (info) => {
            if(info.file.status == 'done') {
              this.syncDescFormConfig.cover_image_url = info.file.response.data.url
              this.syncDescFormConfig.cover_image_upload_id = info.file.response.data.id
            }
            console.log(this.syncDescFormConfig, info)
            this.syncDescFormConfig.doPostVideoSyncBasic()
        },
        onBatchRemoveCoverImage: () => {
          this.syncDescFormConfig.cover_image_upload_id = undefined
          this.syncDescFormConfig.cover_image_url = ''
          this.syncDescFormConfig.doPostVideoSyncBasic()
        },
        onChangePoi: ( value:string, option ) => {
          console.log(option)
          this.syncDescFormConfig.poi_id = value
          this.syncDescFormConfig.poi_name = option.label
          this.syncDescFormConfig.doPostVideoSyncBasic()
        },
        onBatchSetCoverImage: () => {
          for(let i in this.douyins){
            if(this.douyins[i].sync_status != -1){
              continue
            }
            this.douyins[i].cover_image_upload_id = this.syncDescFormConfig.cover_image_upload_id
            this.douyins[i].cover_image_url = this.syncDescFormConfig.cover_image_url
          }
        },
        onBatchSetText: () => {
          for(let i in this.douyins){
            if(this.douyins[i].sync_status != -1){
              continue
            }
            this.douyins[i].text = this.syncDescFormConfig.text
          }
        },
        onBatchSetPoi: () => {
          for(let i in this.douyins){
            if(this.douyins[i].sync_status != -1){
              continue
            }
            this.douyins[i].poi_id = this.syncDescFormConfig.poi_id
            this.douyins[i].poi_name = this.syncDescFormConfig.poi_name
          }
        },
        onBatchSync: () => {
          for(let i in this.douyins){
            console.log(this.douyins[i])
            if(this.douyins[i].sync_status != -1){
              continue
            }
            this.douyins[i].onSync()
          }
        }
      }
    }
  },
  async created(){
    await getVideoInfo({id: <string>this.video.id}).then(res => {
      this.video.url  = res.url;
      this.syncs      = res.syncs;
      this.syncDescFormConfig.sync_status = res.sync_desc.status
      this.syncDescFormConfig.text = res.sync_desc.sync_data.text
      this.syncDescFormConfig.poi_id = res.sync_desc.sync_data.poi_id
      this.syncDescFormConfig.poi_name = res.sync_desc.sync_data.poi_name
      this.syncDescFormConfig.cover_image_upload_id = res.sync_desc.sync_data.cover_image_upload_id
      this.syncDescFormConfig.cover_image_url = res.sync_desc.sync_data.cover_image_url
      console.log(this.syncs);
    })

    getDouyinIdLists().then((res) => {
      for(let i in res.items) {
        let key = SYNC_TYPE.DOUYIN + '_' + res.items[i].open_id
        this.douyins.push({
          id: res.items[i],
          cover_image_url: this.syncs[key] ? this.syncs[key].sync_request.cover_image_url : '',
          cover_image_upload_id: this.syncs[key] ? this.syncs[key].sync_request.cover_image_upload_id : '',
          loading: false,
          text: this.syncs[key] ? this.syncs[key].sync_request.text : '',
          poi_id: this.syncs[key] ? this.syncs[key].sync_request.poi_id : undefined,
          poi_name: this.syncs[key] ? this.syncs[key].sync_request.poi_name : '',
          sync_status: this.syncs[key] ? this.syncs[key].status : -1,
          onChangeCustomCoverImage: (info) => {
            if(info.file.status == 'done') {
              this.douyins[i].cover_image_url = info.file.response.data.url
              this.douyins[i].cover_image_upload_id = info.file.response.data.id
            }
            console.log(this.douyins[i], info)
          },
          onRemoveCoverImage: () => {
              this.douyins[i].cover_image_url = ''
              this.douyins[i].cover_image_upload_id = undefined
          },
          onChangePoi: ( value:string, option ) => {
            console.log(option)
            this.douyins[i].poi_id = value
            this.douyins[i].poi_name = option.label
          },
          onSync: async (event) => {
            console.log('syncing')
            this.douyins[i].loading = true;
            try {
              console.log(this.douyins[i], this.video)
              await postVideoSync({
                unikey: this.douyins[i].id.open_id,
                upload_id: this.video.id,
                type: SYNC_TYPE.DOUYIN,
                sync_request: {
                  text: this.douyins[i].text,
                  poi_id: this.douyins[i].poi_id,
                  poi_name: this.douyins[i].poi_name,
                  cover_image_upload_id: this.douyins[i].cover_image_upload_id
                }
              })
              this.douyins[i].sync_status = SYNC_STATUS.ING
              this.douyins[i].loading = false;
            } catch (error) {
              this.douyins[i].loading = false;
              console.log(error)
            }
          }
        })
      }
    })
  }
})
</script>

<style lang="less">
.video-sync{
  .video {
    width: calc(720px * 0.375);
    height: calc(1280px * 0.375);
    background: black;
    border: 1px black solid;
    border-radius: 15px;
    float: left;
  }

  .batch-form{
    min-width: 920px;

    .batch-video-cover-image {
      .image-box {
        width: calc(720px * 0.25 * 0.75);
        height: calc(1280px * 0.25 * 0.75);
        border: 1px #f0f0f0 dashed;
        display: contents;

        .iamge {
          width: 100%;
          height: 100%;
        }
      }

      .cover-image-uploader {
        width: calc(720px * 0.25 * 0.75 + 16px);
        height: calc(1280px * 0.25 * 0.75 + 16px);
        float: left;

        .ant-upload.ant-upload-select.ant-upload-select-picture-card {
          width: 100%;
          height: 100%;
        }
      }
      .ant-btn {
        margin: 0 15px 10px;
      }
    }

    .batch-video-text {
      .batch-video-text-area {
        width: calc(100% - 90px);
        float: left;
      }
      
      .batch-video-text-button {
        height: 100%;
        position: absolute;
      }
    }

    .batch-video-poi {
      .batch-video-poi-select {
        width: calc(100% - 90px);
        float: left;
      }
    }
  }

  .sync-ids {
    .list-item {
      float: left;
      margin-right: 15px;
      padding-top: 0;

      .card {
        width:330px;
        height: 560px;

        .sync-form {
          margin-top: 15px;

          .sync-video-cover-image {
            .ant-form-item-control-input-content{
              text-align: center;

              .image-box {
                width: calc(720px * 0.25 * 0.75);
                max-height: calc(1280px * 0.25 * 0.75);
                border: 1px #f0f0f0 dashed;
                margin: auto;

                .image {
                  width: 100%;
                  height: 100%;
                }
              }

              .cover-image-uploader {
                width: calc(720px * 0.175 + 16px);
                height: calc(1280px * 0.175 + 16px);

                .ant-upload.ant-upload-select.ant-upload-select-picture-card {
                  width: 100%;
                  height: 100%;
                }
              }

              .ant-btn {
                position: absolute;
                left: -6em;
                top: 2em;
              }
            }
          }
        }
      }
    }

    .list-item:last{
      margin-right: 0;
    }

  }
}
</style>