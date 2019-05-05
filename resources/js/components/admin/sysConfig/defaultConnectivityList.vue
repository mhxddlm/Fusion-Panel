<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="4" sm="12" class="sticky">
				<mu-card raised>
					<mu-card-title :title="$t('admin.sysConfig.connectivity-default.card-title-connectivity-status')" />
					<mu-card-text>

					</mu-card-text>
				</mu-card>
				<mu-card raised>
					<mu-card-title :title="$t('admin.sysConfig.connectivity-default.card-title-instruction')" />
					<mu-card-text v-html="$t('admin.sysConfig.connectivity-default.text-connectivity-reminder')">
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="8" sm="12">
				<fusion-vmess-config :nodeID="0" @moduleFinishLoading="moduleFinishLoading" />
				<fusion-shadowsocks-config :nodeID="0" @moduleFinishLoading="moduleFinishLoading" />
				<fusion-shadowsocksr-config :nodeID="0" @moduleFinishLoading="moduleFinishLoading" />
			</mu-col>
		</mu-row>
		<ell3y-loader :loaded="moduleFinishedLoadingNum" :total="3" type="circle" />
	</mu-container>
</template>

<script>
	const nodeVmessConfig = () => import('../node/connectivity/vmessConfig.vue');
	const nodeShadowsocksConfig = () => import('../node/connectivity/shadowsocksConfig.vue');
	const nodeShadowsocksRConfig = () => import('../node/connectivity/shadowsocksrConfig.vue');
	const moduleLoading = () => import('../../public/moduleLoading.vue');
	export default {
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
		mounted() {
		},
		methods: {
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
			'ell3y-loader': moduleLoading,
		}
	}
</script>

<style lang="less" scoped>
	@media (min-width: 800px){
		.sticky{
			position: sticky;
			position: -webkit-sticky;
			top: 64px;
		}
	}
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