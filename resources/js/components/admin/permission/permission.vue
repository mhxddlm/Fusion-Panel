<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="5" md="5" sm="12" v-show="!editMode" class="introduction animate-move">
				<mu-card raised>
					<mu-card-title :title="$t('admin.admin.card-title-permission-instruction')" />
					<mu-card-text>
						<mu-row gutter>
							<mu-col span="4">
								<mu-flex justify-content="center">
									<mu-icon color="lightGreen400" value="brightness_1"></mu-icon>
									<div v-html="$t('admin.admin.text-field-full-access')" style="margin-top: 1.5px"></div>
								</mu-flex>
							</mu-col>
							<mu-col span="4">
								<mu-flex justify-content="center">
									<mu-icon color="amber300" value="brightness_1"></mu-icon>
									<div v-html="$t('admin.admin.text-field-partial-access')" style="margin-top: 1.5px"></div>
								</mu-flex>
							</mu-col>
							<mu-col span="4">
								<mu-flex justify-content="center">
									<mu-icon color="deepOrangeA200" value="brightness_1"></mu-icon>
									<div v-html="$t('admin.admin.text-field-no-access')" style="margin-top: 1.5px"></div>
								</mu-flex>
							</mu-col>
						</mu-row>
						<mu-divider style="margin: 20px 0 20px 0;"></mu-divider>
						<div v-html="$t('admin.admin.text-field-introduction-part-1')"></div>
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" :lg="colSpan" :md="colSpan" sm="12" class="animate-move">
				<mu-card raised>
					<mu-card-title :title="$t('admin.admin.card-title-admin-list')" />
					<mu-card-text>
						<mu-data-table :data="adminList" :columns="adminListColumns"
						               :no-data-text="$t('admin.admin.data-table-no-data-text')" :loading="isLoadingAdminList">
							<template slot-scope="scope">
								<td class="is-center">{{scope.row.name}}</td>
								<td class="is-center">
									<mu-tooltip v-for="(value, key, index) in permissionList" :key="key" :content="key">
											<mu-icon :z-depth="2" :color="scope.row.permissions[key] &&
										scope.row.permissions[key].length !== 0 ?
										scope.row.permissions[key].length === value.length ? 'lightGreen400' :
										'amber' + (9 - 2 * scope.row.permissions[key].length) + '00' : 'deepOrangeA200'"
											         value="brightness_1" :class="{'cover-up': index !== 0}" />
									</mu-tooltip>
								</td>
								<td class="is-center">
									<mu-button tag="button" icon color="primary"
									           @click="editAdminPermission(scope.row.user_id)"
									           :disabled="editAdminInfo.user_id === scope.row.user_id">
										<mu-icon value="edit"></mu-icon>
									</mu-button>
									<mu-button :icon="deleteAdminID !== scope.row.user_id"
									           :fab="deleteAdminID === scope.row.user_id"
									           :small="deleteAdminID === scope.row.user_id"
									           v-loading="deleteAdminID === scope.row.user_id && isDeletingAdmin"
									           :style="deleteAdminID === scope.row.user_id ? 'top: -3px': ''"
									           color="red" data-mu-loading-size="24"
									           @click="deleteAdmin(scope.row.user_id)"
									           @blur="deleteAdminID = undefined">
										<mu-icon value="delete_forever"></mu-icon>
									</mu-button>
								</td>
							</template>
						</mu-data-table>
					</mu-card-text>
					<mu-card-actions>
						<mu-row>
							<mu-col span="8" v-show="addMode" style="height:36px;">
								<mu-select ref="userSearchDialog" filterable v-model="temp.newAdmin" :no-data-text="temp.userSearchText" full-width>
									<mu-option v-for="user, index in temp.users" :key="user.id" :label="user.name" :value="user.id"></mu-option>
								</mu-select>
							</mu-col>
							<mu-col span="4">
								<mu-button :flat="!addMode" :full-width="addMode"
								           v-loading="isAddingAdmin" data-mu-loading-size="24"
								           color="primary" @click="addAdmin()">
									<mu-icon left value="person_add"></mu-icon>
									{{!isMobile ? $t('admin.admin.button-text-add-admin') : ""}}
								</mu-button>
							</mu-col>
						</mu-row>
					</mu-card-actions>
				</mu-card>
			</mu-col>
			<mu-col span="12" :lg="12- colSpan" :md="12 - colSpan" sm="12" v-if="editMode" class="permission animate-move" style="display: none;">
				<mu-card raised>
					<mu-card-title :title="$t('admin.admin.card-title-permission', {name: editAdminInfo.name})" />
					<mu-card-text>
						<mu-data-table :data="Object.keys(permissionList)" :columns="permissionListColumns">
							<template slot-scope="scope">
								<td class="is-center">{{scope.row}}</td>
								<td class="is-left">
									<mu-checkbox :key="i.id" v-for="i in permissionList[scope.row]"
									             :value="i.id" v-model="editAdminPermissionIndex"
									             :label="i.method" style="margin: 0 5px 0 5px">
									</mu-checkbox>
								</td>
							</template>
						</mu-data-table>
					</mu-card-text>
					<mu-card-actions>
						<mu-button flat color="primary" @click="updateAdminPermission()"
						           v-loading="isUpdatingPermission" data-mu-loading-size="24">
							<mu-icon left value="update"></mu-icon>{{$t('admin.admin.button-text-update')}}
						</mu-button>
					</mu-card-actions>
				</mu-card>
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	export default {
		data: ()=>({
			editMode: false,
			addMode: false,
			deleteAdminID: undefined,
			isLoadingAdminList: true,
			isUpdatingPermission: false,
			isAddingAdmin: false,
			isDeletingAdmin: false,
			editAdminInfo: {},
			editAdminPermissionIndex:[],
			adminList: [],
			permissionList: [],
			temp: {
				newAdmin: undefined,
				userSearchWatcher: undefined,
				users:[],
				userSearchText: i18n.t('admin.admin.text-field-pls-input'),
			},
			adminListColumns:[
				{title: i18n.t('admin.admin.data-table-column-title-name'), name: "name", align: 'center'},
				{title: i18n.t('admin.admin.data-table-column-title-permission-status'), name: "permissions", align: 'center', width: 190},
				{title: i18n.t('admin.admin.data-table-column-title-actions'), align: 'center', width: 110},
			],
			permissionListColumns:[
				{title: i18n.t('admin.admin.data-table-column-title-permission'), name: "name", align: 'center', width: 150},
				{title: i18n.t('admin.admin.data-table-column-title-permission-status'), name: "permissions", align: 'left'},
			],
		}),
		computed:{
			colSpan(){
				return this.editMode ? "5" : "7";
			},
			isMobile(){
				return window.innerWidth < 800;
			}
		},
		created(){
			this.updateUserSearchData = _.debounce(this.getUserSearch, 500);
		},
		mounted() {
			this.getPermissionList();
			this.getAdminList();
			this.setWatcher();
		},
		beforeDestroy(){
			this.temp.userSearchWatcher();
		},
		methods: {
			deleteAdmin(id){
				if(this.deleteAdminID !== id){
					this.deleteAdminID = id;
				}
				else{
					let t = {};
					t['id'] = id;
					t['permission'] = [];
					this.isDeletingAdmin = true;
					axios.post('/api/v1/admin/updateAdminPermission', {data: t})
						.then(resp =>{
							if(resp.data){
								this.$toast.success(i18n.t('admin.admin.toast-delete-admin-success'));
							}else{
								this.$toast.error(i18n.t('admin.admin.toast-delete-admin-error'));
							}
							this.getAdminList();
							this.isDeletingAdmin = false;
							this.deleteAdminID = undefined;
						})
						.catch(resp =>{

						})
				}
			},
			addAdmin(){
				if(!this.addMode)
					this.addMode = true;
				else{
					let t = {};
					t['id'] = this.temp.newAdmin;
					t['permission'] = [this.permissionList.AdminIndex[0].id];
					this.isAddingAdmin = true;
					axios.post('/api/v1/admin/updateAdminPermission', {data: t})
						.then(resp =>{
							this.getAdminList();
							this.isAddingAdmin = false;
							if(resp.data.add && resp.data.del >= 0){
								this.$toast.success(i18n.t('admin.admin.toast-add-admin-success'));
							}else{
								this.$toast.error(i18n.t('admin.admin.toast-add-admin-error'));
							}
						})
						.catch(resp =>{

						})
				}
			},
			editAdminPermission(id){
				this.editAdminPermissionIndex = [];
				this.editAdminInfo = this.adminList.find(e => e.user_id === id);
				Object.values(this.editAdminInfo.permissions).forEach(e => {
					e.forEach(k =>{
						this.editAdminPermissionIndex.push(k.permission_id);
					})
				});
				if(!this.editMode){
					if(!isMobile()){
						document.getElementsByClassName('introduction')[0].classList.add('slideOutLeft', 'animated');
						setTimeout(()=>{
							document.getElementsByClassName('introduction')[0].classList.remove('slideOutLeft', 'animated');
							this.editMode = true;
						}, 300);
						setTimeout(()=>{
							document.getElementsByClassName('permission')[0].style.display = "";
							document.getElementsByClassName('permission')[0].classList.add('slideInRight', 'animated');
						}, 900);
						setTimeout(()=>{
							document.getElementsByClassName('permission')[0].classList.remove('slideInRight', 'animated');
						}, 1900);
					}else{
						this.editMode = true;
						setTimeout(()=>{
							document.getElementsByClassName('permission')[0].style.display = "";
							document.getElementsByClassName('permission')[0].classList.add('slideInUp', 'animated');
						}, 100);
						setTimeout(()=>{
							document.getElementsByClassName('permission')[0].classList.remove('slideInUp', 'animated');
						}, 700);
					}
				}
			},
			updateAdminPermission(){
				this.isUpdatingPermission = true;
				let t = {};
				t['id'] = this.editAdminInfo.user_id;
				t['permission'] = this.editAdminPermissionIndex;
				axios.post('/api/v1/admin/updateAdminPermission', {data: t})
					.then(resp =>{
						if(resp.data.add && resp.data.del >= 0){
							this.$toast.success(i18n.t('admin.admin.toast-update-permission-success'));
						}else{
							this.$toast.error(i18n.t('admin.admin.toast-update-permission-error'));
						}
						this.getAdminList();
						this.isUpdatingPermission = false;
					})
					.catch(resp =>{

					})
			},
			getAdminList(){
				this.isLoadingAdminList = true;
				axios.get('/api/v1/admin/getAdminList')
					.then(resp =>{
						this.adminList = resp.data;
						this.isLoadingAdminList = false;
					})
					.catch(resp =>{

					})
			},
			getPermissionList(){
				axios.get('/api/v1/admin/getPermissionList')
					.then(resp =>{
						this.permissionList = resp.data;
					})
					.catch(resp =>{

					})
			},
			getUserSearch(a){
				this.temp.userSearchText = i18n.t('admin.admin.text-field-updating');
				axios.get('/api/v1/admin/getUserList',{
					params:{
						search: a
					}})
					.then(resp =>{
						this.temp.userSearchText = i18n.t('admin.admin.text-field-updated');
						this.temp.users = _.unionBy(resp.data.data, this.temp.users, 'id');
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			setWatcher(){
				this.$nextTick(() =>{
					this.temp.userSearchWatcher = this.$watch(() => this.$refs.userSearchDialog.searchValue, (value) => {
						this.userSearchText = i18n.t('admin.admin.text-field-wait-to-update');
						if (value === undefined || value === "")
							this.temp.newAdmin = undefined;
						else if (_.find(this.temp.users, {'name': value}) === undefined){
							this.updateUserSearchData(value);
						}
					});
				});
			}
		}
	}
	function isMobile () {
		return window.innerWidth < 800;
	}
</script>

<style scoped>
	.cover-up{
		margin-left: -10px;
	}
	.animate-move{
		transition: all .3s ease;
	}
</style>