import { SYNC_STATUS, SYNC_TYPE } from "/@/api/page/model/videoModel";
import { getToutiaoIdLists } from "/@/api/page/toutiao";
import { postVideoSync } from "/@/api/page/video";

export default async (that) => {
    await getToutiaoIdLists().then((res) => {
        for(let i in res.items) {
            let key = SYNC_TYPE.TOUTIAO + '_' + res.items[i].open_id
            let syncIdPutIndex = parseInt(i, 10) + parseInt(that.SyncIdPushedCount, 10);
            console.log(syncIdPutIndex, that.syncIds);
            that.syncIds.push({
                type: 'toutiao',
                id: res.items[i],
                loading: false,
                text: that.syncs[key] ? that.syncs[key].sync_request.text : '',
                sync_status: that.syncs[key] ? that.syncs[key].status : -1,
                setText: () => {
                    if(that.syncIds[syncIdPutIndex].sync_status != -1){
                    return
                    }
                    that.syncIds[syncIdPutIndex].text = that.syncDescFormConfig.text
                },
                onSync: async () => {
                    console.log('syncing', syncIdPutIndex, that.syncIds[syncIdPutIndex])
                    that.syncIds[syncIdPutIndex].loading = true;
                    try {
                    console.log(that.syncIds[syncIdPutIndex], that.video)
                    await postVideoSync({
                        unikey: that.syncIds[syncIdPutIndex].id.open_id,
                        upload_id: that.video.id,
                        type: SYNC_TYPE.TOUTIAO,
                        sync_request: {
                            text: that.syncIds[syncIdPutIndex].text,
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
