<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="3" md="4" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('admin.user.list.card-title-user-list-screening')" />
					<mu-card-text>
						<mu-form :model="selections">
							<mu-form-item prop="input" icon="search" :label="$t('admin.user.list.text-label-screening-search')">
								<mu-text-field v-model="selections.search"></mu-text-field>
							</mu-form-item>
							<mu-form-item prop="date" icon="date_range" :label="$t('admin.user.list.text-label-screening-not-used-since')">
								<mu-date-input v-model="selections.noUseSince" label-float full-width landscape></mu-date-input>
							</mu-form-item>
							<mu-form-item prop="filters" :label="$t('admin.user.list.text-label-screening-filters')">
								<mu-checkbox v-model="selections.filters" value="enabled" :label="$t('admin.user.list.checkbox-screening-enabled')"></mu-checkbox>
								<mu-checkbox v-model="selections.filters" value="paid" :label="$t('admin.user.list.checkbox-screening-paid')"></mu-checkbox>
							</mu-form-item>
						</mu-form>
					</mu-card-text>
					<mu-card-actions>
						<p style="font-style:italic; font-size: 12px; font-color:#707070; text-align: right; margin-bottom: -8px;">{{screeningIndicator}}</p>
					</mu-card-actions>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="9" md="8" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('admin.user.list.card-title-user-list')" />
					<mu-card-text>
						<mu-data-table selectable select-all checkbox :selects.sync="selects" :checkbox-col-width="55"
						               :sort.sync="sort" @sort-change="handleSortChange" :columns="columns"
						               :data="userList" :loading="tableLoading"
						               :no-data-text="$t('admin.user.list.data-table-no-data')">
							<template slot-scope="scope">
								<td class="is-center">{{scope.row.name}}</td>
								<td class="is-center">{{scope.row.email}}</td>
								<td class="is-center">{{scope.row.data_used}}</td>
								<td class="is-center">
									<mu-icon :value="userListStatus(scope.row.status).icon" :color="userListStatus(scope.row.status).color"></mu-icon>
								</td>
								<td class="is-center">
									<mu-button tag="button" icon color="primary" :to="{name:'adminUserEdit', params:{id: scope.row.id}}">
										<mu-icon value="edit"></mu-icon>
									</mu-button>
									<mu-button icon color="red">
										<mu-icon value="delete_forever"></mu-icon>
									</mu-button>
								</td>
							</template>
						</mu-data-table>
						<mu-flex align-items="center" style="padding: 8px;" wrap="wrap">
							{{$t('admin.user.list.data-table-selected')}} <mu-chip delete v-for="selectIndex in selects" @delete="removeSelect(selectIndex)" style="margin: 8px;" :color="userList[selectIndex].status === 1 ? 'red' : 'green'" :key="selectIndex">{{userList[selectIndex].name}}</mu-chip>
						</mu-flex>
						<mu-flex justify-content="center">
							<mu-pagination :total="paginationInfo.totalEntry" :current.sync="paginationInfo.currentPage"
							               :page-size="paginationInfo.pageSize"></mu-pagination>
						</mu-flex>
					</mu-card-text>
					<mu-card-actions v-show="selects.length !== 0">
						<mu-button slot="actions" flat color="primary" @click="openDeleteUserDialog = true">{{$t('admin.user.list.dialog-button-text-delete')}}</mu-button>
					</mu-card-actions>
				</mu-card>
			</mu-col>
		</mu-row>
		<mu-dialog :title="$t('admin.user.list.dialog-title-delete-user')" width="600" max-width="70%" :esc-press-close="false" :overlay-close="false" :open.sync="openDeleteUserDialog">
			<p v-html="$t('admin.user.list.dialog-text-field-warning')" ></p>
			<mu-divider />
			<mu-chip delete v-for="selectIndex in selects" @delete="removeSelect(selectIndex)" style="margin: 8px;" :color="userList[selectIndex].status === 1 ? 'red' : 'green'" :key="selectIndex">{{userList[selectIndex].name}}</mu-chip>
			<mu-button slot="actions" flat  @click="openDeleteUserDialog = false">{{$t('admin.user.list.dialog-button-text-close')}}</mu-button>
			<mu-button slot="actions" flat color="red" @click="deleteUserSure">{{$t('admin.user.list.dialog-button-text-delete')}}</mu-button>
		</mu-dialog>
	</mu-container>
</template>

<script>
	export default {
		data: ()=>({
			tableLoading: true,
			openDeleteUserDialog: false,
			screeningIndicator: i18n.t('admin.user.list.text-screening-loading'),
			selections: {
				search: '',
				filters: [],
				noUseSince: undefined
			},
			userList:[],
			selects: [],
			sort: {
				name: '',
				order: 'asc'
			},
			paginationInfo:{
				pageSize: 10,
				currentPage: 1,
				totalEntry: 1,
			},
			columns:[
				{title: i18n.t('admin.user.list.data-table-column-title-username'), name: "name", align: 'center'},
				{title: i18n.t('admin.user.list.data-table-column-title-email'), name: "email", align: 'center'},
				{title: i18n.t('admin.user.list.data-table-column-title-data-used'), name: "data_used", align: 'center'},
				{title: i18n.t('admin.user.list.data-table-column-title-status'), name: "status", align: 'center', width: 82},
				{title: i18n.t('admin.user.list.data-table-column-title-actions'), name: "actions", align: 'center', width: 164},
			],
		}),
		mounted() {
			this.updateData();
		},
		created() {
			this.updateData = _.debounce(this.getData, 1000);
		},
		watch: {
			selections: {
				handler: function (a, b) {
					this.screeningIndicator = i18n.t('admin.user.list.text-screening-waiting');
					this.updateData();
				}, deep: true
			},
			paginationInfo: {
				handler: function (a, b) {
					this.getData();
				}, deep: true
			}
		},
		methods:{
			handleSortChange ({name, order}) {
				this.userList = this.userList.sort((a, b) => order === 'asc' ? a[name] - b[name] : b[name] - a[name]);
			},
			removeSelect (selectIndex) {
				const index = this.selects.indexOf(selectIndex);
				this.selects.splice(index, 1);
			},
			userListStatus(status){
				let t = {};
				switch(status){
					case 0:
						t.icon = 'cancel';
						t.color = 'red';
						break;
					case 1:
						t.icon = 'check_circle';
						t.color = 'green';
				}
				return t;
			},
			getData (){
				this.tableLoading = true;
				this.screeningIndicator = i18n.t('admin.user.list.text-screening-loading');
				axios.get('/api/v1/admin/getUserList',{
					params:{
						search: this.selections.search,
						num: this.paginationInfo.pageSize,
						page: this.paginationInfo.currentPage
					}})
					.then(resp =>{
						this.paginationInfo.currentPage = resp.data.current_page;
						this.paginationInfo.totalEntry = resp.data.total;
						this.userList = resp.data.data;
						this.tableLoading = false;
						this.screeningIndicator = i18n.t('admin.user.list.text-screening-loaded');
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			deleteUserSure(users){

			}
		}
	}
</script>

<style scoped>
</style>