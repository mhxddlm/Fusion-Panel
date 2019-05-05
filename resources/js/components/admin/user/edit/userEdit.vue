<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="4" md="3" sm="12" class="sticky">
				<mu-card raised>
					<mu-card-header :title="userInfo.basic.name" :sub-title="userInfo.basic.email">
						<mu-avatar slot="avatar">
							<img :src="'https://www.gravatar.com/avatar/' + userInfo.basic.emailHash">
						</mu-avatar>
					</mu-card-header>
					<mu-card-text>
						{{$t('admin.user.edit.text-user-created-at', {date: userInfo.basic.created_at})}}<br>
						{{$t('admin.user.edit.text-user-updated-at', {date: userInfo.basic.updated_at})}}
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="8" md="9" sm="12">
				<fusion-user-basic-info :userID="pageAttributes.userID" @globalUserBasicInfo="globalUserBasicInfo" @moduleFinishLoading="moduleFinishLoading" />
				<fusion-user-group-info :userID="pageAttributes.userID" @moduleFinishLoading="moduleFinishLoading" />
				<fusion-user-relay-info :userID="pageAttributes.userID" @moduleFinishLoading="moduleFinishLoading" />
			</mu-col>
		</mu-row>
		<ell3y-loader :loaded="moduleFinishedLoadingNum" :total="4" type="circle"  />
	</mu-container>
</template>

<script>
	const userBasicInfo = () => import('./userEditBasic.vue');
	const userGroupInfo = () => import('./userEditGroup.vue');
	const userRelayInfo = () => import('./userEditRelay.vue');
	const moduleLoading = () => import('../../../public/moduleLoading.vue');
	export default {
		data: ()=>({
			pageAttributes: {
				userID: undefined,
				moduleLoading:{
					userBasicInfo: false,
					userGroupInfo: false,
					userRelayInfo: false,
					userFinancialInfo: true
				},
			},
			userInfo:{
				basic: {}
			}
		}),
		beforeMount(){
			this.pageAttributes.userID = _.toSafeInteger(this.$route.params.id);
		},
		mounted() {
		},
		computed: {
			moduleFinishedLoadingNum () {
				let a = 0;
				Object.values(this.pageAttributes.moduleLoading).forEach(e => {
					if(e === true) {a++;}
				});
				return a;
			}
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
			updateUserFinancialInfo(){
				axios.get('/api/v1/admin/getUserFinancialInfo',{
					params:{
						id: this.$route.params.id,
					}})
					.then(resp => {
						this.userInfo.financial = resp.data;
						this.pageAttributes.moduleLoading.userFinancialInfo = true;
					})
					.catch(resp =>{

					})
			}
		},
		components: {
			'fusion-user-basic-info': userBasicInfo,
			'fusion-user-group-info': userGroupInfo,
			'fusion-user-relay-info': userRelayInfo,
			'ell3y-loader': moduleLoading,
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