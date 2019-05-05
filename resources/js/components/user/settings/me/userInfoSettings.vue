<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="4" sm="12">
				<mu-paper :z-depth="5" class="card sticky" style="border-radius: 10px; margin-top: 70px !important;">
					<mu-flex justify-content="center">
						<mu-paper circle :z-depth="5" style="margin-top: -50px; height: 100px">
							<mu-avatar :size="100">
								<img :src="avatarUrl" onerror="this.src='/img/avatar_404.png'">
							</mu-avatar>
						</mu-paper>
					</mu-flex>
					<mu-flex justify-content="center">
						<h2>{{userInfo.basic.name}}</h2>
					</mu-flex>
					<mu-flex justify-content="center">
						<h3>{{userInfo.basic.email}}</h3>
					</mu-flex>
					<mu-flex justify-content="center">
						<h4>{{$t('user.settings.me.text-user-created-at', {date: userInfo.basic.created_at})}}</h4>
					</mu-flex>
					<mu-divider></mu-divider>
					<mu-card-text>
						<mu-row gutter>
							<mu-col span="6">
								<mu-flex justify-content="center">
									<mu-icon :value="userInfo.basic.status !== 1 ? 'error' : 'check_circle'"
									         :color="userInfo.basic.status !== 1 ? 'red' : 'success'" />
								</mu-flex>
								<mu-flex justify-content="center" style="margin-top: 10px">
									{{$t('user.settings.me.text-user-account-status')}}
								</mu-flex>
							</mu-col>
							<mu-col span="6">
								<mu-flex justify-content="center">
									<mu-icon :value="userInfo.basic.email_verified_at !== null ? 'check_circle' : 'error'"
									         :color="userInfo.basic.email_verified_at !== null ? 'success' : 'red'" />
								</mu-flex>
								<mu-flex justify-content="center" style="margin-top: 10px">
									{{$t('user.settings.me.text-user-email-status')}}
								</mu-flex>
							</mu-col>
						</mu-row>
					</mu-card-text>
				</mu-paper>
			</mu-col>
			<mu-col span="12" lg="8" sm="12">
				<fusion-user-basic-info @globalUserBasicInfo="globalUserBasicInfo" @moduleFinishLoading="moduleFinishLoading" />
				<fusion-user-panel-info />
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	const userBasicInfo = () => import('./modules/userEditBasic.vue');
	const userPanelInfo = () => import('./modules/userEditPanel.vue');
	export default {
		data: ()=>({
			pageAttributes: {
				userID: undefined,
				moduleLoading:{
					userBasicInfo: false,
					userPanelPreferences: true,
					userEmailPreferences: true,
				},
			},
			userInfo:{
				basic: {}
			}
		}),
		computed:{
			avatarUrl() {
				return this.userInfo.basic.email == null ?
					'/img/avatar_404.png' : 'https://www.gravatar.com/avatar/' + this.userInfo.basic.emailHash + '?s=100&d=404';
			}
		},
		mounted() {

		},
		methods: {
			globalUserBasicInfo(a){
				this.userInfo.basic = a;
			},
			moduleFinishLoading(which){
				Object.keys(this.pageAttributes.moduleLoading).forEach(e => {
					if(e === which) this.pageAttributes.moduleLoading[e] = true;
				});
			},
		},
		components: {
			'fusion-user-basic-info': userBasicInfo,
			'fusion-user-panel-info': userPanelInfo
		}
	}
</script>

<style scoped>
	@media (min-width: 800px){
		.sticky{
			position: sticky;
			position: -webkit-sticky;
			top: 64px;
		}
	}
</style>