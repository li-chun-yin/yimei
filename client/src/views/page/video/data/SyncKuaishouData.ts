import { SYNC_STATUS, SYNC_TYPE } from "/@/api/page/model/videoModel";
import { postVideoSync } from "/@/api/page/video";
import { getKuaishouIdLists } from "/@/api/page/kuaishou";
import { Modal } from "ant-design-vue";

export default async (that) => {
    await getKuaishouIdLists().then((res) => {
        for(let i in res.items) {
          let key = SYNC_TYPE.KUAISHOU + '_' + res.items[i].open_id
          let syncIdPutIndex = parseInt(i, 10) + parseInt(that.SyncIdPushedCount, 10);
          console.log(syncIdPutIndex, that.syncIds, that.syncDescFormConfig.cover_image_from_video);
          that.syncIds.push({
            type: 'kuaishou',
            id: res.items[i],
            loading: false,
            caption: that.syncs[key] ? that.syncs[key].sync_request.caption : '',
            cover_image_url: that.syncs[key] ? that.syncs[key].sync_request.cover_image_url : that.syncDescFormConfig.cover_image_url,
            cover_image_upload_id: that.syncs[key] ? that.syncs[key].sync_request.cover_image_upload_id : that.syncDescFormConfig.cover_image_upload_id,
            sync_status: that.syncs[key] ? that.syncs[key].status : -1,
            setText: () => {
              if(that.syncIds[syncIdPutIndex].sync_status != -1){
                return
              }
              that.syncIds[syncIdPutIndex].caption = that.syncDescFormConfig.text
            },
            setCoverImage: () => {
              if(that.syncIds[syncIdPutIndex].sync_status != -1){
                  return
              }
              that.syncIds[syncIdPutIndex].cover_image_upload_id = that.syncDescFormConfig.cover_image_upload_id
              that.syncIds[syncIdPutIndex].cover_image_url = that.syncDescFormConfig.cover_image_url
            },
            onChangeCustomCoverImage: (info) => {
              if(info.file.status == 'done') {
                  that.syncIds[syncIdPutIndex].cover_image_url = info.file.response.data.url
                  that.syncIds[syncIdPutIndex].cover_image_upload_id = info.file.response.data.id
              }
              console.log(that.syncIds[syncIdPutIndex], info)
            },
            onRemoveCoverImage: () => {
                that.syncIds[syncIdPutIndex].cover_image_url = that.syncDescFormConfig.cover_image_from_video
                that.syncIds[syncIdPutIndex].cover_image_upload_id = that.syncDescFormConfig.cover_image_upload_id
            },
            onSync: async () => {
              console.log('syncing', syncIdPutIndex, that.syncIds[syncIdPutIndex])

              if(that.syncIds[syncIdPutIndex].caption === '') {
                Modal.error({title: '快手视频标题不能为空'})
                return
              }


              that.syncIds[syncIdPutIndex].loading = true;
              try {
                console.log(that.syncIds[syncIdPutIndex], that.video)
                
                if(!that.syncIds[syncIdPutIndex].cover_image_upload_id){
                  that.syncIds[syncIdPutIndex].cover_image_upload_id = await that.getCoverImageUploadId()
                }

                await postVideoSync({
                  unikey: that.syncIds[syncIdPutIndex].id.open_id,
                  upload_id: that.video.id,
                  type: SYNC_TYPE.KUAISHOU,
                  sync_request: {
                    caption: that.syncIds[syncIdPutIndex].caption,
                    cover_image_upload_id: that.syncIds[syncIdPutIndex].cover_image_upload_id,
                  }
                })
                that.syncIds[syncIdPutIndex].sync_status = SYNC_STATUS.ING
                that.syncIds[syncIdPutIndex].loading = false;
              } catch (error) {
                that.syncIds[syncIdPutIndex].loading = false;
                console.log(error)
              }
            }
          })
        }
        that.SyncIdPushedCount = that.SyncIdPushedCount + parseInt(res.items.length, 10)
    })
}
