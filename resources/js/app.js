require('./bootstrap');
window.Vue = require('vue');

import VueRouter from 'vue-router';
import Vuex from 'vuex';
import Vue from 'vue';

import 'typeface-roboto';

// MuseUI Imports
import MuseUI from 'muse-ui';
import './theme.js';
import 'muse-ui/dist/muse-ui.css';
import 'muse-ui/lib/styles/base.less';
import 'muse-ui/lib/styles/vars.less';




// MuseUI Loading Module Imports
import 'muse-ui-loading/dist/muse-ui-loading.css'; // load css
import Loading from 'muse-ui-loading';

// MuseUI ProgressBar Module Imports
import 'muse-ui-progress/dist/muse-ui-progress.css';
import Progress from 'muse-ui-progress';

// MuseUI Message Module Imports
import 'muse-ui-message/dist/muse-ui-message.css';
import Message from 'muse-ui-message';

// MuseUI Toast
import Toast from 'muse-ui-toast';

// Vue i18n Imports
import i18n from './i18n';

// VueX Store Imports
import store from './store';

//Vue Laue Chart Imports
import { Laue } from 'laue';

//Lodash Imports
import _ from 'lodash';
Object.defineProperty(Vue.prototype, '$_', { value: _ });

import fusion from './functions.js';


// MuseUI Configs
Vue.config.devtools = true;

Message.config({
	okLabel: i18n.t('terms.ok'),
	cancelLabel: i18n.t('terms.cancel'),
});
Toast.config({
	position: 'bottom-end',
	time: 2000,
	closeIcon: 'close',
	close: false,
	successIcon: 'check_circle',
	infoIcon: 'info',
	warningIcon: 'priority_high',
	errorIcon: 'warning'
});

// Vue Activation and Register
window.Vue.use(VueRouter);
window.i18n = i18n;
Vue.use(MuseUI);
Vue.use(Vuex);
Vue.use(Loading);
Vue.use(Progress);
Vue.use(Message);
Vue.use(Toast);
Vue.use(Laue);
Vue.use(fusion);


// Vue component Imports
const publicLayOut = () => import('./components/public/layout.vue');
const pageContent = () => import('./components/public/pageContent.vue');
const publicIndex = () => import('./components/public/index.vue');

const authLogin = () => import('./components/auth/login.vue');
const authRegister = () => import('./components/auth/register.vue');
const authAPI = () =>import('./components/auth/apiAuth.vue');

const userSidebar = () => import('./components/user/sidebarContent.vue');
const userIndex = () => import('./components/user/index/index.vue');

const userNode = () => import('./components/user/node/node.vue');
const userNodeList = () => import('./components/user/node/nodeList.vue');
const userNodeDetail = () => import('./components/user/node/nodeDetail.vue');
const userNodeRelay = () => import('./components/user/node/nodeRelay/nodeRelay.vue');
const userNodeRelayList = () => import('./components/user/node/nodeRelay/nodeRelayList.vue');
const userNodeRelayNew = () => import('./components/user/node/nodeRelay/nodeRelayNew.vue');
const userNodeRelayEdit = () => import('./components/user/node/nodeRelay/nodeRelayEdit.vue');

const userFinancial = () => import('./components/user/financial/financial.vue');
const userFinancialCharge = () => import('./components/user/financial/charge.vue');
const userFinancialPurchase = () => import('./components/user/financial/purchase.vue');

const userSupport = () => import('./components/user/support/support.vue');
const userSupportDash = () => import('./components/user/support/supportDash.vue');
const userTicketList = () => import('./components/user/support/ticket/ticketList.vue');
const userTicketDetail = () => import('./components/user/support/ticket/ticketDetail.vue');

const userInvite = () => import('./components/user/invite/invite.vue');
const userInviteInvite = () => import('./components/user/invite/inviteMode.vue');
const userInvitePromote = () => import('./components/user/invite/promoteMode.vue');

const userSettings = () => import('./components/user/settings/settings.vue');
const userSettingsMe = () => import('./components/user/settings/me/userInfoSettings.vue');
const userSettingsConnect = () => import('./components/user/settings/connectivity/userConnectivitySettings.vue');


const adminSidebar = () => import('./components/admin/sidebarContent.vue');
const adminIndex = () => import('./components/admin/index/index.vue');

const adminUser = () => import('./components/admin/user/user.vue');
const adminUserList = () => import('./components/admin/user/userList.vue');
const adminUserEdit = () => import('./components/admin/user/edit/userEdit.vue');

const adminMarketing = () => import('./components/admin/marketing/marketing.vue');
const adminMarketingSummary = () => import('./components/admin/marketing/summary.vue');
const adminMarketingInviteCode = () => import('./components/admin/marketing/inviteCode.vue');
const adminMarketingExchangeCode = () => import('./components/admin/marketing/exchangeCode.vue');

const adminNode = () => import('./components/admin/node/node.vue');
const adminNodeList = () => import('./components/admin/node/nodeList.vue');
const adminNodeEdit = () => import('./components/admin/node/edit/nodeEdit.vue');
const adminNodeRelayList = () => import('./components/admin/node/nodeRelayList.vue');

const adminGroup = () => import('./components/admin/group/group.vue');
const adminGroupList = () => import('./components/admin/group/groupList.vue');
const adminGroupEdit = () => import('./components/admin/group/edit/groupEdit.vue');

const adminSysConfig = () => import('./components/admin/sysConfig/sysConfig.vue');
const adminSysConfigList = () => import('./components/admin/sysConfig/sysConfigList.vue');
const adminConnectivityConfigList = () => import('./components/admin/sysConfig/defaultConnectivityList.vue');

const adminBulletin = () => import('./components/admin/bulletin/bulletin.vue');
const adminBulletinList = () => import('./components/admin/bulletin/bulletinList.vue');
const adminBulletinEdit = () => import('./components/admin/bulletin/bulletinEdit.vue');

const adminTicket = () => import('./components/admin/support/support.vue');
const adminTicketList = () => import('./components/admin/support/ticket/ticketList.vue');
const adminTicketDetail = () => import('./components/admin/support/ticket/ticketDetail.vue');

const adminPermission = () => import('./components/admin/permission/permission.vue');

const error403 = () => import('./components/errors/403.vue');
const error404 = () => import('./components/errors/404.vue');

const routes = [
	{
		path: '/',
		component: publicLayOut,
		children:[
			{
				path: '',
				components:{
					pageContent: publicIndex,
				},
				name: 'publicIndex',
				meta: {name: 'home'}
			},
			{
				path: 'auth',
				components:{
					pageContent: pageContent,
				},
				children: [
					{
						path: 'register',
						component: authRegister,
						name :'authRegister', meta: {name: 'register'}
					},
					{
						path: 'login',
						component:authLogin,
						name :'authLogin', meta: {name: 'login'}
					},
					{
						path: 'apiAuth',
						component:authAPI,
						name :'authAPI',
						meta: {name: 'api-auth'}
					},
					{
						path: '403',
						component: error403,
						name: 'error403',
						meta: {name: 'error.403'}
					},
				]
			},
			{
				path: '/user',
				components:{
					sidebarContent: userSidebar,
					pageContent: pageContent,
				},
				children: [
					{
						path: '',
						component:userIndex,
						name :'userIndex',
						meta: {name: 'user.dashboard'}
					},
					{
						path: 'node',
						component:userNode,
						children: [
							{
								path: '',
								component: userNodeList,
								name: 'userNodeList', meta: {name: 'user.node-list'}
							},
							{
								path: 'detail/:id',
								component: userNodeDetail,
								name: 'userNodeDetail', meta: {name: 'user.node-detail'}
							},
							{
								path: 'nodeRelay',
								component:userNodeRelay,
								children: [
									{
										path: '',
										component: userNodeRelayList,
										name: 'userNodeRelayList', meta: {name: 'user.node-relay-list'}
									},
									{
										path: 'new',
										component: userNodeRelayNew,
										name: 'userNodeRelayNew', meta: {name: 'user.node-relay-new'}
									},
									{
										path: 'edit/:id',
										component: userNodeRelayEdit,
										name: 'userNodeRelayEdit', meta: {name: 'user.node-relay-edit'}
									}
								],
							},
						],
					},
					{
						path: 'financial',
						component:userFinancial,
						children: [
							{
								path: 'charge',
								component: userFinancialCharge,
								name: 'userFinancialCharge', meta: {name: 'user.charge'}
							},
							{
								path: 'purchase',
								component: userFinancialPurchase,
								name: 'userFinancialPurchase', meta: {name: 'user.shop'}
							}
						],
					},
					{
						path: 'support',
						component:userSupport,
						children: [
							{
								path: '',
								component: userSupportDash,
								name: 'userSupportDash', meta: {name: 'user.support'}
							},
							{
								path: 'ticketList',
								component: userTicketList,
								name: 'userTicketList', meta: {name: 'user.ticket-list'}
							},
							{
								path: 'ticket/:id',
								component: userTicketDetail,
								name: 'userTicketDetail', meta: {name: 'user.ticket-detail'}
							}
						],
					},
					{
						path: 'settings',
						component:userSettings,
						children: [
							{
								path: 'me',
								component: userSettingsMe,
								name: 'userSettingsMe', meta: {name: 'user.settings-me'}
							},
							{
								path: 'connectivity',
								component: userSettingsConnect,
								name: 'userSettingsConnect', meta: {name: 'user.settings-connect'}
							},
						],
					},
					{
						path: 'invite',
						component:userInvite,
						children: [
							{
								path: 'basic',
								component: userInviteInvite,
								name: 'userInviteInvite', meta: {name: 'user.invite-invite'}
							},
							{
								path: 'promote',
								component: userInvitePromote,
								name: 'userInvitePromote', meta: {name: 'user.invite-promote'}
							},
						],
					},
				]
			},
			{
				path: '/admin',
				components: {
					sidebarContent: adminSidebar,
					pageContent: pageContent,
				},
				children: [
					{
						path: '',
						component: adminIndex,
						name: 'adminIndex',
						meta: {
							name: 'admin.dashboard',
							permission: 'VIEW.AdminIndex'
						}
					},
					{
						path: 'permission',
						component: adminPermission,
						name: 'adminPermission',
						meta: {
							name: 'admin.permission',
							permission: 'VIEW.AdminControl'
						}
					},
					{
						path: 'user',
						component:adminUser,
						children: [
							{
								path: '',
								component: adminUserList,
								name: 'adminUserList',
								meta: {
									name: 'admin.user-list',
									permission: 'VIEW.UserControl'
								}
							},
							{
								path: 'edit/:id',
								component: adminUserEdit,
								name: 'adminUserEdit',
								meta: {
									name: 'admin.user-edit',
									permission: 'VIEW.UserControl'
								}
							}
						],
					},
					{
						path: 'node',
						component:adminNode,
						children: [
							{
								path: '',
								component: adminNodeList,
								name: 'adminNodeList',
								meta: {
									name: 'admin.node-list',
									permission: 'VIEW.NodeControl'
								}
							},
							{
								path: 'edit/:id',
								component: adminNodeEdit,
								name: 'adminNodeEdit',
								meta: {
									name: 'admin.node-edit',
									permission: 'VIEW.NodeControl'
								}
							},
							{
								path: 'relay',
								component: adminNodeRelayList,
								name: 'adminNodeRelayList',
								meta: {
									name: 'admin.node-relay',
									permission: 'VIEW.RelayControl'
								}
							},
						],
					},
					{
						path: 'marketing',
						component:adminMarketing,
						children: [
							{
								path: '',
								component: adminMarketingSummary,
								name: 'adminMarketingSummary',
								meta: {
									name: 'admin.marketing',
									permission: 'VIEW.MarketingControl'
								}
							},
							{
								path: 'inviteCode',
								component: adminMarketingInviteCode,
								name: 'adminMarketingInviteCode',
								meta: {
									name: 'admin.invite-code',
									permission: 'VIEW.MarketingControl'
								}
							},
							{
								path: 'exchangeCode',
								component: adminMarketingExchangeCode,
								name: 'adminMarketingExchangeCode',
								meta: {
									name: 'admin.exchange-code',
									permission: 'VIEW.MarketingControl'
								}
							}
						],
					},
					{
						path: 'group',
						component:adminGroup,
						children: [
							{
								path: '',
								component: adminGroupList,
								name: 'adminGroupList',
								meta: {
									name: 'admin.group-list',
									permission: 'VIEW.GroupControl'
								}
							},
							{
								path: 'edit/:id',
								component: adminGroupEdit,
								name: 'adminGroupEdit',
								meta: {
									name: 'admin.group-edit',
									permission: 'VIEW.GroupControl'
								}
							}
						],
					},
					{
						path: 'system',
						component:adminSysConfig,
						children: [
							{
								path: '',
								component: adminSysConfigList,
								name: 'adminSysConfigList',
								meta: {
									name: 'admin.system-config-list',
									permission: 'VIEW.SystemControl'
								}
							},
							{
								path: 'connectivityConfig',
								component: adminConnectivityConfigList,
								name: 'adminConnectivityConfigList',
								meta: {
									name: 'admin.connectivity-config-list',
									permission: 'VIEW.SystemControl'
								}
							}
						],
					},
					{
						path: 'bulletin',
						component:adminBulletin,
						children: [
							{
								path: '',
								component: adminBulletinList,
								name: 'adminBulletinList',
								meta: {
									name: 'admin.bulletin-list',
									permission: 'VIEW.BulletinControl'
								}
							},
							{
								path: 'edit/:id',
								component: adminBulletinList,
								name: 'adminBulletinEdit',
								meta: {
									name: 'admin.bulletin-edit',
									permission: 'VIEW.BulletinControl'
								}
							}
						],
					},
					{
						path: 'support',
						component:adminTicket,
						children: [
							{
								path: 'ticket',
								component: adminTicketList,
								name: 'adminTicketList',
								meta: {
									name: 'admin.ticket-list',
									permission: 'VIEW.SupportControl'
								}
							},
							{
								path: 'ticket/detail/:id',
								component: adminTicketDetail,
								name: 'adminTicketDetail',
								meta: {
									name: 'admin.ticket-detail',
									permission: 'VIEW.SupportControl'
								}
							}
						],
					},
				]
			},
			{
				path: '*',
				components:{
					pageContent: error404,
				},
				name: 'error404',
				meta: {name: 'error.404'}
			}
		]
	},
];

const router = new VueRouter({
	mode: 'history',
	routes: routes,
	//linkExactActiveClass: 'mu-bottom-item-active mu-primary-text-color',
});

router.beforeEach((to, from, next) => {
	window.scrollTo(0, 0);
	Progress.start();

	if(to.meta.permission === undefined)
		next();
	else {
		try{
			if(_.isEmpty(store.state.userInfo.permission.VIEW)) //error first occurs here
				router.replace({name: 'error403', params: { from: from.name}});
			else{
				if(!_.isEmpty(store.state.userInfo.permission.VIEW.find(e =>{
					return e.method + "." + e.name === to.meta.permission;
				})))
					next();
				else
					router.replace({name: 'error403', params: { from: from.name}});
			}
		} catch(e){
			router.replace({name: 'authAPI', params: { to: to.fullPath}});
		}
	}

});

window.axios.interceptors.response.use(resp => {
	// Do something with response data
	return resp;
}, error=> {
	if(error.response){
		Message.alert(i18n.t('error.pop.' + error.response.status + '-text'), i18n.t('error.pop.' + error.response.status));
	}
	return Promise.reject(error);
});

router.afterEach(() => {
	Progress.done();
});



export const app = new Vue({ i18n, store, router }).$mount('#app');
