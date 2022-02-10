<template>
  <PageWrapper class="video-sync">
    <a-card title="基本信息" :bordered="false">
      <video v-if="video.url" :src="video.url" controls="controls" class="video" />
      <a-form :model="syncDescFormConfig" :label-col="{span: 4}" class="batch-form">

        <a-form-item label="视频标题" class="batch-video-text" required help="用于所有自媒体平台">
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">{{syncDescFormConfig.text}}</template>
          <a-textarea v-else v-model:value="syncDescFormConfig.text" @change="syncDescFormConfig.doPostVideoSyncBasic" showCount :autoSize="{minRows: 3, maxRows: 3}" class="batch-video-text-area" />
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSetText" class="batch-video-text-button">批量设置</a-button>
        </a-form-item>

        <a-form-item label="视频简介" class="batch-video-abstract" help="用于西瓜视频">
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">{{syncDescFormConfig.abstract}}</template>
          <a-textarea v-else v-model:value="syncDescFormConfig.abstract" @change="syncDescFormConfig.doPostVideoSyncBasic" showCount :autoSize="{minRows: 3, maxRows: 3}" class="batch-video-abstract-area" />
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSetAbstract" class="batch-video-abstract-button">批量设置</a-button>
        </a-form-item>

        <a-form-item label="视频封面" class="batch-video-cover-image" help="用于抖音">
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

        <a-form-item label="抖音位置" class="batch-video-poi" help="用于抖音">
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

        <a-form-item label="是否原创" class="batch-video-origin" help="用于西瓜视频">
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">{{syncDescFormConfig.claim_origin == true ? '是' : '否' }}</template>
          <a-switch v-else checked-children="是" un-checked-children="否" v-model:checked="syncDescFormConfig.claim_origin" @change="syncDescFormConfig.doPostVideoSyncBasic"></a-switch>
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSetClaimOrigin" class="batch-video-origin-button">批量设置</a-button>
        </a-form-item>

        <a-form-item label="赞赏入口" class="batch-video-praise" help="用于西瓜视频">
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">{{syncDescFormConfig.praise == true ? '开' : '关' }}</template>
          <a-switch v-else checked-children="开" un-checked-children="关" v-model:checked="syncDescFormConfig.praise" @change="syncDescFormConfig.doPostVideoSyncBasic"></a-switch>
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSetPraise" class="batch-video-praise-button">批量设置</a-button>
        </a-form-item>

        <a-form-item :colon="false" label=" ">
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSync" type="primary" shape="round" size="large" block>一键发布</a-button>
        </a-form-item>
      </a-form>
    </a-card>

    <a-card title="自媒体账号" :bordered="false" class="!mt-5 sync-ids">
      <a-list :dataSource="syncIds">
        <template #renderItem="{ item, index }">

          <SyncDouyinItem v-if="item.type=='douyin'" v-model:item="syncIds[index]" />

          <SyncToutiaoItem v-if="item.type=='toutiao'" v-model:item="syncIds[index]" />
          
          <SyncXiguaItem v-if="item.type=='xigua'" v-model:item="syncIds[index]" />

        </template>
      </a-list>
    </a-card>
  </PageWrapper>
</template>

<script lang="ts">
import { defineComponent, computed, ref, unref} from 'vue'
import { useDebounceFn } from '@vueuse/core';
import { 
  Card, 
  CardMeta, 
  List, 
  ListItem, 
  Avatar, 
  Upload, 
  Form, 
  FormItem, 
  Input, 
  Button, 
  Image, 
  Tag, 
  Switch 
} from 'ant-design-vue'
import { PlusOutlined, LoadingOutlined } from '@ant-design/icons-vue';
import { PageWrapper } from '/@/components/Page'
import { Time } from '/@/components/Time';
import { useRouter } from 'vue-router'
import { ApiSelect } from '/@/components/Form'
import { searchDouyiPois } from '/@/api/page/douyin'
import { getVideoInfo, postVideoSyncBasic } from '/@/api/page/video'
import{ SYNC_STATUS, VIDEO_INDEX_STATUS } from '/@/api/page/model/videoModel'
import { useGlobSetting } from '/@/hooks/setting';
import SyncDouyinData from './data/SyncDouyinData';
import SyncToutiaoData from './data/SyncToutiaoData';
import SyncXiguaData from './data/SyncXiguaData';
import SyncDouyinItem from './components/SyncDouyinItem.vue';
import SyncToutiaoItem from './components/SyncToutiaoItem.vue';
import SyncXiguaItem from './components/SyncXiguaItem.vue';

export default defineComponent({
  name: 'VideoSyncPage',
  components: { 
    SyncDouyinItem,
    SyncToutiaoItem,
    SyncXiguaItem,
    PageWrapper,
    ApiSelect,
    Time,
    PlusOutlined,
    LoadingOutlined,
    [Card.name]: Card,
    [CardMeta.name]: CardMeta,
    [List.name]: List,
    [ListItem.name]: ListItem,
    [Avatar.name]: Avatar,
    [Upload.name]: Upload,
    [Form.name]: Form,
    [FormItem.name]: FormItem,
    [Input.TextArea.name]: Input.TextArea,
    [Switch.name]: Switch,
    [Button.name]: Button,
    [Image.name]: Image,
    [Tag.name]: Tag
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
      onSearchPoiList: useDebounceFn(onSearchPoiList, 300)
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
      syncIds: [],
      SyncIdPushedCount: 0,
      syncDescFormConfig: {
        sync_status: -1,
        text: '',
        abstract: '', //视频简介 西瓜
        claim_origin: false, //声明原创 西瓜
        praise: false, //赞赏入口 西瓜
        cover_image_url: '', // 抖音
        cover_image_upload_id: undefined, // 抖音
        poi_id: undefined,// 抖音
        poi_name: '', // 抖音
        loading: false,
        doPostVideoSyncBasic: () => {
          postVideoSyncBasic({
            upload_id: this.video.id,
            text: this.syncDescFormConfig.text,
            abstract: this.syncDescFormConfig.abstract,
            cover_image_upload_id: this.syncDescFormConfig.cover_image_upload_id,
            poi_id: this.syncDescFormConfig.poi_id,
            poi_name: this.syncDescFormConfig.poi_name,
            claim_origin: this.syncDescFormConfig.claim_origin,
            praise: this.syncDescFormConfig.praise,
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
          for(let i in this.syncIds){
            if(typeof this.syncIds[i].setCoverImage == 'function'){
              this.syncIds[i].setCoverImage()
            }
          }
        },
        onBatchSetText: () => {
          for(let i in this.syncIds){
            if(typeof this.syncIds[i].setText == 'function'){
              this.syncIds[i].setText()
            }
          }
        },
        onBatchSetAbstract: () => {
          for(let i in this.syncIds){
            if(typeof this.syncIds[i].setAbstract == 'function'){
              this.syncIds[i].setAbstract()
            }
          }
        },
        onBatchSetPoi: () => {
          for(let i in this.syncIds){
            if(typeof this.syncIds[i].setPoi == 'function'){
              this.syncIds[i].setPoi()
            }
          }
        },
        onBatchSetClaimOrigin: () => {
          for(let i in this.syncIds){
            if(typeof this.syncIds[i].setClaimOrigin == 'function'){
              this.syncIds[i].setClaimOrigin()
            }
          }
        },
        onBatchSetPraise: () => {
          for(let i in this.syncIds){
            if(typeof this.syncIds[i].setPraise == 'function'){
              this.syncIds[i].setPraise()
            }
          }
        },
        onBatchSync: () => {
          for(let i in this.syncIds){
            console.log(this.syncIds[i])
            if(this.syncIds[i].sync_status != -1){
              continue
            }
            this.syncIds[i].onSync()
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
      this.syncDescFormConfig.abstract = res.sync_desc.sync_data.abstract
      this.syncDescFormConfig.poi_id = res.sync_desc.sync_data.poi_id
      this.syncDescFormConfig.poi_name = res.sync_desc.sync_data.poi_name
      this.syncDescFormConfig.cover_image_upload_id = res.sync_desc.sync_data.cover_image_upload_id
      this.syncDescFormConfig.cover_image_url = res.sync_desc.sync_data.cover_image_url
      this.syncDescFormConfig.claim_origin = res.sync_desc.sync_data.claim_origin
      this.syncDescFormConfig.praise = res.sync_desc.sync_data.praise
      console.log(this.syncs);
    })

    await SyncDouyinData(this)
    console.log(this.SyncIdPushedCount)
    await SyncToutiaoData(this)
    console.log(this.SyncIdPushedCount)
    await SyncXiguaData(this)
    console.log(this.SyncIdPushedCount)
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
    float: left;
    width: calc(100% - 720px * 0.375);

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

    .batch-video-abstract {
      .batch-video-abstract-area {
        width: calc(100% - 90px);
        float: left;
      }
      
      .batch-video-abstract-button {
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

    .batch-video-origin {
      .batch-video-origin-button {
        margin-left: 1em;
      }
    }

    .batch-video-praise {
      .batch-video-praise-button {
        margin-left: 1em;
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