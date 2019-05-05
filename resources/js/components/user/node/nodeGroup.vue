<template>
	<mu-expansion-panel :zDepth="8"  :expand="isExpand" style="border-radius: 7px; margin-top: 20px;">
		<div slot="header" style="margin-top: 20px;margin-bottom: 20px;">
			<div style="font-size: 20px; font-weight: bold;">{{groupInfo.name}}</div>
			<div style="font-size: 13px; font-weight: 400;"v-html="groupInfo.description"></div>
		</div>
		<mu-row gutter>
			<mu-col span="12" lg="8" md="8" sm="12">
				<mu-flex justify-content="center">
					<la-cartesian :height="75" :data="groupInfo.status" autoresize>
						<la-line curve animated :width="2" prop="value" color="url(#color-id)"></la-line>
						<defs>
							<linearGradient id="color-id" x1="0" y1="0" x2="0" y2="1">
								<stop offset="0" stop-color="#512DA8"></stop>
								<stop offset="1" stop-color="#E040FB"></stop>
							</linearGradient>
						</defs>
					</la-cartesian>
				</mu-flex>
			</mu-col>
			<mu-col span="12" lg="6" md="6" sm="12">
			</mu-col>
		</mu-row>
		<mu-divider style="margin-top: 20px;"/>
		<fusion-node-in-group :gid="groupID" :showDesktopNodeDetail.sync="showDesktopNodeDetail"
		                      @showNodeDetail="showNodeDetail" />
	</mu-expansion-panel>
</template>

<script>

	const nodeInGroup = () => import('./nodeInGroup.vue');
	export default {
		props:{
			groupID: Number,
			showDesktopNodeDetail: Boolean
		},
		data: ()=> ({
			isExpand: true,
			groupInfo: {}
		}),
		mounted(){
			this.getGroupInfo();
		},
		methods: {
			getGroupInfo(){
				axios.get('/api/v1/user/getGroupInfo',{
					params:{
						id: this.groupID
					}})
					.then(resp =>{
						this.groupInfo = resp.data;
					})
					.catch(resp =>{
						console.log(resp);
					});
			},
			showNodeDetail(id){
				this.$emit('showNodeDetail', id);
			}
		},
		components: {
			'fusion-node-in-group': nodeInGroup,
		}
	}
</script>

<style scoped>

</style>