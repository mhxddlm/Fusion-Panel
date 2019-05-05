<template>
	<mu-dialog :title="$t('admin.node.relay.dialog-title-'+ mode +'-relay')" width="360" :open.sync="openRelayInfoDialog" :esc-press-close="false" :overlay-close="false">
		<transition name="fade">
			<mu-linear-progress color="#E040FB" :value="dialogLoader" v-show="dialogLoader !== 100" mode="determinate" />
		</transition>
		<mu-form :model="newRelayInfo">
			<mu-row gutter>
				<mu-col span="10" sm="10">
					<mu-select ref="userSearchDialog" :disabled="mode === 'edit'" :label="$t('admin.node.relay.dialog-text-field-choose-user')" filterable v-model="newRelayInfo.userID" :no-data-text="userSearchText" full-width>
						<mu-option v-for="user, index in temp.users" :key="user.id" :label="user.name" :value="user.id"></mu-option>
					</mu-select>
				</mu-col>
				<mu-col span="2" sm="2">
					<mu-flex justify-content="center" align-items="center" style="height: 88px;">
						<mu-button icon @click="newRelayInfo.userID = undefined" :disabled="mode === 'edit'">
							<mu-icon value="delete_sweep"></mu-icon>
						</mu-button>
					</mu-flex>
				</mu-col>
			</mu-row>
			<mu-select color="primary" :label="$t('admin.node.relay.dialog-text-field-choose-relay')" v-model="newRelayInfo.relayNodeID" :no-data-text="userNodeUpdatingText" filterable full-width>
				<mu-option v-for="nodeInfo, index in relayNodeSelectable" :key="nodeInfo.node_id" :label="nodeInfo.name" :value="nodeInfo.node_id"></mu-option>
			</mu-select>
			<mu-select color="primary" :label="$t('admin.node.relay.dialog-text-field-choose-final')" v-model="newRelayInfo.finalNodeID" :no-data-text="userNodeUpdatingText" filterable full-width>
				<mu-option v-for="nodeInfo, index in finalNodeSelectable" :key="nodeInfo.node_id" :label="nodeInfo.name" :value="nodeInfo.node_id"></mu-option>
			</mu-select>
			<mu-form-item prop="input" :label="$t('admin.node.relay.dialog-text-field-relay-name')">
				<mu-text-field color="primary" v-model="newRelayInfo.name"></mu-text-field>
			</mu-form-item>
		</mu-form>
		<mu-button slot="actions" flat @click="closeDialog()">{{$t('admin.node.relay.dialog-button-close')}}</mu-button>
		<mu-button slot="actions" flat data-mu-loading-size="24" v-loading="dialogLoading" color="primary" @click="addRelayInfo()">{{$t('admin.node.relay.dialog-button-' + mode)}}</mu-button>
	</mu-dialog>
</template>

<script>
	export default {
		data: () => ({
			mode: 'add',
			newRelayInfo: {
				userID: undefined,
			},
			dialogInfoLoading: {
				nodeInfo: false,
				userSearch: false,
			},
			openRelayInfoDialog: false,
			userSearchText: i18n.t('admin.node.relay.text-field-pls-input'),
			userNodeUpdatingText: i18n.t('admin.node.relay.text-field-pls-choose-user'),
			dialogLoading: false,
			temp:{
				users: [],
				allNodeInfo: [],
			},
			userSearchWatcher: undefined,
			selectedUserWatcher: undefined,

		}),
		computed: {
			relayNodeSelectable() {
				return _.filter(this.temp.allNodeInfo, o =>{ return (o.allowRelay === 1 && o.node_id !== this.newRelayInfo.finalNodeID);});
			},
			finalNodeSelectable(){
				return _.filter(this.temp.allNodeInfo, o =>{ return (o.node_id !== this.newRelayInfo.relayNodeID);});
			},
			dialogLoader() {
				let a = 0;
				Object.values(this.dialogInfoLoading).forEach(e => {
					if(e === true) a+= 50;
				});
				return a;
			}
		},
		created(){
			this.updateUserSearchData = _.debounce(this.getUserSearch, 500);
		},
		mounted(){
		},
		methods: {
			getUserSearch(a){
				this.userSearchText = i18n.t('admin.node.relay.text-field-updating');
				this.dialogInfoLoading.userSearch = false;
				axios.get('/api/v1/admin/getUserList',{
					params:{
						search: a
					}})
					.then(resp =>{
						this.userSearchText = i18n.t('admin.node.relay.text-field-updated');
						this.temp.users = _.unionBy(resp.data.data, this.temp.users, 'id');
						this.dialogInfoLoading.userSearch = true;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			getUserNodes(id){
				this.userNodeUpdatingText = i18n.t('admin.node.relay.text-field-updating');
				this.dialogInfoLoading.nodeInfo = false;
				axios.get('/api/v1/admin/getUserNode',{
					params:{
						userID: id
					}})
					.then(resp =>{
						this.temp.allNodeInfo = resp.data;
						this.userNodeUpdatingText = i18n.t('admin.node.relay.dialog-text-field-no-node');
						this.dialogInfoLoading.nodeInfo = true;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			getUsername(){ //in case we didn't get a username in relayInfo
				axios.get('/api/v1/admin/getUserBasicInfo',{
					params:{
						userID: this.newRelayInfo.userID
					}})
					.then(resp =>{
						this.newRelayInfo.username = resp.data.name;
						this.dialogInfoLoading.userSearch = true;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			addRelayInfo(){
				this.dialogLoading = true;
				axios.post('/api/v1/admin/addRelayInfo', {data: this.newRelayInfo})
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
				if(_.isEmpty(t)) this.newRelayInfo.userID = undefined;
				else{
					this.newRelayInfo = t;
				}
				this.setWatcher();
				this.dialogInfoLoading.nodeInfo = false;
				this.dialogInfoLoading.userSearch = false;
				this.openRelayInfoDialog = true;
				if(this.mode === 'edit' || (this.mode === 'add' && !_.isEmpty(t))){
					this.updateUserSearchData(this.newRelayInfo.username);
					this.getUserNodes(this.newRelayInfo.userID);
				}
			},
			closeDialog(){
				this.newRelayInfo = {};
				this.temp.allNodeInfo = [];
				this.openRelayInfoDialog = false;
				this.userSearchWatcher();
				this.selecteduserWatcher();
			},
			setWatcher(){
				this.$nextTick(() =>{
					this.userSearchWatcher = this.$watch(() => this.$refs.userSearchDialog.searchValue, (value) => {
						this.userSearchText = i18n.t('admin.node.relay.text-field-wait-to-update');
						if (value === undefined || value === "")
							this.newRelayInfo.userID = undefined;
						else if (_.find(this.temp.users, {'name': value}) === undefined){
							this.updateUserSearchData(value);
							this.dialogInfoLoading.nodeInfo = false;
						}
					});
					this.selecteduserWatcher = this.$watch(() => this.newRelayInfo.userID, (value, oldValue) => {
						this.userNodeUpdatingText = i18n.t('admin.node.relay.text-field-wait-to-update');
						if(this.newRelayInfo.username === undefined) this.getUsername();
						else this.dialogInfoLoading.userSearch = true;
						if(value !== undefined && value !== oldValue) this.getUserNodes(value);
					});
				});
			}
		}
	}
</script>

<style scoped>

</style>