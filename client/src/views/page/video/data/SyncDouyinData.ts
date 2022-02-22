import { Modal } from "ant-design-vue";
import { getDouyinIdLists } from "/@/api/page/douyin";
import { SYNC_STATUS, SYNC_TYPE } from "/@/api/page/model/videoModel";
import { postVideoSync } from "/@/api/page/video";

export default async (that) => {
    await getDouyinIdLists().then((res) => {
        for(let i in res.items) {
            let key = SYNC_TYPE.DOUYIN + '_' + res.items[i].open_id
            let syncIdPutIndex = parseInt(i, 10) + parseInt(that.SyncIdPushedCount, 10);
            that.syncIds.push({
                type: 'douyin',
                id: res.items[i],
                cover_image_url: that.syncs[key] ? that.syncs[key].sync_request.cover_image_url : '',
                cover_image_upload_id: that.syncs[key] ? that.syncs[key].sync_request.cover_image_upload_id : '',
                loading: false,
                text: that.syncs[key] ? that.syncs[key].sync_request.text : '',
                poi_id: that.syncs[key] ? that.syncs[key].sync_request.poi_id : undefined,
                poi_name: that.syncs[key] ? that.syncs[key].sync_request.poi_name : '',
                sync_status: that.syncs[key] ? that.syncs[key].status : -1,
                setText: () => {
                    if(that.syncIds[syncIdPutIndex].sync_status != -1){
                        return
                    }
                    that.syncIds[syncIdPutIndex].text = that.syncDescFormConfig.text
                },
                setCoverImage: () => {
                    if(that.syncIds[syncIdPutIndex].sync_status != -1){
                        return
                    }
                    that.syncIds[syncIdPutIndex].cover_image_upload_id = that.syncDescFormConfig.cover_image_upload_id
                    that.syncIds[syncIdPutIndex].cover_image_url = that.syncDescFormConfig.cover_image_url
                },
                setPoi: () => {
                    if(that.syncIds[syncIdPutIndex].sync_status != -1){
                        return
                    }
                    that.syncIds[syncIdPutIndex].poi_id = that.syncDescFormConfig.poi_id
                    that.syncIds[syncIdPutIndex].poi_name = that.syncDescFormConfig.poi_name
                },
                onChangeCustomCoverImage: (info) => {
                    if(info.file.status == 'done') {
                        that.syncIds[syncIdPutIndex].cover_image_url = info.file.response.data.url
                        that.syncIds[syncIdPutIndex].cover_image_upload_id = info.file.response.data.id
                    }
                    console.log(that.syncIds[syncIdPutIndex], info)
                },
                onRemoveCoverImage: () => {
                    that.syncIds[syncIdPutIndex].cover_image_url = ''
                    that.syncIds[syncIdPutIndex].cover_image_upload_id = undefined
                },
                onChangePoi: ( value:string, option ) => {
                    console.log(option)
                    that.syncIds[syncIdPutIndex].poi_id = value
                    that.syncIds[syncIdPutIndex].poi_name = option.label
                },
                onSync: async () => {
                    console.log('syncing', syncIdPutIndex, that.syncIds[syncIdPutIndex])
                    if(that.syncIds[syncIdPutIndex].text === '') {
                        Modal.error({title: '抖音视频标题不能为空'})
                        return
                    }
                    that.syncIds[syncIdPutIndex].loading = true;
                    try {
                        console.log(that.syncIds[syncIdPutIndex], that.video)


                        if(     !that.syncIds[syncIdPutIndex].cover_image_upload_id
                            &&  that.syncIds[syncIdPutIndex].cover_image_url
                        ){
                            that.syncIds[syncIdPutIndex].cover_image_upload_id = await that.getCoverImageUploadId()
                        }
          
                        await postVideoSync({
                            unikey: that.syncIds[syncIdPutIndex].id.open_id,
                            upload_id: that.video.id,
                            type: SYNC_TYPE.DOUYIN,
                            sync_request: {
                                text: that.syncIds[syncIdPutIndex].text,
                                poi_id: that.syncIds[syncIdPutIndex].poi_id,
                                poi_name: that.syncIds[syncIdPutIndex].poi_name,
                                cover_image_upload_id: that.syncIds[syncIdPutIndex].cover_image_upload_id
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
