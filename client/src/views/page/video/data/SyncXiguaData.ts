import { SYNC_STATUS, SYNC_TYPE } from "/@/api/page/model/videoModel";
import { postVideoSync } from "/@/api/page/video";
import { getXiguaIdLists } from "/@/api/page/xigua";

export default async (that) => {
    await getXiguaIdLists().then((res) => {
        for(let i in res.items) {
          let key = SYNC_TYPE.XIGUA + '_' + res.items[i].open_id
          let syncIdPutIndex = parseInt(i, 10) + parseInt(that.SyncIdPushedCount, 10);
          console.log(syncIdPutIndex, that.syncIds);
          that.syncIds.push({
            type: 'xigua',
            id: res.items[i],
            loading: false,
            text: that.syncs[key] ? that.syncs[key].sync_request.text : '',
            abstract: that.syncs[key] ? that.syncs[key].sync_request.abstract : '',
            claim_origin: that.syncs[key] ? that.syncs[key].sync_request.claim_origin : false,
            praise: that.syncs[key] ? that.syncs[key].sync_request.praise : false,
            sync_status: that.syncs[key] ? that.syncs[key].status : -1,
            setText: () => {
              if(that.syncIds[syncIdPutIndex].sync_status != -1){
                return
              }
              that.syncIds[syncIdPutIndex].text = that.syncDescFormConfig.text
            },
            setAbstract: () => {
              if(that.syncIds[syncIdPutIndex].sync_status != -1){
                return
              }
              that.syncIds[syncIdPutIndex].abstract = that.syncDescFormConfig.abstract
            },
            setClaimOrigin: () => {
              if(that.syncIds[syncIdPutIndex].sync_status != -1){
                return
              }
              that.syncIds[syncIdPutIndex].claim_origin = that.syncDescFormConfig.claim_origin
            },
            setPraise: () => {
              if(that.syncIds[syncIdPutIndex].sync_status != -1){
                return
              }
              that.syncIds[syncIdPutIndex].praise = that.syncDescFormConfig.praise
            },
            onSync: async () => {
              console.log('syncing', syncIdPutIndex, that.syncIds[syncIdPutIndex])
              that.syncIds[syncIdPutIndex].loading = true;
              try {
                console.log(that.syncIds[syncIdPutIndex], that.video)
                await postVideoSync({
                  unikey: that.syncIds[syncIdPutIndex].id.open_id,
                  upload_id: that.video.id,
                  type: SYNC_TYPE.XIGUA,
                  sync_request: {
                    text: that.syncIds[syncIdPutIndex].text,
                    abstract: that.syncIds[syncIdPutIndex].abstract,
                    claim_origin: that.syncIds[syncIdPutIndex].claim_origin,
                    praise: that.syncIds[syncIdPutIndex].praise,
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
