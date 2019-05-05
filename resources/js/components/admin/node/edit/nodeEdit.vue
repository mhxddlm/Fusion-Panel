<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="4" md="3" sm="12" class="sticky">
				<mu-card raised>
					<mu-card-header :title="nodeInfo.basic.name" :sub-title="nodeInfo.basic.address">
						<mu-avatar slot="avatar">
							<img :src="'https://www.countryflags.io/' + nodeInfo.basic.area + '/flat/64.png'" height="100">
						</mu-avatar>
					</mu-card-header>
					<mu-card-text>
						{{$t('admin.node.edit.text-node-created-at', {date: nodeInfo.basic.created_at})}}<br>
						{{$t('admin.node.edit.text-node-updated-at', {date: nodeInfo.basic.updated_at})}}
					</mu-card-text>
				</mu-card>
				<mu-card raised>
					<mu-card-title :title="$t('admin.node.edit.card-title-server-remote-actions')" />
					<mu-card-text>
						<mu-row gutter>
							<mu-col span="4" sm="4">
								<mu-flex justify-content="center" align-items="center">
									<mu-button icon color="red"><mu-icon value="power_settings_new"></mu-icon></mu-button>
								</mu-flex>
							</mu-col>
							<mu-col span="4" sm="4">
								<mu-flex justify-content="center" align-items="center">
									<mu-button icon color="success"><mu-icon value="refresh"></mu-icon></mu-button>
								</mu-flex>
							</mu-col>
							<mu-col span="4" sm="4">
								<mu-flex justify-content="center" align-items="center">
									<mu-button icon color="primary"><mu-icon value="code"></mu-icon></mu-button>
								</mu-flex>
							</mu-col>
						</mu-row>
					</mu-card-text>
				</mu-card>
				<fusion-node-status-small :nodeID="pageAttributes.nodeID" :centreControlled="false"/>
			</mu-col>
			<mu-col span="12" lg="8" md="9" sm="12">
				<fusion-node-basic-info :nodeID="pageAttributes.nodeID" @globalNodeBasicInfo="globalNodeBasicInfo" @moduleFinishLoading="moduleFinishLoading" />
				<fusion-node-connect-info :nodeID="pageAttributes.nodeID"  @moduleFinishLoading="moduleFinishLoading" />
			</mu-col>
		</mu-row>
		<ell3y-loader :loaded="moduleFinishedLoadingNum" :total="3" type="circle"  />
	</mu-container>
</template>

<script>
	const nodeBasicInfo = () => import('./nodeEditBasic.vue');
	const nodeModuleInfo = () => import('./nodeEditConnect.vue');
	const nodeStatusSmall = () => import('../../modules/nodeStatusSmall.vue');
	const moduleLoading = () => import('../../../public/moduleLoading.vue');

	export default {
		data: ()=>({
			pageAttributes: {
				nodeID: undefined,
				moduleLoading:{
					nodeBasicInfo: false,
					nodeModuleInfo: false,
					nodeTrafficInfo: true,
				},
			},
			nodeInfo:{
				basic: {
					area: "CA"
				}
			},
		}),
		watch: {

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
		beforeMount(){
			this.pageAttributes.nodeID = _.toSafeInteger(this.$route.params.id);
		},
		mounted() {
		},
		methods:{
			moduleFinishLoading(which){
				Object.keys(this.pageAttributes.moduleLoading).forEach(e => {
					if(e === which) this.pageAttributes.moduleLoading[e] = true;
				});
			},
			globalNodeBasicInfo(a){
				this.nodeInfo.basic = a;
			},
		},
		components:{
			'fusion-node-basic-info': nodeBasicInfo,
			'fusion-node-connect-info': nodeModuleInfo,
			'fusion-node-status-small': nodeStatusSmall,
			'ell3y-loader': moduleLoading,
		}
	}
</script>

<style scoped>
	.mu-tab{
		min-width: 0 !important;
	}
	.fusion-small-button{
		min-width: 0 !important;
	}
	@media (min-width: 800px){
		.sticky{
			position: sticky;
			position: -webkit-sticky;
			top: 64px;
		}
	}
</style>