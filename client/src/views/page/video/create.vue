<template>
  <PageWrapper>
    <a-card title="上传视频" :bordered="false">
      <a-upload-dragger
        name="file"
        :action="getVideoCreateUrl"
        accept="video/*"
        @change="handleChange"
      >
        <p class="ant-upload-drag-icon">
          <span class="iconify" data-icon="ant-design:cloud-upload-outlined" data-width="200" data-height="200" style="margin:auto"></span>
        </p>
        <p class="ant-upload-text">单击或拖动文件到此区域上传</p>
        <p class="ant-upload-hint">
          支持常用视频格式，推荐使用 mp4 、webm。
        </p>
        <p class="ant-upload-hint">
          视频文件大小不超过4GB，时长在15分钟以内。
        </p>
        <p class="ant-upload-hint">
          为了更好的观看体验，推荐上传16:9，分辨率为720p（1280x720）及以上的竖版视频。
        </p>
        <p class="ant-upload-hint">
          带品牌logo或品牌水印的视频，会命中抖音的审核逻辑，有比较大的概率导致分享视频推荐降权处理/分享视频下架处理/分享账号被封禁处理。
        </p>
      </a-upload-dragger>
    </a-card>
  </PageWrapper>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { Card, Upload, message } from 'ant-design-vue'
import { PageWrapper } from '/@/components/Page'
import { router } from '/@/router';
import { getVideoCreateUrl } from '/@/api/page/video';


export default defineComponent({
  name: 'VideoCreatePage',
  components: { 
    PageWrapper,
    [Card.name]: Card,
    [Upload.UploadDragger.name]: Upload.UploadDragger,
  },
  setup() {
    const handleChange = (info: {file: { status:string, name: string }, fileList: []}) => {
      console.log(info.file.status, info)
      const status = info.file.status;
      if (status !== 'uploading') {
        console.log(info.file, info.fileList);
      }
      
      if (status === 'done') {
        message.success(`${info.file.name} 文件上传成功.`);
        router.push('/video/sync/' + info.file.response.data.id);
      } else if (status === 'error') {
        message.error(`${info.file.name} 文件上传失败.`);
      }
    };
    return { 
      handleChange,
      getVideoCreateUrl
    }
  }
})
</script>