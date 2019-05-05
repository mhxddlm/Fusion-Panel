<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="7" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('user.support.index.card-title-ticket-list')" />
					<mu-card-text>
						<mu-data-table :columns="ticketList.columns" :data="ticketList.list" :no-data-text="$t('user.support.index.data-table-no-data')"
						               height="250" hideHeader>
							<template slot-scope="scope">
								<td class="is-center">
									<mu-icon v-if="scope.row.status === 2" value="done_all" color="primary"></mu-icon>
									<mu-icon v-if="scope.row.status === 1" value="done" color="success"></mu-icon>
									<mu-icon v-if="scope.row.status === 0" value="close" color="red"></mu-icon>
									<mu-icon v-if="scope.row.status === -1" value="archive" color="info"></mu-icon>
									<mu-icon v-if="scope.row.status === -2" value="lock" color="warning"></mu-icon>
								</td>
								<td class="is-center">{{scope.row.title}}</td>
								<td class="is-center">
									<mu-button tag="button" icon color="primary"
									           :to="{name:'userTicketDetail', params:{id: scope.row.id}}">
										<mu-icon value="comment"></mu-icon>
									</mu-button>
									<mu-button tag="button" icon color="red" :disabled="scope.row.status <= 0">
										<mu-icon value="close"></mu-icon>
									</mu-button>
								</td>
							</template>
						</mu-data-table>
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="5" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('user.support.index.card-title-QA-list')" />
					<mu-card-text>
						<mu-data-table :columns="QAList.columns" :data="QAList.list" :no-data-text="$t('user.support.index.data-table-ticket-no-data')"
						               height="250" hideHeader>
							<template slot-scope="scope">
								<td class="is-center">{{scope.row.question}}</td>
								<td class="is-center">
									<mu-button tag="button" icon color="primary" :disabled="scope.row.status <= 0">
										<mu-icon value="more"></mu-icon>
									</mu-button>
								</td>
							</template>
						</mu-data-table>
					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
		<mu-row>
			<mu-col span="12">
				<mu-card raised>
					<mu-card-title :title="$t('user.support.index.card-title-documents')" />
					<mu-card-text>

					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	export default {
		data: () => ({
			ticketList: {
				columns:[
					{title: i18n.t('user.support.index.data-table-column-title-status'), name: "status", align: 'center', width: 82},
					{title: i18n.t('user.support.index.data-table-column-title-title'), name: "title", align: 'center'},
					{title: i18n.t('user.support.index.data-table-column-title-actions'), name: "actions", align: 'center', width: 164},
				],
				list:[]
			},
			QAList:{
				columns:[
					{title: i18n.t('user.support.index.data-table-column-title-question'), name: "question", align: 'center'},
					{title: i18n.t('user.support.index.data-table-column-title-actions'), name: "actions", align: 'center', width: 82},
				],
				list: []
			}
		}),
		mounted() {
			this.getTicketList();
			this.getQAList();
		},
		methods:{
			getTicketList(){
				axios.get('/api/v1/user/getTicketList')
					.then(resp =>{
						this.ticketList.list = resp.data;
					})
			},

			getQAList(){
				axios.get('/api/v1/user/getQAList')
					.then(resp =>{
						this.QAList.list = resp.data;
					})
			}
		}
	}
</script>

<style scoped>

</style>