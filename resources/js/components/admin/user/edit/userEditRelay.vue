<template>
	<mu-card raised>
		<mu-card-title :title="$t('admin.user.edit.card-title-edit-user-relay-info')" />
		<mu-card-text>
			<mu-data-table max-height="300":columns="columns" :data="userRelayInfo" align="center" :no-data-text="$t('admin.user.edit.data-table-no-data')">
				<template slot-scope="scope">
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
		<mu-card-actions style="padding-top: -10px;">
			<mu-button color="primary" @click="deployDialog('add', undefined)" flat>{{$t('admin.user.edit.button-text-add-relay')}}</mu-button>
		</mu-card-actions>
		<fusion-relay-actions-dialog ref="relayAction" />
	</mu-card>
</template>

<script>
	const relayActionsDialog = () => import('../../modules/relayActionsDialog.vue');
	export default {
		props: {
			userID: Number,
		},
		data: ()=>({
			relayInfo: {},
			userRelayInfo: [],
			openRelayInfoDialog: false,
			columns:[
				{title: i18n.t('admin.user.edit.data-table-column-title-relay-name'), name: "name", align: 'center'},
				{title: i18n.t('admin.user.edit.data-table-column-title-relay-node'), name: "relayNodeName", align: 'center'},
				{title: i18n.t('admin.user.edit.data-table-column-title-final-node'), name: "finalNodeName", align: 'center'},
				{title: i18n.t('admin.user.edit.data-table-column-title-actions'), name: "actions", align: 'center', width: 164},
			],
		}),
		mounted() {
			this.retrieveUserRelayInfo();
		},
		methods: {
			retrieveUserRelayInfo(){
				axios.get('/api/v1/admin/getUserRelayInfo',{
					params:{
						id: this.userID,
					}})
					.then(resp => {
						this.userRelayInfo = resp.data;
						this.$emit('moduleFinishLoading', "userRelayInfo");
					})
					.catch(resp =>{

					})
			},
			deleteRelay(id){
				axios.post('/api/v1/admin/deleteRelayInfo', {data: id})
					.then(resp =>{

					})
					.catch(resp =>{

					});
			},
			deployDialog(m, id){
				let temp = {userID: this.userID};
				if(m === 'edit'){
					temp = this.userRelayInfo.find(e => e.id === id);
					temp.userID = this.userID;
				}
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