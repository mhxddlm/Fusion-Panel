<template>
	<mu-container>
		<mu-row gutter>
			<mu-col spna="12" lg="3" md="4" sm="12">
				<mu-card raised>
					<mu-card-text>
						<mu-flex justify-content="center" align-items="center">
							<mu-button :to="{name: 'userNodeRelayNew'}" full-width color="primary">{{$t('user.node-relay.card-title-new-relay')}}</mu-button>
						</mu-flex>
					</mu-card-text>
				</mu-card>
				<mu-card raised>
					<mu-card-title :title="$t('user.node-relay.card-title-relay-what')" />
					<mu-card-text>
						IDK what to write here.
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="9" md="8" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('user.node-relay.card-title-relay-list')" />
					<mu-card-text>
						<mu-data-table max-height="300" :columns="columns" :data="relayList" :loading="tableLoading" :no-data-text="$t('user.node-relay.data-table-no-data')">
							<template slot-scope="scope">
								<td class="is-center">{{scope.row.name}}</td>
								<td class="is-center">{{scope.row.relayNodeName}}</td>
								<td class="is-center">{{scope.row.finalNodeName}}</td>
								<td class="is-center">
									<mu-button tag="button" icon color="primary" :to="{name:'userNodeRelayEdit', params:{id: scope.row.id}}">
										<mu-icon value="edit"></mu-icon>
									</mu-button>
									<mu-button icon color="red" @click="deleteRelay(scope.row.id)">
										<mu-icon value="delete_forever"></mu-icon>
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
		data: ()=>({
			relayList:[],
			tableLoading: true,
			columns:[
				{title: i18n.t('user.node-relay.data-table-column-title-relay-name'), name: "name", align: 'center'},
				{title: i18n.t('user.node-relay.data-table-column-title-relay-node'), name: "relayNode", align: 'center'},
				{title: i18n.t('user.node-relay.data-table-column-title-final-node'), name: "finalNode", align: 'center'},
				{title: i18n.t('user.node-relay.data-table-column-title-actions'), name: "actions", align: 'center', width: 164},
			],
		}),
		mounted() {
			axios.get('/api/v1/user/getUserRelay')
			.then(resp =>{
				this.relayList = resp.data;
				this.tableLoading = false;
			})
			.catch(resp =>{
				console.log(resp);
			})
		},
		methods:{
			deleteRelay(id){
				axios.post('/api/v1/user/deleteRelay', {data: id})
					.then(resp =>{
						this.relayList = this.relayList.filter(function (relay){
							if(relay.id === id)
								return false;
							return true;
						});
						console.log(resp.data);
					})
					.catch(resp =>{

					})
			}
		}
	}
</script>

<style scoped>

</style>