<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="3" md="4" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('admin.bulletin.list.card-title-instruction')" />
					<mu-card-text>
						<p v-html="$t('admin.bulletin.list.text-field-instruction')"></p>
					</mu-card-text>
				</mu-card>
				<mu-card raised>
					<mu-card-title :title="$t('admin.bulletin.list.card-title-bulletin-list')" />
					<mu-card-text>
						<mu-data-table :columns="columns" :data="bulletinIndex" :loading="tableLoading" :no-data-text="$t('admin.bulletin.list.data-table-no-data')">
							<template slot-scope="scope">
								<td class="is-center">{{$t('bulletin.' + scope.row.type)}}</td>
								<td class="is-center">
									<mu-button tag="button" icon color="primary" @click="selectedType = scope.row.type">
										<mu-icon value="edit"></mu-icon>
									</mu-button>
								</td>
							</template>
						</mu-data-table>
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="9" md="8" sm="12">
				<fusion-bulletin-edit :type="selectedType" />
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	const adminBulletinEdit = () => import('./bulletinEdit.vue');
	export default {
		data: ()=>({
			bulletinIndex: [],
			tableLoading: true,
			selectedType: undefined,
			columns:[
				{title: i18n.t('admin.bulletin.list.data-table-column-title-name'), name: "type_name", align: 'center'},
				{title: i18n.t('admin.bulletin.list.data-table-column-title-action'), name: "action", align: 'center', width: 82},
			],
		}),
		created(){
			this.selectedType = _.isNull(this.$route.params.id) ? undefined : this.$route.params.id;
		},
		mounted(){
			this.getBulletinList();
		},
		methods: {
			getBulletinList(){
				axios.get('/api/v1/admin/getBulletinTypeIndex')
					.then(resp =>{
						this.bulletinIndex = resp.data;
						this.tableLoading = false;
					})
					.catch(resp =>{
						console.log(resp);
					})
			}
		},
		components:{
			'fusion-bulletin-edit': adminBulletinEdit
		}
	}
</script>

<style scoped>

</style>