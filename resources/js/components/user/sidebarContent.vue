<template>
	<mu-container class="fusion-user-sidebar">
		<mu-appbar :z-depth="0" color="transparent" class="mu-app-drawer-header" style="height: 64px;">
			<mu-flex justify-content="start">
				<img src="/svg/logo.svg" height="50px" width="50px" style="margin: 7px 7px 7px 0px;">
				<router-link :to="{name:'userIndex'}" tag='div' class="mu-appbar-title-text" v-html="APP_NAME_HTML"></router-link>
			</mu-flex>
		</mu-appbar>
		<div class="fusion-user-sidebar-avatar">
			<mu-flex justify-content="center" style="padding-top: 53.5px; margin-bottom: 5px;">
				<mu-avatar :size="80">
					<img :src="avatarUrl" onerror="this.src='/img/avatar_404.png'">
				</mu-avatar>
			</mu-flex>
			<mu-flex justify-content="center">
				<h3 style="margin-top: 0px; margin-bottom: 5px; color: white">{{$store.state.userInfo.name || "Loading..."}}</h3>
			</mu-flex>
			<mu-flex justify-content="center">
				<p style="margin-top: 0px; color: white">{{$store.state.userInfo.email || "Loading..."}}</p>
			</mu-flex>
			<mu-row gutter>
				<mu-col span="6" md="6" sm="6">
					<mu-flex justify-content="center">
						<p style="color: white;">Balance: ${{$store.state.userInfo.balance}}</p>
					</mu-flex>
				</mu-col>
				<mu-col span="6" md="6" sm="6">
					<mu-flex justify-content="center">
						<p style="color: white;">Status: {{$store.state.userInfo.status === 1 ? "Good" : "Nah"}}</p>
					</mu-flex>
				</mu-col>
			</mu-row>
		</div>

		<mu-bottom-nav color="primary" style="width: 100%;">
			<mu-bottom-nav-item :to="{name: 'userIndex'}" exact-active-class="mu-bottom-item-active mu-primary-text-color" exact
			                    value="dashboard" :title="$t('sidebar.dashboard')" icon="dashboard"></mu-bottom-nav-item>
			<mu-bottom-nav-item :to="{name: 'userNodeList'}" exact-active-class="mu-bottom-item-active mu-primary-text-color" exact
			                    value="node" :title="$t('sidebar.node')" icon="dns"></mu-bottom-nav-item>
			<mu-bottom-nav-item :to="{name: 'userFinancialPurchase'}" exact-active-class="mu-bottom-item-active mu-primary-text-color" exact
			                    value="shop" :title="$t('sidebar.shop')" icon="shopping_cart"></mu-bottom-nav-item>
			<mu-bottom-nav-item :to="{name: 'userSupportDash'}" exact-active-class="mu-bottom-item-active mu-primary-text-color"
			                    value="support" :title="$t('sidebar.support')" icon="contact_support"></mu-bottom-nav-item>
		</mu-bottom-nav>
		<mu-button flat small @click="showMore" style="width: 100%; color: rgba(0,0,0,.54);">{{$t('sidebar.more')}} <mu-icon right :value="showMoreList ? 'arrow_drop_up' : 'arrow_drop_down'"></mu-icon></mu-button>
		<transition name="slide">
		<mu-list v-show="showMoreList" toggle-nested>
			<mu-list-item button exact-active-class="is-selected" exact
			              :to="{name: $store.state.userInfo.promote_program ? 'userInvitePromote' : 'userInviteInvite'}">
				<mu-list-item-action>
					<mu-icon value="share"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>
					{{$store.state.userInfo.promote_program !== null ? $t('pages.user.invite-promote') : $t('pages.user.invite-invite')}}
				</mu-list-item-title>
			</mu-list-item>
			<mu-list-item button exact-active-class="is-selected" exact
			              :to="{name: 'userSettingsMe'}">
				<mu-list-item-action>
					<mu-icon value="account_circle"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.user.settings-me')}}</mu-list-item-title>
			</mu-list-item>
			<mu-list-item button exact-active-class="is-selected" exact
			              :to="{name: 'userSettingsConnect'}">
				<mu-list-item-action>
					<mu-icon value="settings"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.user.settings-connect')}}</mu-list-item-title>
			</mu-list-item>
			<mu-divider></mu-divider>
			<mu-list-item button exact-active-class="is-selected" exact
			              :to="{name: 'adminIndex'}" v-show="isAdmin || $store.state.userInfo.permission !== undefined">
				<mu-list-item-action>
					<mu-icon value="supervisor_account"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.admin.dashboard')}}</mu-list-item-title>
			</mu-list-item>
		</mu-list>
		</transition>
	</mu-container>
</template>

<script>
	import md5 from 'js-md5';
	export default {
		data:() => ({
			showMoreList: false,
			open: '',
			isAdmin: false,
		}),
		computed: {
			avatarUrl() {
				return this.$store.state.userInfo.email == null ?
					'/img/avatar_404.png' : 'https://www.gravatar.com/avatar/' + md5(this.$store.state.userInfo.email) + '?s=80&d=404';
			},
		},
		watch:{
			'$store.state.userInfo.permission'(oldV, newV){
				this.isAdmin = _.isEmpty(newV);
			}
		},
		methods:{
			showMore(){
				this.showMoreList = !this.showMoreList;
			}
		}
	}
</script>

<style lang="less" scoped>
	@import '~muse-ui/lib/styles/vars.less';
	.mu-app-drawer-header {
		.mu-appbar-title-text {
			line-height: 64px;
			cursor: pointer;
			font-family: 'Cinzel', serif !important;
			color: white !important;
		}
		.mu-appbar-title-text > small {
			font-size: 12px;
		}
	}
	.fusion-user-sidebar{
		height: 360px;
		z-index: -1;
		background-image: radial-gradient(farthest-corner at 0% 100%, #E040FB 0%, #512DA8 90%);
	}
	.fusion-user-sidebar-avatar{
		height: 300px;
	}
	.fusion-user-sidebar-avatar::after{
		margin-top: calc(53.5px - 1.875rem);
		content: '';
		width: 150%;
		left: -25%;
		height: 4.375rem;
		background-color: white;
		border-radius: 100%;
		position: absolute;
	}
	.slide-enter-active {
		transition: height .3s ease;
	}
	.slide-leave-active {
		transition: height .3s ease;
	}
	.slide-enter, .slide-leave-to{
		height: 0px;
	}
	.slide-enter-to, .slide-leave{
		height: 100%;
	}
</style>