<template>
	<div>
		<mu-card raised>
			<mu-card-text>
				<mu-flex justify-content="center">
					<h2>{{nodeInfo.name}}</h2>
				</mu-flex>
				<mu-flex justify-content="center">
					<p style="font-weight: 400">{{nodeInfo.address}}</p>
				</mu-flex>
			</mu-card-text>
		</mu-card>
		<mu-card raised>
			<mu-card-title :title="$t('user.node.detail.card-title-node-info')" />
			<mu-card-text>
				<div v-html="nodeInfo.description"></div>
				<mu-divider />
			</mu-card-text>
		</mu-card>
		<div v-if="acceptedNodeID !== 0">
			<fusion-vmess-user :nodeID="acceptedNodeID" />
			<fusion-shadowsocks-user :nodeID="acceptedNodeID" />
			<fusion-shadowsocksr-user :nodeID="acceptedNodeID" />
		</div>
	</div>
</template>

<script>
	const vmessModule = () => import('../settings/connectivity/modules/userVmessSettings.vue');
	const shadowsocksModule = () => import('../settings/connectivity/modules/userShadowsocksSettings.vue');
	const shadowsocksRModule = () => import('../settings/connectivity/modules/userShadowsocksRSettings.vue');
	export default {
		props:{
			nodeID: {
				type: Number,
				default: 0,
			},
		},
		data: () => ({
			nodeInfo:{

			},
			nodeParamID: undefined,
		}),
		watch:{
			acceptedNodeID: function(newVal, oldVal){
				if(newVal !== oldVal && newVal !== 0){
					this.getNodeInfo();
				}

			}
		},
		computed:{
			acceptedNodeID(){
				return this.nodeID === 0 ? this.nodeParamID : this.nodeID;
			}
		},
		beforeMount() {
			this.nodeParamID = this.nodeID === 0 ? _.toSafeInteger(this.$route.params.id) : this.nodeID;
		},
		mounted(){

		},
		methods: {
			getNodeInfo(){
				axios.get('/api/v1/user/getNodeInfo',{
					params:{
						nodeID: this.acceptedNodeID
					}})
					.then(resp =>{
						this.nodeInfo = resp.data;
					})
					.catch(resp =>{
						console.log(resp);
					})
			}
		},
		components:{
			'fusion-vmess-user': vmessModule,
			'fusion-shadowsocks-user': shadowsocksModule,
			'fusion-shadowsocksr-user': shadowsocksRModule,
		}
	}
</script>

<style lang="less" scoped>
	/deep/ .override{
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-moz-user-select: none;
		user-select: none;
		filter: blur(5px);
		pointer-events: none;
		transition:all 0.3s ease;
	}
	/deep/ .override-text{
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-moz-user-select: none;
		user-select: none;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		font-size: 15px;
		font-weight: bold;
		white-space:nowrap;
	}
</style>