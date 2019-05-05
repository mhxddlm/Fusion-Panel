<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" :lg="colSpan.lg" :md="colSpan.md" sm="12" class="sticky animate-move">
				<mu-card raised>
					<mu-card-title :title="$t('admin.group.list.card-title-group-list-add')" v-show="showAddGroup" />
					<transition name="slide">
						<mu-card-text v-show="showAddGroup">

							<mu-form :model="newGroupInfo" class="smooooooth">
								<mu-form-item prop="input" :label="$t('admin.group.edit.text-label-name')">
									<mu-text-field color="primary" v-model="newGroupInfo.name"></mu-text-field>
								</mu-form-item>
								<mu-form-item prop="input" :label="$t('admin.group.edit.text-label-order')">
									<mu-text-field color="primary" v-model="newGroupInfo.order"></mu-text-field>
								</mu-form-item>
								<mu-form-item prop="input" :label="$t('admin.group.edit.text-label-speed-limit')">
									<mu-text-field color="primary" v-model="newGroupInfo.speedLimit"></mu-text-field>
								</mu-form-item>
								<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-description')">
									<mu-text-field v-model="newGroupInfo.description" multi-line :rows="3"></mu-text-field>
								</mu-form-item>
								<mu-form-item prop="switch" :label="$t('admin.group.edit.switch-label-hidden')">
									<mu-switch color="primary" v-model="newGroupInfo.hidden"
									           :label="this.newGroupInfo.hidden? $t('admin.group.edit.switch-label-hidden-enable')
								            : $t('admin.group.edit.switch-label-hidden-disable')">
									</mu-switch>
								</mu-form-item>
							</mu-form>
						</mu-card-text>
					</transition>
					<mu-card-actions :class="{'button-add-margin': !showAddGroup}">
						<mu-row gutter>
							<mu-col :span="showAddGroup ? 9 : 12">
								<mu-button full-width :color="showAddGroup ? 'green' : 'primary'" @click="addGroup()">
									{{$t('admin.group.list.button-text-add-group')}}
								</mu-button>
							</mu-col>
							<mu-col :span="showAddGroup ? 3 : 0" v-show="showAddGroup">
								<mu-button flat color="red" @click="showAddGroup = false">
									<mu-icon value="close" />
								</mu-button>
							</mu-col>
						</mu-row>
					</mu-card-actions>
				</mu-card>
				<mu-card raised>
					<mu-card-title :title="$t('admin.group.list.card-title-group-list-screening')" />
					<mu-card-text>
						<mu-form :model="selections">
							<mu-form-item prop="input" icon="search" :label="$t('admin.group.list.text-label-screening-search')">
								<mu-text-field v-model="selections.search"></mu-text-field>
							</mu-form-item>
						</mu-form>
					</mu-card-text>
					<mu-card-actions>
						<p style="font-style:italic; font-size: 12px; font-color:#707070; text-align: right; margin-bottom: -8px;">{{screeningIndicator}}</p>
					</mu-card-actions>
				</mu-card>
			</mu-col>
			<mu-col span="12" :lg="12 - colSpan.lg" :md="12 - colSpan.md" sm="12" class="animate-move">
				<mu-card raised>
					<mu-card-title :title="$t('admin.group.list.card-title-group-list')" />
					<mu-card-text>
						<mu-data-table :columns="columns" :data="groupList" :loading="tableLoading"
						               :no-data-text="$t('admin.group.list.data-table-no-data')">
							<template slot-scope="scope">
								<td class="is-center">{{scope.row.name}}</td>
								<td class="is-center">{{scope.row.memberCount}}</td>
								<td class="is-center">{{scope.row.nodeCount}}</td>
								<td class="is-center">↑ {{scope.row.trafficUp}} | ↓ {{scope.row.trafficDown}}</td>
								<td class="is-center">
									<mu-button tag="button" icon color="primary" :to="{name:'adminGroupEdit', params:{id: scope.row.id}}">
										<mu-icon value="edit"></mu-icon>
									</mu-button>
								</td>
							</template>
						</mu-data-table>
					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	export default {
		data: () => ({
			tableLoading: true,
			showAddGroup: false,
			screeningIndicator: i18n.t('admin.group.list.text-screening-loading'),
			selections: {
				search: '',
			},
			groupList:[],
			selects: [],
			sort: {
				name: '',
				order: 'asc'
			},
			newGroupInfo:{
				name: undefined,
				order: 0,
				speedLimit: 0,
				description: undefined,
				hidden: false
			},
			columns:[
				{title: i18n.t('admin.group.list.data-table-column-title-name'), name: "name", align: 'center'},
				{title: i18n.t('admin.group.list.data-table-column-title-member-count'), name: "memberCount", align: 'center'},
				{title: i18n.t('admin.group.list.data-table-column-title-node-count'), name: "nodeCount", align: 'center'},
				{title: i18n.t('admin.group.list.data-table-column-title-usage'),  align: 'center'},
				{title: i18n.t('admin.group.list.data-table-column-title-actions'), align: 'center', width: 82},
			],
		}),
		computed:{
			colSpan(){
				let t = {};
				if(this.showAddGroup) {
					t.lg = 5;
					t.md = 6;
				}else{
					t.lg = 3;
					t.md = 4;
				}
				return t;
			}
		},
		created() {
			this.updateGroupData = _.debounce(this.getData, 500);
		},
		mounted() {
			this.updateGroupData();
		},
		methods: {
			getData(){
				this.tableLoading = true;
				this.screeningIndicator = i18n.t('admin.group.list.text-screening-loading');
				axios.get('/api/v1/admin/getGroupList')
					.then(resp => {
						resp.data.forEach(e => {
							e.trafficUp = this.dataConvertTraffic(e.usage.up);
							e.trafficDown = this.dataConvertTraffic(e.usage.down);
						});
						this.groupList = resp.data;
						this.tableLoading = false;
						this.screeningIndicator = i18n.t('admin.group.list.text-screening-loaded');
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			addGroup(){
				if(!this.showAddGroup)
					this.showAddGroup = true;
				else{
					axios.post('/api/v1/admin/updateGroupBasicInfo',{data: this.newGroupInfo})
						.then(resp => {
							if(!_.isNull(resp.data.id))
								this.$router.push({ name: 'adminGroupEdit', params: { id: resp.data.id } });
						})
						.catch(resp =>{
							console.log(resp);
						})
				}
			}
		}
	}
</script>

<style scoped>
	.slide-enter-active {
		transition: height .5s ease;
	}
	.slide-leave-active {
		transition: height .5s ease;
	}
	.slide-enter, .slide-leave-to{
		height: 0px;
	}
	.slide-enter-to, .slide-leave{
		height: 100%;
	}
	.smooooooth{
		transition: all 0.5s ease;
	}
	.button-add-margin{
		padding: 15px
	}
	.animate-move{
		transition: all .5s ease;
	}
	@media (min-width: 800px){
		.sticky{
			position: sticky;
			position: -webkit-sticky;
			top: 64px;
		}
	}
</style>