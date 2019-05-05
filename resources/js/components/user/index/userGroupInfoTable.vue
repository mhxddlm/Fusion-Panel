<template>
	<mu-data-table max-height="300" :loading="!isDisplay" :data-mu-loading-text="$t('terms.loading')"
	               :columns="columns" :data="list" :sort.sync="sort" @sort-change="handleSortChange" align="center"
	               :no-data-text="$t('user.components.user-group-info-table.no-data')">
		<template slot-scope="scope">
			<td class="is-center">{{scope.row.name}}</td>
			<td class="is-center">{{scope.row.trafficTotal + " / " + scope.row.trafficLimit}}</td>
			<td class="is-center">{{scope.row.timeFormatted}}</td>
		</template>
	</mu-data-table>
</template>

<script>
	export default {
		data: () =>({
			sort: {
				name: '',
				order: 'asc'
			},
			columns:[
				{title: i18n.t('user.components.user-group-info-table.group-name'), name: "name", align: 'center'},
				{title: i18n.t('user.components.user-group-info-table.group-traffic'), name: "traffic", align: 'center'},
				{title: i18n.t('user.components.user-group-info-table.group-time'), name: "time", align: 'center'},
			],
			list: [],
			isDisplay: false,
		}),
		mounted(){
			axios.get('/api/v1/user/getUserGroupInfo')
				.then(resp =>{
					resp.data.forEach(e =>{
						e.trafficTotal = this.dataConvertTraffic(e.traffic_up + e.traffic_down);
						e.trafficLimit = _.isNull(e.traffic_limit) ? i18n.t('terms.unlimited') : this.dataConvertTraffic(e.traffic_limit);
						e.timeFormatted =  _.isNull(e.expire_time) ? i18n.t('terms.unlimited') : e.expire_time;
					});
					this.list = resp.data;
					this.isDisplay = true;
				})
				.catch(resp =>{
					console.log(resp);
				})
		},
		methods: {
			handleSortChange ({name, order}) {
				this.list = this.list.sort((a, b) => order === 'asc' ? a[name] - b[name] : b[name] - a[name]);
			},
		}
	}
</script>

<style scoped>

</style>