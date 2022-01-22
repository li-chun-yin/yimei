import type { AppRouteModule } from '/@/router/types';

import { LAYOUT } from '/@/router/constant';
import { t } from '/@/hooks/web/useI18n';

const dashboard: AppRouteModule = {
  path: '/id',
  name: 'Id',
  component: LAYOUT,
  redirect: '/id/index',
  meta: {
    hideChildrenInMenu: true,
    icon: 'ion:id-card',
    title: t('routes.id.account_manager'),
    orderNo: 100001
  },
  children: [
    {
      path: 'index',
      name: 'IdPage',
      component: () => import('../../../views/page/id/index.vue'),
      meta: {
        title: t('routes.id.account_manager'),
        icon: 'ion:id-card',
        hideMenu: true
      },
    },
  ],
};

export default dashboard;
