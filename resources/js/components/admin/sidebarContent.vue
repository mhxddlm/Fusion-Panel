<template>
	<mu-container>
		<mu-appbar :z-depth="0" color="transparent" class="mu-app-drawer-header" style="height: 256px;">
			<mu-flex justify-content="center" style="margin-bottom: 30px;">
				<img src="/svg/logo.svg" height="100px" width="100px">
			</mu-flex>
			<router-link :to="{name:'userIndex'}" tag='div' class='mu-appbar-title-text' v-html="APP_NAME_HTML"></router-link>
			<div class='mu-app-version'>Fusion v{{APP_FRONT_END_VERSION}} | v{{APP_BACK_END_VERSION}}</div>
		</mu-appbar>
		<mu-divider></mu-divider>
		<mu-list toggle-nested>
			<mu-list-item button  :to="{name: 'adminIndex'}">
				<mu-list-item-action>
					<mu-icon value="dashboard"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.admin.dashboard')}}</mu-list-item-title>
			</mu-list-item>
			<mu-list-item button :ripple="true" nested :open="open === 'user'" @toggle-nested="open = arguments[0] ? 'user' : ''">
				<mu-list-item-action>
					<mu-icon value="group"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.admin.user')}}</mu-list-item-title>
				<mu-list-item-action>
					<mu-icon class="toggle-icon" size="24" value="keyboard_arrow_down"></mu-icon>
				</mu-list-item-action>
				<mu-list-item button :to="{name: 'adminUserList'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.user-list')}}</mu-list-item-title>
				</mu-list-item>
			</mu-list-item>
			<mu-list-item button :ripple="true" nested :open="open === 'group'" @toggle-nested="open = arguments[0] ? 'group' : ''">
				<mu-list-item-action>
					<mu-icon value="domain"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.admin.group')}}</mu-list-item-title>
				<mu-list-item-action>
					<mu-icon class="toggle-icon" size="24" value="keyboard_arrow_down"></mu-icon>
				</mu-list-item-action>
				<mu-list-item button :to="{name: 'adminGroupList'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.group-list')}}</mu-list-item-title>
				</mu-list-item>
			</mu-list-item>
			<mu-list-item button :ripple="true" nested :open="open === 'node'" @toggle-nested="open = arguments[0] ? 'node' : ''">
				<mu-list-item-action>
					<mu-icon value="dns"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.admin.node')}}</mu-list-item-title>
				<mu-list-item-action>
					<mu-icon class="toggle-icon" size="24" value="keyboard_arrow_down"></mu-icon>
				</mu-list-item-action>
				<mu-list-item button :to="{name: 'adminNodeList'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.node-list')}}</mu-list-item-title>
				</mu-list-item>
				<mu-list-item button :to="{name: 'adminNodeRelayList'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.node-relay')}}</mu-list-item-title>
				</mu-list-item>
			</mu-list-item>
			<mu-list-item button :ripple="true" nested :open="open === 'marketing'" @toggle-nested="open = arguments[0] ? 'marketing' : ''">
				<mu-list-item-action>
					<mu-icon value="shopping_cart"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.admin.marketing')}}</mu-list-item-title>
				<mu-list-item-action>
					<mu-icon class="toggle-icon" size="24" value="keyboard_arrow_down"></mu-icon>
				</mu-list-item-action>
				<mu-list-item button :to="{name: 'adminMarketingSummary'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.marketing-summary')}}</mu-list-item-title>
				</mu-list-item>
				<mu-list-item button :to="{name: 'adminMarketingInviteCode'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.invite-code')}}</mu-list-item-title>
				</mu-list-item>
				<mu-list-item button :to="{name: 'adminMarketingExchangeCode'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.exchange-code')}}</mu-list-item-title>
				</mu-list-item>
			</mu-list-item>
			<mu-list-item button :ripple="true" nested :open="open === 'support'" @toggle-nested="open = arguments[0] ? 'support' : ''">
				<mu-list-item-action>
					<mu-icon value="help"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.admin.support')}}</mu-list-item-title>
				<mu-list-item-action>
					<mu-icon class="toggle-icon" size="24" value="keyboard_arrow_down"></mu-icon>
				</mu-list-item-action>
				<mu-list-item button :to="{name: 'adminTicketList'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.ticket-list')}}</mu-list-item-title>
				</mu-list-item>
			</mu-list-item>
			<mu-list-item button :ripple="true" nested :open="open === 'bulletin'" @toggle-nested="open = arguments[0] ? 'bulletin' : ''">
				<mu-list-item-action>
					<mu-icon value="announcement"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.admin.bulletin')}}</mu-list-item-title>
				<mu-list-item-action>
					<mu-icon class="toggle-icon" size="24" value="keyboard_arrow_down"></mu-icon>
				</mu-list-item-action>
				<mu-list-item button :to="{name: 'adminBulletinList'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.bulletin-list')}}</mu-list-item-title>
				</mu-list-item>
			</mu-list-item>
			<mu-list-item button :ripple="true" nested :open="open === 'permission'" @toggle-nested="open = arguments[0] ? 'permission' : ''">
				<mu-list-item-action>
					<mu-icon value="security"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.admin.permission')}}</mu-list-item-title>
				<mu-list-item-action>
					<mu-icon class="toggle-icon" size="24" value="keyboard_arrow_down"></mu-icon>
				</mu-list-item-action>
				<mu-list-item button :to="{name: 'adminPermission'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.admin-list')}}</mu-list-item-title>
				</mu-list-item>
			</mu-list-item>
			<mu-list-item button :ripple="true" nested :open="open === 'system'" @toggle-nested="open = arguments[0] ? 'system' : ''">
				<mu-list-item-action>
					<mu-icon value="settings"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.admin.system')}}</mu-list-item-title>
				<mu-list-item-action>
					<mu-icon class="toggle-icon" size="24" value="keyboard_arrow_down"></mu-icon>
				</mu-list-item-action>
				<mu-list-item button :to="{name: 'adminSysConfigList'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.system-config-list')}}</mu-list-item-title>
				</mu-list-item>
				<mu-list-item button :to="{name: 'adminConnectivityConfigList'}" :ripple="true" slot="nested">
					<mu-list-item-title>{{$t('pages.admin.connectivity-config-list')}}</mu-list-item-title>
				</mu-list-item>
			</mu-list-item>
			<mu-divider></mu-divider>
			<mu-list-item button :to="{name: 'userIndex'}">
				<mu-list-item-action>
					<mu-icon value="person"></mu-icon>
				</mu-list-item-action>
				<mu-list-item-title>{{$t('pages.user.dashboard')}}</mu-list-item-title>
			</mu-list-item>
		</mu-list>
	</mu-container>
</template>

<script>
	export default {
		data: ()=> ({
			open: '',
		})
	}
</script>

<style lang="less" scoped>
	@import '~muse-ui/lib/styles/vars.less';
	.mu-item-action{
		min-width: 40px !important;
	}
	.mu-app-drawer-header {
		background-image: radial-gradient(farthest-corner at 0% 100%, #E040FB 0%, #512DA8 90%);
		.mu-appbar-title {
			line-height: 24px;
			display: flex;
			font-size: 24px;
			flex-direction: column;
			font-weight: 500;
		}
		.mu-appbar-title-text {
			line-height: 24px;
			cursor: pointer;
			font-family: 'Cinzel', serif;
		}
		.mu-appbar-title-text > small {
			font-size: 12px;
		}
		.mu-app-version {
			line-height: 1;
			font-size: 12px;
			margin-top: 4px;
			font-family: 'Cinzel', serif;
		}
	}
</style>