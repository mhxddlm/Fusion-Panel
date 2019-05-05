<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="4" sm="12" class="sticky">
				<mu-card raised>
					<mu-card-title :title="$t('user.settings.connect.card-title-connectivity-status')" />
					<mu-card-text>

					</mu-card-text>
				</mu-card>
				<mu-card raised>
					<mu-card-title :title="$t('user.settings.connect.card-title-connectivity-reminder')" />
					<mu-card-text v-html="$t('user.settings.connect.text-connectivity-reminder')">
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="8" sm="12">
				<fusion-vmess-user @moduleFinishLoading="moduleFinishLoading" />
				<fusion-shadowsocks-user @moduleFinishLoading="moduleFinishLoading" />
				<fusion-shadowsocksr-user @moduleFinishLoading="moduleFinishLoading" />
			</mu-col>
		</mu-row>
		<ell3y-loader :loaded="moduleFinishedLoadingNum" :total="3" type="circle" />
	</mu-container>
</template>

<script>
	const vmessModule = () => import('./modules/userVmessSettings.vue');
	const shadowsocksModule = () => import('./modules/userShadowsocksSettings.vue');
	const shadowsocksRModule = () => import('./modules/userShadowsocksRSettings.vue');
	const moduleLoading = () => import('../../../public/moduleLoading.vue');
	export default {
		data: ()=> ({
			modulesLoaded:{
				vmessModule: false,
				shadowsocksModule: false,
				shadowsocksRModule: false,
			}
		}),
		computed: {
			moduleFinishedLoadingNum () {
				let a = 0;
				Object.values(this.modulesLoaded).forEach(e => {
					if(e === true) {a++;}
				});
				return a;
			}
		},
		mounted() {
		},
		methods: {
			moduleFinishLoading(which){
				Object.keys(this.modulesLoaded).forEach(e => {
					if(e === which) this.modulesLoaded[e] = true;
				});
			},
		},
		components:{
			'fusion-vmess-user': vmessModule,
			'fusion-shadowsocks-user': shadowsocksModule,
			'fusion-shadowsocksr-user': shadowsocksRModule,
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
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		font-size: 15px;
		font-weight: bold;
		white-space:nowrap;
	}
</style>