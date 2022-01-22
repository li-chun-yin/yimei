import type { AppRouteModule } from '/@/router/types';

import { LAYOUT } from '/@/router/constant';
import { t } from '/@/hooks/web/useI18n';

const dashboard: AppRouteModule = {
  path: '/video',
  name: 'Video',
  component: LAYOUT,
  redirect: '/video/index',
  meta: {
    icon: 'ion:videocam',
    title: t('routes.video.manager'),
    orderNo: 100002,
  },
  children: [
    {
      path: 'create',
      name: 'VideoCreatePage',
      component: () => import('/@/views/page/video/create.vue'),
      meta: {
        title: t('routes.video.create'),
      },
    },
    {
      path: 'sync/:id',
      name: 'VideoSyncPage',
      component: () => import('/@/views/page/video/sync.vue'),
      meta: {
        title: t('routes.video.sync'),
        hideMenu: true,
      },
    },
    {
      path: 'index',
      name: 'VideoIndexPage',
      component: () => import('/@/views/page/video/index.vue'),
      meta: {
        title: t('routes.video.list'),
      },
    },
  ],
};

export default dashboard;
