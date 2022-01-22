import type { AppRouteModule } from '/@/router/types';

import { LAYOUT } from '/@/router/constant';
import { t } from '/@/hooks/web/useI18n';

const dashboard: AppRouteModule = {
  path: '/setting',
  name: 'Setting',
  component: LAYOUT,
  redirect: '/setting/index',
  meta: {
    hideChildrenInMenu: true,
    icon: 'ion:settings',
    title: t('routes.setting.set'),
    orderNo: 100000
  },
  children: [
    {
      path: 'index',
      name: 'SettingPage',
      component: () => import('../../../views/page/setting/index.vue'),
      meta: {
        title: t('routes.setting.set'),
        icon: 'ion:settings',
        hideMenu: true
      },
    },
  ],
};

export default dashboard;
