<template>
	<mu-container>
		<mu-row gutter>
		<mu-col span="12" lg="4" md="3" sm="12">
			<mu-card raised>
				<mu-card-header :title="groupInfo.basic.name" :sub-title="groupInfo.basic.email">
					<mu-avatar slot="avatar">
						<img :src="'https://www.gravatar.com/avatar/' + groupInfo.basic.emailHash">
					</mu-avatar>
				</mu-card-header>
				<mu-card-text>
					{{$t('admin.group.edit.text-group-created-at', {date: groupInfo.basic.created_at})}}<br>
					{{$t('admin.group.edit.text-group-updated-at', {date: groupInfo.basic.updated_at})}}
				</mu-card-text>
			</mu-card>
		</mu-col>
		<mu-col span="12" lg="8" md="9" sm="12">
			<fusion-group-basic-info :groupID="pageAttributes.groupID" @globalGroupBasicInfo="globalGroupBasicInfo" @moduleFinishLoading="moduleFinishLoading" />
			<fusion-group-node-info :groupID="pageAttributes.groupID" @moduleFinishLoading="moduleFinishLoading" />
			<fusion-group-user-info :groupID="pageAttributes.groupID" @moduleFinishLoading="moduleFinishLoading" />
		</mu-col>
		</mu-row>
		<ell3y-loader :loaded="moduleFinishedLoadingNum" :total="3" type="circle"  />
	</mu-container>
</template>

<script>
	const groupBasicInfo = () => import('./groupEditBasic.vue');
	const groupNodeInfo = () => import('./groupEditNode.vue');
	const groupUserInfo = () => import('./groupEditUser.vue');
	const moduleLoading = () => import('../../../public/moduleLoading.vue');
	export default {
		data: () =>({
			pageAttributes: {
				groupID: undefined,
				moduleLoading:{
					groupBasicInfo: false,
					groupUserInfo: false,
					groupNodeInfo: false
				},
			},
			groupInfo:{
				basic: {}
			},
		}),
		beforeMount(){
			this.pageAttributes.groupID = _.toSafeInteger(this.$route.params.id);
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
			globalGroupBasicInfo(a){
				this.groupInfo.basic = a;
			},
			moduleFinishLoading(which){
				Object.keys(this.pageAttributes.moduleLoading).forEach(e => {
					if(e === which) this.pageAttributes.moduleLoading[e] = true;
				});
			},
		},
		components: {
			'fusion-group-basic-info': groupBasicInfo,
			'fusion-group-node-info': groupNodeInfo,
			'fusion-group-user-info': groupUserInfo,
			'ell3y-loader': moduleLoading,
		}
	}
</script>

<style scoped>

</style>