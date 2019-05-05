<template>
	<mu-card raised>
		<mu-card-title :title="$t('admin.group.edit.card-title-edit-group-node-info')" />
		<mu-card-text>
			<mu-data-table :loading="tableLoading" max-height="300" :columns="columns" :data="groupNodeInfo" align="center" :no-data-text="$t('admin.group.edit.data-table-no-data')">
				<template slot-scope="scope">
					<td class="is-center">{{scope.row.name}}</td>
					<td class="is-center">{{scope.row.created_at}}</td>
					<td class="is-center">
						<mu-button tag="button" icon color="primary" :to="{name:'adminNodeEdit', params:{id: scope.row.node_id}}">
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
			<mu-button color="primary" flat>{{$t('admin.group.edit.button-text-add-node')}}</mu-button>
		</mu-card-actions>
	</mu-card>
</template>

<script>
	export default {
		props: {
			groupID: Number,
		},
		data: ()=>({
			tableLoading: true,
			groupNodeInfo: [],
			allNodeInfo: {},
			columns:[
				{title: i18n.t('admin.group.edit.data-table-column-title-node-name'), name: "name", align: 'center'},
				{title: i18n.t('admin.group.edit.data-table-column-title-join-at'), name: "created_at", align: 'center'},
				{title: i18n.t('admin.group.edit.data-table-column-title-actions'), name: "actions", align: 'center', width: 164},
			],
		}),
		mounted() {
			this.retrieveGroupNodeInfo();
		},
		computed: {
		},
		methods: {
			retrieveGroupNodeInfo(){
				this.tableLoading = true;
				axios.get('/api/v1/admin/getGroupNodeInfo',{
					params:{
						id: this.groupID,
					}})
					.then(resp =>{
						this.groupNodeInfo = resp.data;
						this.tableLoading = false;
						this.$emit('moduleFinishLoading', "groupNodeInfo");
					})
					.catch(resp =>{

					})
			}
		}
	}
</script>

<style scoped>

</style>