<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="3" md="3" sm="12">
				<mu-card raised>
					<mu-card-text>
						<mu-flex justify-content="center" align-items="center">
							<mu-button full-width color="primary" @click="deployDialog('add')">{{$t('admin.marketing.invite-code.button-text-add-code')}}</mu-button>
						</mu-flex>
					</mu-card-text>
				</mu-card>
				<mu-card raised>
					<mu-card-title :title="$t('admin.marketing.invite-code.card-title-invite-code-config')" />
					<mu-card-text>
						<mu-form :model="inviteCode">
							<mu-form-item prop="input" :label="$t('admin.marketing.invite-code.text-label-default-code-amount')">
								<mu-text-field v-model="inviteCode.defaultCodeAmount"></mu-text-field>
							</mu-form-item>
							<mu-form-item prop="switch" :label="$t('admin.marketing.invite-code.switch-label-force-register')">
								<mu-switch color="primary" v-model="inviteCode.forceInviteRegister" :label="inviteCode.forceInviteRegister ? $t('admin.marketing.invite-code.switch-label-force-enable') : $t('admin.marketing.invite-code.switch-label-force-disable')"></mu-switch>
							</mu-form-item>
						</mu-form>
					</mu-card-text>
				</mu-card>
				<mu-card raised>
					<mu-card-title :title="$t('admin.marketing.invite-code.card-title-rebate-config')" />
					<mu-card-text>
						<mu-form :model="rebateInfo">
							<mu-form-item prop="input" :label="$t('admin.marketing.invite-code.text-label-default-first-rebate-percent')">
								<mu-text-field v-model="rebateInfo.firstRebatePercent"></mu-text-field>
							</mu-form-item>
							<mu-form-item prop="input" :label="$t('admin.marketing.invite-code.text-label-default-other-rebate-percent')">
								<mu-text-field v-model="rebateInfo.normalRebatePercent"></mu-text-field>
							</mu-form-item>
							<mu-form-item prop="switch" :label="$t('admin.marketing.invite-code.switch-label-rebate-status')">
								<mu-switch color="primary" v-model="rebateInfo.rebateStatus" :label="rebateInfo.rebateStatus ? $t('admin.marketing.invite-code.switch-label-rebate-enable') : $t('admin.marketing.invite-code.switch-label-rebate-disable')"></mu-switch>
							</mu-form-item>
						</mu-form>
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="9" md="9" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('admin.marketing.invite-code.card-title-invite-code-list')" />
					<mu-card-text>
						<mu-data-table selectable select-all :selects.sync="selects" checkbox :columns="pageAttributes.columns" :data="inviteCodeList" :loading="pageAttributes.tableLoading" :no-data-text="$t('admin.marketing.invite-code.data-table-no-data')">
							<template slot-scope="scope">
								<td class="is-center">{{scope.row.code}}</td>
								<td class="is-center">{{scope.row.money_include}}</td>
								<td class="is-center">{{scope.row.used_count + '/' + scope.row.limit}}</td>
								<td class="is-center">{{scope.row.genName}}</td>
								<td class="is-center">
									<mu-button tag="button" icon color="primary" @click="deployDialog('edit',scope.row.id)">
										<mu-icon value="edit"></mu-icon>
									</mu-button>
									<mu-button icon color="red" @click="deleteInviteCode([scope.row.id])">
										<mu-icon value="delete_forever"></mu-icon>
									</mu-button>
								</td>
							</template>
						</mu-data-table>
					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
		<Ell3y-loader :loaded="moduleFinishedLoadingNum" :total="3" type="circle"/>
		<fusion-invite-code-dialog ref="codeAction" :admin="true" :mode="pageAttributes.mode" @close="updateInviteCodeList"/>
	</mu-container>
</template>

<script>
	const inviteCodeActionsDialog = () => import('../modules/inviteCodeActionsDialog.vue');
	const moduleLoading = () => import('../../public/moduleLoading.vue');
	export default {
		data: ()=>({
			inviteCode:{
				defaultCodeAmount: undefined,
				forceInviteRegister: undefined,
			},
			rebateInfo:{
				firstRebatePercent: undefined,
				normalRebatePercent: undefined,
				rebateStatus: undefined,
			},
			inviteCodeList: [],
			selects: [],
			pageAttributes:{
				tableLoading: true,
				columns:[
					{title: i18n.t('admin.marketing.invite-code.data-table-column-title-code'), name: "code", align: 'center'},
					{title: i18n.t('admin.marketing.invite-code.data-table-column-title-money'), name: "money_include", align: 'center'},
					{title: i18n.t('admin.marketing.invite-code.data-table-column-title-usage'), name: "used_count", align: 'center'},
					{title: i18n.t('admin.marketing.invite-code.data-table-column-title-genby'), name: "genName", align: 'center'},
					{title: i18n.t('admin.marketing.invite-code.data-table-column-title-actions'), align: 'center', width: 164},
				],
				moduleLoading:{
					inviteCodeConfig: false,
					rebateConfig: false,
					inviteCodeList: false,
				},
				mode: 'add'
			}
		}),
		created(){
			this.updateInviteCodeList = _.debounce(this.getInviteCodeList, 500);
		},
		mounted(){
			this.retrieveInviteCodeConfig();
			this.retrieveRebateConfig();
			this.updateInviteCodeList();
		},
		computed:{
			moduleFinishedLoadingNum () {
				let a = 0;
				Object.values(this.pageAttributes.moduleLoading).forEach(e => {
					if(e === true) {a++;}
				});
				return a;
			}
		},
		methods: {
			retrieveInviteCodeConfig(){
				axios.get('/api/v1/admin/getInviteCodeConfig')
					.then(resp =>{
						this.inviteCode = resp.data;
						this.pageAttributes.moduleLoading.inviteCodeConfig = true;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			retrieveRebateConfig(){
				axios.get('/api/v1/admin/getRebateConfig')
					.then(resp =>{
						this.rebateInfo = resp.data;
						this.pageAttributes.moduleLoading.rebateConfig = true;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			getInviteCodeList(){
				this.pageAttributes.tableLoading = true;
				axios.get('/api/v1/admin/getInviteCodeList')
					.then(resp =>{
						this.inviteCodeList = resp.data;
						this.pageAttributes.tableLoading = false;
						this.pageAttributes.moduleLoading.inviteCodeList = true;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			deleteInviteCode(id){

			},
			deployDialog(m, id){
				let temp = {gen_by: undefined, enable: true};
				if(m === 'edit')
					temp = this.inviteCodeList.find(e => e.id === id);
				this.$nextTick(()=>{
					this.$refs.codeAction.openDialog(m, temp);
				});
			}
		},
		components:{
			'Ell3y-loader': moduleLoading,
			'fusion-invite-code-dialog': inviteCodeActionsDialog,
		}
	}
</script>

<style scoped>

</style>