<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="3" md="3" sm="12">
				<mu-card raised>
					<mu-card-text>
						<mu-flex justify-content="center" align-items="center">
							<mu-button full-width color="primary" @click="deployDialog('add');">{{$t('admin.marketing.exchange-code.button-text-add-code')}}</mu-button>
						</mu-flex>
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="9" md="9" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('admin.marketing.exchange-code.card-title-exchange-code-list')" />
					<mu-card-text>
						<mu-data-table selectable select-all :selects.sync="selects" checkbox :columns="pageAttributes.columns" :data="exchangeCodeList" :loading="pageAttributes.tableLoading" :no-data-text="$t('admin.marketing.exchange-code.data-table-no-data')">
							<template slot-scope="scope">
								<td class="is-center">{{scope.row.code}}</td>
								<td class="is-center">{{scope.row.money}}</td>
								<td class="is-center">{{scope.row.used_count + '/' + scope.row.limit}}</td>
								<td class="is-center">{{scope.row.expire_at}}</td>
								<td class="is-center">
									<mu-button tag="button" icon color="primary" @click="deployDialog('edit',scope.row.id)">
										<mu-icon value="edit"></mu-icon>
									</mu-button>
									<mu-button icon color="red" @click="deleteExchangeCode([scope.row.id])">
										<mu-icon value="delete_forever"></mu-icon>
									</mu-button>
								</td>
							</template>
						</mu-data-table>
					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
		<mu-dialog :title="$t('admin.marketing.exchange-code.dialog-title-' + pageAttributes.mode + '-exchange-code')" width="450" max-width="95%" :open.sync="pageAttributes.openExchangeCodeDialog">
			<mu-form :model="selectedExchangeCode">
				<mu-form-item prop="input" :label="$t('admin.marketing.exchange-code.dialog-text-label-code')">
					<mu-text-field v-model="selectedExchangeCode.code" action-icon="autorenew" :action-click="()=>{selectedExchangeCode.code = ''}" :help-text="$t('admin.marketing.exchange-code.dialog-text-field-empty-for-random')"></mu-text-field>
				</mu-form-item>
				<mu-form-item prop="input" :label="$t('admin.marketing.exchange-code.dialog-text-label-money')">
					<mu-text-field v-model="selectedExchangeCode.money" type="number"></mu-text-field>
				</mu-form-item>
				<mu-form-item prop="input" :label="$t('admin.marketing.exchange-code.dialog-text-label-limit')">
					<mu-text-field v-model="selectedExchangeCode.limit" type="number"></mu-text-field>
				</mu-form-item>
				<mu-form-item prop="input" :label="$t('admin.marketing.exchange-code.dialog-text-label-expire-at')">
					<mu-date-input color="primary" display-color="primary" v-model="selectedExchangeCode.expire_at" action-icon="all_inclusive" :action-click="() => (selectedExchangeCode.expire_at = 0)" :help-text="$t('admin.marketing.exchange-code.dialog-text-field-hint')" full-width landscape></mu-date-input>
				</mu-form-item>
			</mu-form>
			<mu-button slot="actions" flat @click="pageAttributes.openExchangeCodeDialog = false">{{$t('admin.marketing.exchange-code.dialog-button-text-close')}}</mu-button>
			<mu-button slot="actions" flat color="primary" @click="updateExchangeCode()">{{$t('admin.marketing.exchange-code.dialog-button-text-' + pageAttributes.mode)}}</mu-button>
		</mu-dialog>
	</mu-container>
</template>

<script>
	const moduleLoading = () => import('../../public/moduleLoading.vue');
	export default {
		data: ()=>({
			exchangeCodeList: [],
			selects: [],
			selectedExchangeCode:{},
			pageAttributes:{
				mode: 'add',
				tableLoading: true,
				openExchangeCodeDialog: false,
				columns:[
					{title: i18n.t('admin.marketing.exchange-code.data-table-column-title-code'), name: "code", align: 'center'},
					{title: i18n.t('admin.marketing.exchange-code.data-table-column-title-money'), name: "money_include", align: 'center'},
					{title: i18n.t('admin.marketing.exchange-code.data-table-column-title-usage'), name: "used_count", align: 'center'},
					{title: i18n.t('admin.marketing.exchange-code.data-table-column-title-expire'), name: "expire_at", align: 'center'},
					{title: i18n.t('admin.marketing.exchange-code.data-table-column-title-actions'), align: 'center', width: 164},
				],
			},
		}),
		created(){
			this.updateExchangeCodeList = _.debounce(this.getExchangeCodeList, 500);
		},
		mounted(){
			this.updateExchangeCodeList();
		},
		methods: {
			getExchangeCodeList(){
				this.pageAttributes.tableLoading = true;
				axios.get('/api/v1/admin/getExchangeCodeList')
					.then(resp =>{
						this.exchangeCodeList = resp.data;
						this.pageAttributes.tableLoading = false;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			updateExchangeCode(){
				axios.post('/api/v1/admin/updateExchangeCode', {data: this.selectedExchangeCode})
					.then(resp =>{

					})
					.catch(resp =>{

					});
			},
			deleteExchangeCode(id){

			},
			deployDialog(m, id){
				this.pageAttributes.mode = m;
				if(m === 'edit')
					this.selectedExchangeCode = this.exchangeCodeList.find(e => e.id === id);
				this.pageAttributes.openExchangeCodeDialog = true;
			}
		},
		components:{
			'Ell3y-loader': moduleLoading,
		}
	}
</script>

<style scoped>

</style>