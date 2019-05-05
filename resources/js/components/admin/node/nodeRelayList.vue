<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="3" md="4" sm="12">
				<mu-card raised>
					<mu-card-text>
						<mu-flex justify-content="center" align-items="center">
							<mu-button full-width color="primary" @click="deployDialog('add', undefined);">{{$t('admin.node.relay.button-text-new-relay')}}</mu-button>
						</mu-flex>
					</mu-card-text>
				</mu-card>
				<mu-card raised>
					<mu-card-title :title="$t('admin.node.relay.card-title-screening')" />
					<mu-card-text>
						<mu-form :model="selections">
							<mu-row gutter>
								<mu-col span="10" sm="10">
									<mu-select :label="$t('admin.node.relay.text-field-screening-relay-node')" filterable v-model="selections.relayNode" :no-data-text="$t('admin.node.relay.text-field-updated')" full-width>
										<mu-option v-for="node,index in temp.relays" :key="node.relayNodeID" :label="node.relayNodeName" :value="node.relayNodeID"></mu-option>
									</mu-select>
								</mu-col>
								<mu-col span="2" sm="2">
									<mu-flex justify-content="center" align-items="center" style="height: 88px;">
									<mu-button icon @click="selections.relayNode = undefined">
										<mu-icon value="delete_sweep"></mu-icon>
									</mu-button>
									</mu-flex>
								</mu-col>
							</mu-row>
							<mu-row gutter>
								<mu-col span="10" sm="10">
									<mu-select :label="$t('admin.node.relay.text-field-screening-final-node')" filterable v-model="selections.finalNode" :no-data-text="$t('admin.node.relay.text-field-updated')" full-width>
										<mu-option v-for="node,index in temp.finals" :key="node.finalNodeID" :label="node.finalNodeName" :value="node.finalNodeID"></mu-option>
									</mu-select>
								</mu-col>
								<mu-col span="2" sm="2">
									<mu-flex justify-content="center" align-items="center" style="height: 88px;">
									<mu-button icon @click="selections.finalNode = undefined">
										<mu-icon value="delete_sweep"></mu-icon>
									</mu-button>
									</mu-flex>
								</mu-col>
							</mu-row>
							<mu-row gutter>
								<mu-col span="10" sm="10">
									<mu-select ref="userSearch" :label="$t('admin.node.relay.text-field-screening-user')" filterable v-model="selections.userID" :no-data-text="pageAttributes.userSearchText" full-width>
										<mu-option v-for="user,index in temp.users" :key="user.id" :label="user.name" :value="user.id"></mu-option>
									</mu-select>
								</mu-col>
								<mu-col span="2" sm="2">
									<mu-flex justify-content="center" align-items="center" style="height: 88px;">
									<mu-button icon @click="selections.userID = undefined">
										<mu-icon value="delete_sweep"></mu-icon>
									</mu-button>
									</mu-flex>
								</mu-col>
							</mu-row>
						</mu-form>
					</mu-card-text>
					<mu-card-actions>
						<p style="font-style:italic; font-size: 12px; font-color:#707070; text-align: right; margin-bottom: -8px;">{{pageAttributes.screeningIndicator}}</p>
					</mu-card-actions>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="9" md="8" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('admin.node.relay.card-title-relay-list')" />
					<mu-card-text>
						<mu-data-table selectable select-all :selects.sync="selects" checkbox :columns="pageAttributes.columns" :data="relayList" :loading="pageAttributes.tableLoading" :no-data-text="$t('admin.node.relay.data-table-no-data')">
							<template slot-scope="scope">
								<td class="is-center">{{scope.row.username}}</td>
								<td class="is-center">{{scope.row.name}}</td>
								<td class="is-center">{{scope.row.relayNodeName}}</td>
								<td class="is-center">{{scope.row.finalNodeName}}</td>
								<td class="is-center">
									<mu-button tag="button" icon color="primary" @click="deployDialog('edit', scope.row.id)">
										<mu-icon value="edit"></mu-icon>
									</mu-button>
									<mu-button icon color="red" @click="deleteRelay(scope.row.id)">
										<mu-icon value="delete_forever"></mu-icon>
									</mu-button>
								</td>
							</template>
						</mu-data-table>
					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
		<fusion-relay-actions-dialog ref="relayAction" @close="updateRelayData"/>
	</mu-container>
</template>

<script>
	const relayActionsDialog = () => import('../modules/relayActionsDialog.vue');
	export default {
		data: ()=>({
			relayList:[],
			selections:{
				relayNode: undefined,
				finalNode: undefined,
				userID: undefined,
			},
			selects: [],
			pageAttributes:{
				openRelayInfoDialog: false,
				tableLoading: true,
				screeningIndicator: i18n.t('admin.node.relay.text-screening-loading'),
				columns:[
					{title: i18n.t('admin.node.relay.data-table-column-title-username'), name: "username", align: 'center'},
					{title: i18n.t('admin.node.relay.data-table-column-title-name'), name: "name", align: 'center'},
					{title: i18n.t('admin.node.relay.data-table-column-title-relay-node'), name: "relayNodeName", align: 'center'},
					{title: i18n.t('admin.node.relay.data-table-column-title-final-node'), name: "finalNodeName", align: 'center'},
					{title: i18n.t('admin.node.relay.data-table-column-title-actions'), name: "actions", align: 'center', width: 164},
				],
				sort: {
					name: '',
					order: 'asc'
				},
				userSearchText: i18n.t('admin.node.relay.text-field-pls-input'),
			},
			temp:{
				relays:[],
				finals:[],
				users:[],
				relayID: undefined,
			}
		}),
		watch: {
			selections: {
				handler: function() {
					this.pageAttributes.screeningIndicator = i18n.t('admin.node.relay.text-screening-waiting');
					this.updateRelayData();
				}, deep: true
			}
		},
		computed:{

		},
		created() {
			this.updateRelayData = _.debounce(this.getData, 500);
			this.updateUserSearchData = _.debounce(this.getUserSearch, 500);
		},
		mounted() {
			this.updateRelayData();
			this.$watch(() => this.$refs.userSearch.searchValue, (value) => {
				this.pageAttributes.userSearchText = i18n.t('admin.node.relay.text-field-wait-to-update');
				if (value === undefined || value === "")
					this.selections.userID = undefined;
				else if (_.find(this.temp.users, {'name': value}) === undefined)
					this.updateUserSearchData(value);
			});
		},
		methods:{
			getData(){
				this.pageAttributes.tableLoading = true;
				this.pageAttributes.screeningIndicator = i18n.t('admin.node.relay.text-screening-loading');
				axios.get('/api/v1/admin/getRelayList',{
					params:{
						relayNode: this.selections.relayNode,
						finalNode: this.selections.finalNode,
						userID: this.selections.userID
					}})
					.then(resp => {
						this.relayList = resp.data;
						this.temp.relays = _.uniqBy(this.relayList, 'relayNodeID');
						this.temp.finals = _.uniqBy(this.relayList, 'finalNodeID');
						this.pageAttributes.tableLoading = false;
						this.pageAttributes.screeningIndicator = i18n.t('admin.node.list.text-screening-loaded');
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			getUserSearch(a){
				this.pageAttributes.userSearchText = i18n.t('admin.node.relay.text-field-updating');
				axios.get('/api/v1/admin/getUserList',{
					params:{
						search: a
					}})
					.then(resp =>{
						this.pageAttributes.userSearchText = i18n.t('admin.node.relay.text-field-updated');
						this.temp.users = _.unionBy(resp.data.data, this.temp.users, 'id');
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			deleteRelay(id){
				axios.post('/api/v1/admin/deleteRelay', {data: id})
					.then(resp =>{
						this.relayList = this.relayList.filter(function (relay){
							if(relay.id === id)
								return false;
							return true;
						});
						console.log(resp.data);
					})
					.catch(resp =>{

					})
			},
			deployDialog(m, id){
				let temp = {userID: undefined};
				if(m === 'edit')
					temp = this.relayList.find(e => e.id === id);
				this.$nextTick(()=>{
					this.$refs.relayAction.openDialog(m, temp);
				});
			}
		},
		components:{
			'fusion-relay-actions-dialog': relayActionsDialog
		}
	}
</script>

<style scoped>

</style>