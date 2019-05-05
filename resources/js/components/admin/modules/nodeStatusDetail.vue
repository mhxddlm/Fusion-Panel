<template>
	<mu-container>
		<mu-tabs :value.sync="pageAttributes.statusActive" inverse indicator-color="secondary" full-width>
			<mu-tab>{{$t('admin.node.edit.tab-text-cpu')}}</mu-tab>
			<mu-tab>{{$t('admin.node.edit.tab-text-load')}}</mu-tab>
			<mu-tab>{{$t('admin.node.edit.tab-text-ram')}}</mu-tab>
			<mu-tab>{{$t('admin.node.edit.tab-text-network')}}</mu-tab>
		</mu-tabs>
		<mu-flex justify-content="center">
			<la-cartesian :height="75" :data="selectedStatus" autoresize>
				<la-line curve animated :width="2" prop="value" color="url(#color-1)"></la-line>
				<la-x-axis :prop="name"></la-x-axis>
				<la-y-axis></la-y-axis>
				<defs>
					<linearGradient id="color-1" x1="0" y1="0" x2="0" y2="1">
						<stop offset="0" stop-color="#E040FB"></stop>
						<stop offset="1" stop-color="#512DA8"></stop>
					</linearGradient>
				</defs>
			</la-cartesian>
		</mu-flex>
	</mu-container>
</template>

<script>
	export default {
		props:{
			nodeID: Number,
		},
		data: ()=>({

		})
	}
</script>

<style scoped>

</style>