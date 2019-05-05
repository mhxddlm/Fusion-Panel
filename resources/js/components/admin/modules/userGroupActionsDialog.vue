<template>
	<mu-dialog :title="$t('admin.user.edit.dialog-title-'+ mode +'-group')" width="450" max-width="95%" :open.sync="openGroupInfoDialog" :esc-press-close="false" :overlay-close="false">
		<mu-form :model="newGroupInfo">
			<mu-select color="primary" :label="$t('admin.user.edit.select-label-choose-group')" :disabled="mode === 'edit'  || (mode === 'add' && parent === 'group')" v-model="newGroupInfo.groupID" :no-data-text="$t('admin.user.edit.select-label-choose-group-not-found')" filterable full-width>
				<mu-option v-for="groupInfo, index in temp.allGroupInfo" :key="groupInfo.id" :label="groupInfo.name" :value="groupInfo.id"></mu-option>
			</mu-select>
			<mu-row gutter>
				<mu-col span="10" sm="10">
					<mu-select ref="userSearchDialog" :disabled="mode === 'edit' || (mode === 'add' && parent === 'user')" :label="$t('admin.node.relay.dialog-text-field-choose-user')" filterable v-model="newGroupInfo.userID" :no-data-text="userSearchText" full-width>
						<mu-option v-for="user, index in temp.users" :key="user.id" :label="user.name" :value="user.id"></mu-option>
					</mu-select>
				</mu-col>
				<mu-col span="2" sm="2">
					<mu-flex justify-content="center" align-items="center" style="height: 88px;">
						<mu-button icon @click="newGroupInfo.userID = undefined" :disabled="mode === 'edit' || (mode === 'add' && parent === 'user')">
							<mu-icon value="delete_sweep"></mu-icon>
						</mu-button>
					</mu-flex>
				</mu-col>
			</mu-row>
			<mu-form-item prop="input" :label="$t('admin.user.edit.text-label-data')">
				<mu-text-field color="primary" v-model="newGroupInfo.trafficLimit" action-icon="all_inclusive" :action-click="() => (newGroupInfo.trafficLimit = 'Unlimited')"></mu-text-field>
			</mu-form-item>
			<mu-form-item prop="input" :label="$t('admin.user.edit.text-label-due-time')">
				<mu-date-input color="primary" display-color="primary" v-model="newGroupInfo.expireTime" action-icon="all_inclusive" :action-click="() => (newGroupInfo.expireTime = 0)" full-width landscape></mu-date-input>
			</mu-form-item>
			<mu-form-item prop="input" :label="$t('admin.user.edit.slider-label-group-type')">
				<mu-slider color="primary" :step="1" :max="5" :min="1" v-model="newGroupInfo.type"></mu-slider>
			</mu-form-item>
		</mu-form>
		<p>{{$t('admin.user.edit.dialog-text-hint')}}</p>
		<mu-button slot="actions" flat  @click="closeDialog()">{{$t('admin.user.edit.dialog-button-close')}}</mu-button>
		<mu-button slot="actions" flat v-loading="dialogLoading" data-mu-loading-size="24" color="primary" @click="addGroupInfo()">{{$t('admin.user.edit.dialog-button-' + mode)}}</mu-button>
	</mu-dialog>
</template>

<script>
	export default {
		props:{
			parent: String
		},
		data: () =>({
			newGroupInfo: {
				id: undefined,
				userID: undefined,
				groupID: undefined,
				trafficLimit: undefined,
				expireTime: 0,
				type: 1
			},
			temp: {
				users: [],
				allGroupInfo: [],
			},
			userSearchText: i18n.t('admin.node.relay.text-field-pls-input'),
			userSearchWatcher: undefined,
			openGroupInfoDialog: false,
			dialogLoading: false,
			mode: 'add',
		}),
		created() {
			this.updateUserSearchData = _.debounce(this.getUserSearch, 500);
		},
		methods: {
			addGroupInfo(){
				this.dialogLoading = true;
				axios.post('/api/v1/admin/addUserGroupInfo', {data: this.newGroupInfo})
					.then(resp => {
						this.dialogLoading = false;
						this.closeDialog();
						this.$emit('close');
					})
					.catch(resp =>{

					})
			},
			showDialog(m, data){
				this.newGroupInfo = data;
				this.mode = m;
				this.openGroupInfoDialog = true;
				this.setWatcher();
				if(m === 'edit' || (m === 'add' && this.parent === 'user'))
					this.updateUserSearchData(this.getUserSearch(data.userID));
				if(_.isEmpty(this.allGroupInfo))
					axios.get('/api/v1/admin/getAllGroups')
						.then(resp => {
							this.temp.allGroupInfo = resp.data;
						})
						.catch(resp =>{

						});
			},
			closeDialog(){
				this.newGroupInfo.id = undefined;
				this.newGroupInfo.groupID = undefined;
				this.newGroupInfo.userID = undefined;
				this.newGroupInfo.trafficLimit = undefined;
				this.newGroupInfo.expireTime = 0;
				this.newGroupInfo.type = 1;
				this.openGroupInfoDialog = false;
				this.userSearchWatcher();
			},
			getUserSearch(a){
				this.userSearchText = i18n.t('admin.node.relay.text-field-updating');
				axios.get('/api/v1/admin/getUserList',{
					params:{
						search: a
					}})
					.then(resp =>{
						this.$refs.userSearchDialog.searchValue = a;
						console.log(a + ' ' + this.$refs.userSearchDialog.searchValue);
						this.userSearchText = i18n.t('admin.node.relay.text-field-updated');
						this.temp.users = _.unionBy(resp.data.data, this.temp.users, 'id');
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			getUsername(id){ //in case we didn't get a username
				axios.get('/api/v1/admin/getUserBasicInfo',{
					params:{
						userID: id
					}})
					.then(resp =>{
						return resp.data.name;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			setWatcher(){
				//TODO: Fix mu-select first initiate fail, after selecting a user, the text field wont show name.
				this.$nextTick(() =>{
					this.userSearchWatcher = this.$watch(() => this.$refs.userSearchDialog.searchValue, (value) => {
						console.log('changed: ' + value);
						this.userSearchText = i18n.t('admin.node.relay.text-field-wait-to-update');
						if (value === undefined || value === "")
							this.newGroupInfo.userID = undefined;
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