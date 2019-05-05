<template>
	<mu-dialog :title="$t('admin.marketing.invite-code.dialog-title-'+ mode +'-code')" width="450" max-width="95%" :open.sync="openInviteCodeDialog" :esc-press-close="false" :overlay-close="false">
		<mu-form :model="newInviteCodeInfo">
			<mu-form-item prop="input" :label="$t('admin.marketing.invite-code.dialog-text-label-code')">
				<mu-text-field v-model="newInviteCodeInfo.code" action-icon="autorenew" :action-click="()=>{newInviteCodeInfo.code = ''}" :help-text="$t('admin.marketing.invite-code.dialog-text-field-empty-for-random')"></mu-text-field>
			</mu-form-item>
			<mu-form-item prop="input" :label="$t('admin.marketing.invite-code.dialog-text-label-money')">
				<mu-text-field v-model="newInviteCodeInfo.money_include" type="number"></mu-text-field>
			</mu-form-item>
			<mu-form-item prop="input" :label="$t('admin.marketing.invite-code.dialog-text-label-limit')">
				<mu-text-field v-model="newInviteCodeInfo.limit" type="number"></mu-text-field>
			</mu-form-item>
			<mu-row v-show="admin" gutter>
				<mu-col span="10" sm="10">
					<mu-select ref="userSearchDialog" :disabled="mode === 'edit'" :label="$t('admin.marketing.invite-code.dialog-text-label-genby')" filterable v-model="newInviteCodeInfo.gen_by" :no-data-text="userSearchText" full-width>
						<mu-option v-for="user, index in temp.users" :key="user.id" :label="user.name" :value="user.id"></mu-option>
					</mu-select>
				</mu-col>
				<mu-col span="2" sm="2">
					<mu-flex justify-content="center" align-items="center" style="height: 88px;">
						<mu-button icon @click="newInviteCodeInfo.gen_by = undefined" :disabled="mode === 'edit'">
							<mu-icon value="delete_sweep"></mu-icon>
						</mu-button>
					</mu-flex>
				</mu-col>
			</mu-row>
			<mu-form-item prop="switch" :label="$t('admin.marketing.invite-code.dialog-switch-label-code-status')">
				<mu-switch color="primary" v-model="newInviteCodeInfo.enable" :label="newInviteCodeInfo.enable ? $t('admin.marketing.invite-code.dialog-switch-label-code-enable') : $t('admin.marketing.invite-code.dialog-switch-label-code-disable')"></mu-switch>
			</mu-form-item>
		</mu-form>
		<p v-html="$t('admin.marketing.invite-code.dialog-text-field-genby-info')" ></p>
		<mu-button slot="actions" flat @click="closeDialog()">{{$t('admin.marketing.invite-code.dialog-button-close')}}</mu-button>
		<mu-button slot="actions" flat data-mu-loading-size="24" v-loading="dialogLoading" color="primary" @click="addInviteCodeInfo()">{{$t('admin.marketing.invite-code.dialog-button-' + mode)}}</mu-button>
	</mu-dialog>
</template>

<script>
	export default {
		props:{
			admin: Boolean
		},
		data: () => ({
			newInviteCodeInfo: {
				gen_by: undefined,
			},
			dialogLoading: false,
			openInviteCodeDialog: false,
			userSearchText: i18n.t('admin.marketing.invite-code.dialog-text-field-pls-input'),
			temp:{
				users: []
			},
			userSearchWatcher: undefined,
			mode: 'add',
		}),
		created(){
			this.updateUserSearchData = _.debounce(this.getUserSearch, 500);
		},
		mounted(){
		},
		methods: {
			getUserSearch(a){
				this.userSearchText = i18n.t('admin.marketing.invite-code.dialog-text-field-updating');
				axios.get('/api/v1/admin/getUserList',{
					params:{
						search: a
					}})
					.then(resp =>{
						this.userSearchText = i18n.t('admin.marketing.invite-code.dialog-text-field-updated');
						this.temp.users = _.unionBy(resp.data.data, this.temp.users, 'id');
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			getUsername(){ //in case we didn't get a username
				axios.get('/api/v1/admin/getUserBasicInfo',{
					params:{
						userID: this.newInviteCodeInfo.gen_by
					}})
					.then(resp =>{
						this.newInviteCodeInfo.genName = resp.data.name;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			addInviteCodeInfo(){
				this.dialogLoading = true;
				axios.post('/api/v1/admin/addInviteCodeInfo', {data: this.newInviteCodeInfo})
					.then(resp => {
						this.dialogLoading = false;
						this.closeDialog();
						this.$emit('close');
					})
					.catch(resp =>{

					})
			},
			openDialog(mode, t){
				this.mode = mode;
				if(_.isEmpty(t)) this.newInviteCodeInfo.gen_by = undefined;
				else this.newInviteCodeInfo = t;
				this.openInviteCodeDialog = true;
				this.setWatcher();
				if(this.mode === 'edit'){
					this.updateUserSearchData(this.newInviteCodeInfo.genName);
				}
			},
			closeDialog(){
				this.newInviteCodeInfo = {};
				this.openInviteCodeDialog = false;
				this.userSearchWatcher();
			},
			setWatcher(){
				this.$nextTick(() =>{
					this.userSearchWatcher = this.$watch(() => this.$refs.userSearchDialog.searchValue, (value) => {
						this.userSearchText = i18n.t('admin.marketing.invite-code.dialog-text-field-wait-to-update');
						if (value === undefined || value === "")
							this.newInviteCodeInfo.gen_by = undefined;
						else if (_.find(this.temp.users, {'name': value}) === undefined){
							this.updateUserSearchData(value);
						}
					});
				});
			}
		}
	}
</script>

<style scoped>

</style>