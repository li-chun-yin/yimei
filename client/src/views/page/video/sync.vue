<template>
  <PageWrapper class="c-video-sync">
    <a-card title="基本信息" :bordered="false">
      <video ref="cVideo" crossorigin="anonymous" :src="video.url" controls="controls" @canplay="listenVideoCanPlay" class="c-video" />
      <a-form :model="syncDescFormConfig" :label-col="{span: 4}" class="c-batch-form">

        <a-form-item label="视频标题" class="c-batch-video-text" required help="用于所有自媒体平台">
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">{{syncDescFormConfig.text}}</template>
          <a-textarea v-else v-model:value="syncDescFormConfig.text" @change="syncDescFormConfig.doPostVideoSyncBasic" showCount :autoSize="{minRows: 3, maxRows: 3}" class="c-area" />
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSetText" class="c-button">批量设置</a-button>
        </a-form-item>

        <a-form-item label="视频简介" class="c-batch-video-abstract" help="用于西瓜视频">
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">{{syncDescFormConfig.abstract}}</template>
          <a-textarea v-else v-model:value="syncDescFormConfig.abstract" @change="syncDescFormConfig.doPostVideoSyncBasic" showCount :autoSize="{minRows: 3, maxRows: 3}" class="c-area" />
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSetAbstract" class="c-button">批量设置</a-button>
        </a-form-item>

        <a-form-item label="视频封面" class="c-batch-video-cover-image" help="用于抖音,快手 [点击图片可以替换封面]">
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">
            <div class="c-image-box">
              <a-image v-if="syncDescFormConfig.cover_image_url" :src="syncDescFormConfig.cover_image_url" class="c-image"></a-image>
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

        <a-form-item label="抖音位置" class="c-batch-video-poi" help="用于抖音">
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
            class="c-select"
          />
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSetPoi" class="c-button">批量设置</a-button>
        </a-form-item>

        <a-form-item label="是否原创" class="c-batch-video-origin" help="用于西瓜视频">
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">{{syncDescFormConfig.claim_origin == true ? '是' : '否' }}</template>
          <a-switch v-else checked-children="是" un-checked-children="否" v-model:checked="syncDescFormConfig.claim_origin" @change="syncDescFormConfig.doPostVideoSyncBasic"></a-switch>
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSetClaimOrigin" class="c-button">批量设置</a-button>
        </a-form-item>

        <a-form-item label="赞赏入口" class="c-batch-video-praise" help="用于西瓜视频">
          <template v-if="syncDescFormConfig.sync_status == VIDEO_INDEX_STATUS.DONE">{{syncDescFormConfig.praise == true ? '开' : '关' }}</template>
          <a-switch v-else checked-children="开" un-checked-children="关" v-model:checked="syncDescFormConfig.praise" @change="syncDescFormConfig.doPostVideoSyncBasic"></a-switch>
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSetPraise" class="c-button">批量设置</a-button>
        </a-form-item>

        <a-form-item :colon="false" label=" ">
          <a-button v-if="syncDescFormConfig.sync_status != VIDEO_INDEX_STATUS.DONE" @click="syncDescFormConfig.onBatchSync" type="primary" shape="round" size="large" block>一键发布</a-button>
        </a-form-item>
      </a-form>
    </a-card>

    <a-card title="自媒体账号" :bordered="false" class="!mt-5 c-sync-ids">
      <a-list :dataSource="syncIds">
        <template #renderItem="{ item, index }">

          <SyncDouyinItem v-if="item.type=='douyin'" v-model:item="syncIds[index]" />

          <SyncToutiaoItem v-if="item.type=='toutiao'" v-model:item="syncIds[index]" />
          
          <SyncXiguaItem v-if="item.type=='xigua'" v-model:item="syncIds[index]" />

          <SyncKuaishouItem v-if="item.type=='kuaishou'" v-model:item="syncIds[index]" />

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
import { postUploadDataSync } from '/@/api/page/upload'
import{ SYNC_STATUS, VIDEO_INDEX_STATUS } from '/@/api/page/model/videoModel'
import { useGlobSetting } from '/@/hooks/setting';
import SyncDouyinData from './data/SyncDouyinData';
import SyncToutiaoData from './data/SyncToutiaoData';
import SyncXiguaData from './data/SyncXiguaData';
import SyncKuaishouData from './data/SyncKuaishouData';
import SyncDouyinItem from './components/SyncDouyinItem.vue';
import SyncToutiaoItem from './components/SyncToutiaoItem.vue';
import SyncXiguaItem from './components/SyncXiguaItem.vue';
import SyncKuaishouItem from './components/SyncKuaishouItem.vue';

export default defineComponent({
  name: 'VideoSyncPage',
  components: { 
    SyncDouyinItem,
    SyncToutiaoItem,
    SyncXiguaItem,
    SyncKuaishouItem,
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
      keyword.value = value
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
        cover_image_from_video: undefined,
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
          this.listenVideoCanPlay()
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
    await SyncKuaishouData(this)
    console.log(this.SyncIdPushedCount)
  },
  methods: {
    listenVideoCanPlay(){
      if(this.syncDescFormConfig.cover_image_from_video) {
        this.syncDescFormConfig.cover_image_url = this.syncDescFormConfig.cover_image_from_video
        return
      }
      let video = this.$refs.cVideo
      let canvas = document.createElement('canvas')
      canvas.width = video.videoWidth
      canvas.height = video.videoHeight
      let ctx = canvas.getContext('2d')
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height)
      this.syncDescFormConfig.cover_image_from_video = canvas.toDataURL('image/png')
      if(this.syncDescFormConfig.cover_image_url) {
        return
      }
      this.syncDescFormConfig.cover_image_url = this.syncDescFormConfig.cover_image_from_video
    },
    async getCoverImageUploadId(){
      if(this.syncDescFormConfig.cover_image_upload_id) {
        return this.syncDescFormConfig.cover_image_upload_id
      }
      await postUploadDataSync({
        data: this.syncDescFormConfig.cover_image_from_video
      }).then((res) => {
        console.log(res)
        this.syncDescFormConfig.cover_image_upload_id = res.id
        this.syncDescFormConfig.doPostVideoSyncBasic()
      })
      return this.syncDescFormConfig.cover_image_upload_id
    }
  }, 
})
</script>

<style lang="less">
.c-video-sync {
  .c-video {
    width: calc(720px * 0.375);
    height: calc(1280px * 0.375);
    background: black;
    border: 1px black solid;
    border-radius: 15px;
    float: left;
  }

  .c-batch-form {
    float: left;
    width: calc(100% - 720px * 0.375);

    .c-batch-video-cover-image {
      .c-image-box {
        width: calc(720px * 0.25 * 0.75);
        height: calc(1280px * 0.25 * 0.75);
        border: 1px #f0f0f0 dashed;
        display: contents;

        .c-image {
          width: 100%;
          max-width: calc(720px * 0.25);
          height: 100%;
        }
      }

      .c-cover-image-uploader {
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

    .c-batch-video-text {
      .c-area {
        width: calc(100% - 90px);
        float: left;
      }
      
      .c-button {
        height: 100%;
        position: absolute;
      }
    }

    .c-batch-video-abstract {
      .c-area {
        width: calc(100% - 90px);
        float: left;
      }
      
      .c-button {
        height: 100%;
        position: absolute;
      }
    }

    .c-batch-video-poi {
      .c-select {
        width: calc(100% - 90px);
        float: left;
      }
    }

    .c-batch-video-origin {
      .c-button {
        margin-left: 1em;
      }
    }

    .c-batch-video-praise {
      .c-button {
        margin-left: 1em;
      }
    }
  }

  .c-sync-ids {
    .c-list-item {
      float: left;
      margin-right: 15px;
      padding-top: 0;

      .c-card {
        width:330px;
        height: 560px;

        .c-sync-form {
          margin-top: 15px;

          .c-sync-video-cover-image {
            .ant-form-item-control-input-content{
              text-align: center;

              .c-image-box {
                width: calc(720px * 0.25 * 0.75);
                max-height: calc(1280px * 0.25 * 0.75);
                border: 1px #f0f0f0 dashed;
                margin: auto;

                .c-image {
                  width: 100%;
                  height: 100%;
                }
              }

              .c-cover-image-uploader {
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

    .c-list-item:last{
      margin-right: 0;
    }

  }
}
</style>