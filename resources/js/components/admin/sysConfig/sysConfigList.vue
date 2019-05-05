<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="3" md="4" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('admin.sysConfig.list.card-title-edit-system-config')" />
					<mu-card-text>
						<p v-html="$t('admin.sysConfig.list.text-field-edit-config')"></p>
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="9" md="8" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('admin.sysConfig.list.card-title-system-config-list')" />
					<mu-card-text>
						<mu-data-table :columns="columns" :data="configList" :loading="tableLoading" :no-data-text="$t('admin.sysConfig.list.data-table-no-data')">
							<template slot-scope="scope">
								<td class="is-center">{{scope.row.name}}</td>
								<td class="is-center">{{scope.row.code}}</td>
								<td class="is-center">{{scope.row.value}}</td>
								<td class="is-center">
									<mu-button tag="button" @click="editThisStupidInfo(scope.row.id)" icon color="primary">
										<mu-icon value="edit"></mu-icon>
									</mu-button>
								</td>
							</template>
						</mu-data-table>
					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
		<mu-dialog width="500" :open.sync="openConfigDialog">
			<mu-row gutter>
				<mu-col span="12" lg="5" md="6" sm="12">
					<h3 v-html="currentEditingInfo.name" style="margin-top: 0em;"/>
					<mu-divider style="margin-top: 10px; margin-bottom: 10px;"/>
					{{currentEditingInfo.description}}
				</mu-col>
				<mu-col span="12" lg="7" md="6" sm="12">
					<mu-form :model="currentEditingInfo">
						<mu-form-item prop="input" :label="$t('admin.sysConfig.list.dialog-text-label-code')">
							<mu-text-field color="primary" v-model="currentEditingInfo.code"></mu-text-field>
						</mu-form-item>
						<mu-form-item prop="input" :label="$t('admin.sysConfig.list.dialog-text-label-value')">
							<mu-text-field color="primary" v-model="currentEditingInfo.value"></mu-text-field>
						</mu-form-item>
					</mu-form>
				</mu-col>
			</mu-row>
			<mu-button slot="actions" flat  @click="openConfigDialog = false">{{$t('admin.sysConfig.list.dialog-button-text-close')}}</mu-button>
			<mu-button slot="actions" flat v-loading="dialogLoading" data-mu-loading-size="24" color="primary" @click="saveConfigInfo()">{{$t('admin.sysConfig.list.dialog-button-text-save')}}</mu-button>
		</mu-dialog>
	</mu-container>
</template>

<script>
	export default {
		data: ()=>({
			configList: [],
			tableLoading: true,
			openConfigDialog: false,
			dialogLoading: false,
			currentEditingInfo: {},
			columns:[
				{title: i18n.t('admin.sysConfig.list.data-table-column-title-name'), name: "name", align: 'center'},
				{title: i18n.t('admin.sysConfig.list.data-table-column-title-code'), name: "code", align: 'center'},
				{title: i18n.t('admin.sysConfig.list.data-table-column-title-value'), name: "value", align: 'center'},
				{title: i18n.t('admin.sysConfig.list.data-table-column-title-action'), name: "action", align: 'center', width: 164},
			],
		}),
		mounted(){
			this.updateConfigList();
		},
		methods: {
			editThisStupidInfo(id){
				this.currentEditingInfo = this.configList.find(e => e.id === id);
				this.openConfigDialog = true;
			},
			saveConfigInfo(){
				this.dialogLoading = true;
				axios.post('/api/v1/admin/updateConfigInfo', {data: this.currentEditingInfo})
					.then(resp =>{
						this.dialogLoading = false;
						this.openConfigDialog = false;
						this.updateConfigList();
						console.log(resp.data);
					})
					.catch(resp =>{

					})
			},
			updateConfigList(){
				axios.get('/api/v1/admin/getSystemConfigList')
					.then(resp =>{
						this.configList = resp.data;
						this.tableLoading = false;
					})
					.catch(resp =>{
						console.log(resp);
					})
			}
		}
	}
</script>

<style scoped>

</style>