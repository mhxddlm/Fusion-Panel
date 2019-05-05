<template>
	<div>
		<fusion-vmess-config :nodeID="this.nodeID" @moduleFinishLoading="moduleFinishLoading" />
		<fusion-shadowsocks-config :nodeID="this.nodeID" @moduleFinishLoading="moduleFinishLoading" />
		<fusion-shadowsocksr-config :nodeID="this.nodeID" @moduleFinishLoading="moduleFinishLoading" />
	</div>
</template>

<script>
	const nodeVmessConfig = () => import('../connectivity/vmessConfig.vue');
	const nodeShadowsocksConfig = () => import('../connectivity/shadowsocksConfig.vue');
	const nodeShadowsocksRConfig = () => import('../connectivity/shadowsocksrConfig.vue');
	export default {
		props: {
			nodeID: Number,
		},
		data: ()=> ({
			modulesLoading:{
				vmessModule: false,
				shadowsocksModule: false,
				shadowsocksRModule: false,
			}
		}),
		computed: {
			moduleFinishedLoadingNum () {
				let a = 0;
				Object.values(this.modulesLoading).forEach(e => {
					if(e === true) {a++;}
				});
				return a;
			}
		},
		watch:{
			moduleFinishedLoadingNum(a){
				if (this.moduleFinishedLoadingNum === 3)
					this.$emit('moduleFinishLoading', "nodeModuleInfo");
			}
		},
		mounted(){

		},
		methods:{
			moduleFinishLoading(which){
				Object.keys(this.modulesLoading).forEach(e => {
					if(e === which) this.modulesLoading[e] = true;
				});
			},
		},
		components:{
			'fusion-vmess-config': nodeVmessConfig,
			'fusion-shadowsocks-config': nodeShadowsocksConfig,
			'fusion-shadowsocksr-config': nodeShadowsocksRConfig,
		}
	}
</script>

<style scoped>
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
		margin-top: 10%;
		left: 50%;
		transform: translate(-50%, -50%);
		font-size: 15px;
		font-weight: bold;
		white-space:nowrap;
	}
	/deep/ .mu-expansion-panel-container{
		padding: 5px;
	}
</style>