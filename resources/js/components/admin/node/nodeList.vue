<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" :lg="colSpan.lg" :md="colSpan.md" sm="12" class="sticky animate-move">
				<mu-card raised>
					<mu-card-title :title="$t('admin.node.list.card-title-node-list-add')" v-show="showAddNode" />
					<transition name="slide">
						<mu-card-text v-show="showAddNode">
							<mu-form :model="newNodeInfo">
								<mu-row gutter>
									<mu-col span="12" lg="6" md="6" sm="12">
										<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-name')">
											<mu-text-field color="primary" v-model="newNodeInfo.name"></mu-text-field>
										</mu-form-item>
									</mu-col>
									<mu-col span="12" lg="6" md="6" sm="12">
										<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-area')">
											<mu-text-field color="primary" v-model="newNodeInfo.area"></mu-text-field>
										</mu-form-item>
									</mu-col>
								</mu-row>
								<mu-row gutter>
									<mu-col span="12" lg="6" md="6" sm="12">
										<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-address')">
											<mu-text-field color="primary" v-model="newNodeInfo.address"></mu-text-field>
										</mu-form-item>
									</mu-col>
									<mu-col span="12" lg="6" md="6" sm="12">
										<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-real-ip')">
											<mu-text-field color="primary" v-model="newNodeInfo.real_ip"></mu-text-field>
										</mu-form-item>
									</mu-col>
								</mu-row>
								<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-description')">
									<vue-editor v-model="newNodeInfo.description" :editorToolbar="customToolbar"></vue-editor>
								</mu-form-item>
								<mu-row gutter>
									<mu-col span="12" lg="6" md="6" sm="12">
										<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-rate')">
											<mu-text-field color="primary" v-model="newNodeInfo.rate" type="number"></mu-text-field>
										</mu-form-item>
									</mu-col>
									<mu-col span="12" lg="6" md="6" sm="12">
										<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-speed-limit')">
											<mu-text-field color="primary" v-model="newNodeInfo.speed_limit"></mu-text-field>
										</mu-form-item>
									</mu-col>
									<mu-col span="12" lg="6" md="6" sm="12">
										<mu-form-item prop="switch" :label="$t('admin.node.edit.switch-label-relay-status')">
											<mu-switch color="primary" v-model="newNodeInfo.allow_relay" :label="this.newNodeInfo.allow_relay? $t('admin.node.edit.switch-label-relay-enable') : $t('admin.node.edit.switch-label-relay-disable')"></mu-switch>
										</mu-form-item>
									</mu-col>
									<mu-col span="12" lg="6" md="6" sm="12">
										<mu-form-item prop="switch" :label="$t('admin.node.edit.switch-label-hidden-status')">
											<mu-switch color="primary" v-model="newNodeInfo.hidden" :label="this.newNodeInfo.hidden? $t('admin.node.edit.switch-label-hidden') : $t('admin.node.edit.switch-label-shown')"></mu-switch>
										</mu-form-item>
									</mu-col>
								</mu-row>
							</mu-form>
						</mu-card-text>
					</transition>
					<mu-card-actions :class="{'button-add-margin': !showAddNode}">
						<mu-row gutter>
							<mu-col :span="showAddNode ? 9 : 12">
								<mu-button full-width :color="showAddNode ? 'green' : 'primary'" @click="addNode()">
									{{$t('admin.node.list.button-text-add-node')}}
								</mu-button>
							</mu-col>
							<mu-col :span="showAddNode ? 3 : 0" v-show="showAddNode">
								<mu-button flat color="red" @click="showAddNode = false">
									<mu-icon value="close" />
								</mu-button>
							</mu-col>
						</mu-row>
					</mu-card-actions>
				</mu-card>
				<mu-card raised>
					<mu-card-title :title="$t('admin.node.list.card-title-node-list-screening')" />
					<mu-card-text>
						<mu-form :model="selections">
							<mu-form-item prop="input" icon="search" :label="$t('admin.node.list.text-label-screening-search')">
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
					<mu-card-title :title="$t('admin.node.list.card-title-node-list')" />
					<mu-card-text>
						<mu-data-table selectable :selects.sync="selects" checkbox :sort.sync="sort" @sort-change="handleSortChange" :columns="columns" :data="nodeList" :loading="tableLoading" :no-data-text="$t('admin.node.list.data-table-no-data')">
								<template slot-scope="scope">
									<td class="is-center">{{scope.row.name}}</td>
									<td class="is-center">{{scope.row.address}}</td>
									<td class="is-center">{{scope.row.traffic}}</td>
									<td class="is-center">
										<mu-button tag="button" icon color="primary" :to="{name:'adminNodeEdit', params:{id: scope.row.id}}">
											<mu-icon value="edit"></mu-icon>
										</mu-button>
										<mu-button icon color="red" @click="">
											<mu-icon value="delete_forever"></mu-icon>
										</mu-button>
									</td>
								</template>
						</mu-data-table>
						<mu-flex align-items="center" style="padding: 8px;" wrap="wrap">
							{{$t('admin.node.list.data-table-selected')}} <mu-chip delete v-for="selectIndex in selects" @delete="removeSelect(selectIndex)" style="margin: 8px;" color="green" :key="selectIndex">{{nodeList[selectIndex].name}}</mu-chip>
						</mu-flex>
						<mu-flex justify-content="center">
							<mu-pagination :total="paginationInfo.totalEntry" :current.sync="paginationInfo.currentPage"
							               :page-size="paginationInfo.pageSize"></mu-pagination>
						</mu-flex>
					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	import { VueEditor } from 'vue2-editor'
	export default {
		data: () =>({
			tableLoading: true,
			showAddNode: false,
			screeningIndicator: i18n.t('admin.node.list.text-screening-loading'),
			selections: {
				search: '',
			},
			nodeList:[],
			selects: [],
			newNodeInfo:{
				rate: 1.00,
				allow_relay: true
			},
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
				{title: i18n.t('admin.node.list.data-table-column-title-name'), name: "name", align: 'center'},
				{title: i18n.t('admin.node.list.data-table-column-title-address'), name: "address", align: 'center'},
				{title: i18n.t('admin.node.list.data-table-column-title-usage'), name: "traffic", align: 'center'},
				{title: i18n.t('admin.node.list.data-table-column-title-actions'), align: 'center', width: 164},
			],
			customToolbar: [
				[{ 'size': ['small', false, 'large', 'huge'] }],
				['bold', 'italic', 'underline', 'strike'],
				[{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
				['blockquote', 'code-block'],
				[{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
				[{ 'indent': '-1'}, { 'indent': '+1' }],
				[{ 'color': [] }],
			]
		}),
		computed:{
			colSpan(){
				let t = {};
				if(this.showAddNode) {
					t.lg = 6;
					t.md = 6;
				}else{
					t.lg = 3;
					t.md = 4;
				}
				return t;
			}
		},
		mounted() {
			this.updateData();
		},
		created() {
			this.updateData = _.debounce(this.getData, 1000);
		},
		watch: {
			selections: {
				handler: function (a, b) {
					this.screeningIndicator = i18n.t('admin.node.list.text-screening-waiting');
					this.updateData();
				}, deep: true
			}
		},
		methods: {
			handleSortChange ({name, order}) {
				this.nodeList = this.nodeList.sort((a, b) => order === 'asc' ? a[name] - b[name] : b[name] - a[name]);
			},
			removeSelect (selectIndex) {
				const index = this.selects.indexOf(selectIndex);
				this.selects.splice(index, 1);
			},
			getData (){
				this.tableLoading = true;
				this.screeningIndicator = i18n.t('admin.node.list.text-screening-loading');
				axios.get('/api/v1/admin/getNodeList',{
					params:{
						search: this.selections.search,
						start: this.currentPage * this.pageContent,
						end: (this.currentPage + 1) * this.pageContent - 1,
					}})
					.then(resp => {
						this.paginationInfo.currentPage = resp.data.current_page;
						this.paginationInfo.totalEntry = resp.data.total;
						resp.data.data.forEach(e => {
							e.traffic = this.dataConvertTraffic(e.traffic);
						});
						this.nodeList = resp.data.data;
						this.tableLoading = false;
						this.screeningIndicator = i18n.t('admin.node.list.text-screening-loaded');
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			addNode(){
				if(!this.showAddNode)
					this.showAddNode = true;
				else{
					axios.post('/api/v1/admin/updateNodeBasicInfo',{data: this.newNodeInfo})
						.then(resp => {
							if(!_.isNull(resp.data.id))
								this.$router.push({ name: 'adminNodeEdit', params: { id: resp.data.id } });
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