<template>
	<mu-card raised>
		<mu-card-title :title="$t('admin.group.edit.card-title-edit-group-user-info')" />
		<mu-card-text>
			<mu-data-table :loading="tableLoading" max-height="300" :columns="columns" :data="groupUserInfo" align="center" :no-data-text="$t('admin.group.edit.data-table-no-data')">
				<template slot-scope="scope">
					<td class="is-center">{{scope.row.name}}</td>
					<td class="is-center">↑ {{scope.row.trafficUp}} | ↓ {{scope.row.trafficDown}} / {{scope.row.traffic_limit}}</td>
					<td class="is-center">{{scope.row.timeFormatted}}</td>
					<td class="is-center">{{scope.row.type}}</td>
					<td class="is-center">
						<mu-button tag="button" icon color="primary" @click="deployDialog('edit', scope.row.id)">
							<mu-icon value="edit"></mu-icon>
						</mu-button>
						<mu-button icon color="red">
							<mu-icon value="eject"></mu-icon>
						</mu-button>
					</td>
				</template>
			</mu-data-table>
		</mu-card-text>
		<mu-card-actions style="padding-top: -10px;">
			<mu-button color="primary" @click="deployDialog('add', {})" flat>{{$t('admin.group.edit.button-text-add-user')}}</mu-button>
		</mu-card-actions>
		<fusion-user-group-dialog ref="groupAction" parent="group" @close="retrieveGroupUserInfo"/>
	</mu-card>
</template>

<script>
	const userGroupActions = ()=> import('../../modules/userGroupActionsDialog.vue');
	export default {
		props: {
			groupID: Number,
		},
		data: ()=>({
			tableLoading: true,
			groupUserInfo: [],
			allUserInfo: {},
			columns:[
				{title: i18n.t('admin.group.edit.data-table-column-title-user-name'), name: "name", align: 'center'},
				{title: i18n.t('admin.group.edit.data-table-column-title-usage'), name: "usage", align: 'center'},
				{title: i18n.t('admin.group.edit.data-table-column-title-valid-till'), name: "timeFormatted", align: 'center'},
				{title: i18n.t('admin.group.edit.data-table-column-title-deduction-level'), name: "type", align: 'center'},
				{title: i18n.t('admin.group.edit.data-table-column-title-actions'), name: "actions", align: 'center', width: 164},
			],
		}),
		mounted() {
			this.retrieveGroupUserInfo();
		},
		computed: {
		},
		methods: {
			retrieveGroupUserInfo(){
				this.tableLoading = true;
				axios.get('/api/v1/admin/getGroupUserInfo',{
					params:{
						id: this.groupID,
					}})
					.then(resp =>{
						resp.data.forEach(e => {
							e.trafficUp = this.dataConvertTraffic(e.traffic_up);
							e.trafficDown = this.dataConvertTraffic(e.traffic_down);
							e.traffic_limit = _.isNull(e.traffic_limit) ? i18n.t('terms.unlimited') : this.dataConvertTraffic(e.traffic_limit);
							e.timeFormatted =  _.isNull(e.expire_time) ? i18n.t('terms.unlimited') : e.expire_time;
						})
						this.groupUserInfo = resp.data;
						this.tableLoading = false;
						this.$emit('moduleFinishLoading', "groupUserInfo");
					})
					.catch(resp =>{

					})
			},
			deployDialog(m, id){
				let temp = {};
				if(m === 'add'){
					temp.groupID = this.groupID;
					temp.type = 1;
				}
				if(m === 'edit'){
					const t = this.groupUserInfo.find(e => e.id === id);
					temp.id = id;
					temp.userID = t.user_id;
					temp.groupID = t.group_id;
					temp.trafficLimit = t.traffic_limit;
					temp.expireTime = t.expire_time;
					temp.type = t.type;
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