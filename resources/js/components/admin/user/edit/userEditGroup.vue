<template>
	<mu-card raised>
		<mu-card-title :title="$t('admin.user.edit.card-title-edit-user-group-info')" />
		<mu-card-text>
			<mu-data-table :loading="tableLoading" max-height="300":columns="columns" :data="userGroupInfo" align="center" :no-data-text="$t('admin.user.edit.data-table-no-data')">
				<template slot-scope="scope">
					<td class="is-center">{{scope.row.name}}</td>
					<td class="is-center">{{scope.row.trafficTotal + " / " + scope.row.traffic_limit}}</td>
					<td class="is-center">{{scope.row.timeFormatted}}</td>
					<td class="is-center">
						<mu-button tag="button" icon color="primary" @click="deployDialog('edit', scope.row.id)">
							<mu-icon value="edit"></mu-icon>
						</mu-button>
						<mu-button icon color="red" @click="deleteGroup(scope.row.id)">
							<mu-icon value="delete_forever"></mu-icon>
						</mu-button>
					</td>
				</template>
			</mu-data-table>
		</mu-card-text>
		<mu-card-actions style="padding-top: -10px;">
			<mu-button color="primary" @click="deployDialog('add',{})" flat>{{$t('admin.user.edit.button-text-add-group')}}</mu-button>
		</mu-card-actions>
		<fusion-user-group-dialog ref="groupAction" parent="user" @close="retrieveUserGroupInfo"/>
	</mu-card>
</template>

<script>
	const userGroupActions = ()=> import('../../modules/userGroupActionsDialog.vue');
	export default {
		props: {
			userID: Number,
		},
		data: ()=>({
			openGroupInfoDialog: false,
			tableLoading: true,
			dialogLoading: false,
			userGroupInfo: [],
			allGroupInfo: {},
			columns:[
				{title: i18n.t('admin.user.edit.data-table-column-title-group-name'), name: "name", align: 'center'},
				{title: i18n.t('admin.user.edit.data-table-column-title-traffic'), name: "traffic", align: 'center'},
				{title: i18n.t('admin.user.edit.data-table-column-title-due'), name: "time", align: 'center'},
				{title: i18n.t('admin.user.edit.data-table-column-title-actions'), name: "actions", align: 'center', width: 164},
			],
		}),
		mounted() {
			this.retrieveUserGroupInfo();
		},
		methods: {
			retrieveUserGroupInfo(){
				this.tableLoading = true;
				axios.get('/api/v1/admin/getUserGroupInfo',{
					params:{
						id: this.userID,
					}})
					.then(resp =>{
						resp.data.forEach(e => {
							e.trafficTotal = this.dataConvertTraffic(e.traffic_up + e.traffic_down);
							e.traffic_limit = _.isNull(e.traffic_limit) ? i18n.t('terms.unlimited') : this.dataConvertTraffic(e.traffic_limit);
							e.timeFormatted =  _.isNull(e.expire_time) ? i18n.t('terms.unlimited') : e.expire_time;
						});
						this.userGroupInfo = resp.data;
						this.tableLoading = false;
						this.$emit('moduleFinishLoading', "userGroupInfo");
					})
					.catch(resp =>{

					})
			},
			deleteGroup(id){
				axios.post('/api/v1/admin/deleteUserGroup', {data: id})
					.then(resp =>{
						this.retrieveUserGroupInfo();
						this.$toast.success(i18n.t('admin.user.edit.toast-delete-user-group-success'));
					})
					.catch(resp =>{

					});

			},
			deployDialog(m, id){
				let temp = {};
				if(m === 'edit'){
					const t = this.userGroupInfo.find(e => e.id === id);
					temp.id = id;
					temp.userID = t.user_id;
					temp.groupID = t.group_id;
					temp.trafficLimit = t.traffic_limit;
					temp.expireTime = t.expire_time;
					temp.type = t.type;
				}else{
					temp.userID = this.userID;
					temp.type = 1;
				}
				this.$nextTick(()=>{
					this.$refs.groupAction.showDialog(m, temp);
				});
			}
		},
		components:{
			'fusion-user-group-dialog': userGroupActions
		}
	}
</script>

<style scoped>

</style>