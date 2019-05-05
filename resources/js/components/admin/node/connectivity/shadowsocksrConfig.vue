<template>
	<mu-expansion-panel :zDepth="8" style="border-radius: 10px; margin-top: 20px;" :expand="isLoaded">
		<div slot="header" style="margin-top: 20px;margin-bottom: 20px;">
			<div style="font-size: 20px; font-weight: bold;">{{$t('admin.node.connect.expansion-title-module-config', {moduleName: "Shadowsocksr"})}}</div>
			<div style="font-size: 13px; font-weight: 400;"v-html="$t('admin.node.connect.expansion-title-module-shadowsocksr-subtitle')"></div>
		</div>
		<div v-show="isEmptyConfig" class="override-text">
			<mu-flex justify-content="center">
				{{$t('admin.node.connect.text-field-no-connectivity-config', {moduleName: 'Shadowsocksr'})}}
			</mu-flex>
			<mu-flex justify-content="center">
				<mu-button color="primary" @click="createShadowsocksrConfig()" v-loading="isCreatingConfig"
				           data-mu-loading-size="24">
					{{$t('admin.node.connect.button-text-create-module-config', {moduleName: 'Shadowsocksr'})}}
				</mu-button>
			</mu-flex>
		</div>
		<mu-form :model="shadowsocksrConfig" :class="{'override': isEmptyConfig}">

			<mu-row gutter>
				<mu-col span="12" lg="6" md="6" sm="12">
					<mu-form-item prop="input" :label="$t('admin.node.connect.text-label-shadowsocksr-port-start')" color="primary">
						<mu-text-field v-model="shadowsocksrConfig.port_start" color="primary" type="number"></mu-text-field>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="6" md="6" sm="12">
					<mu-form-item prop="input" :label="$t('admin.node.connect.text-label-shadowsocksr-port-end')" color="primary">
						<mu-text-field v-model="shadowsocksrConfig.port_end" color="primary" type="number"></mu-text-field>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="6" md="6" sm="12">
					<mu-form-item prop="select" :label="$t('admin.node.connect.select-label-shadowsocksr-method')" color="primary">
						<mu-select multiple v-model="shadowsocksrConfig.method" color="primary" full-width>
							<mu-option v-for="option in method" :key="option" :label="option" :value="option">
							</mu-option>
						</mu-select>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="6" md="6" sm="12">
					<mu-form-item prop="select" :label="$t('admin.node.connect.select-label-shadowsocksr-protocol')" color="primary">
						<mu-select multiple v-model="shadowsocksrConfig.protocol" color="primary" full-width>
							<mu-option v-for="option in protocol" :key="option" :label="option" :value="option">
							</mu-option>
						</mu-select>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="6" md="6" sm="12">
					<mu-form-item prop="select" :label="$t('admin.node.connect.select-label-shadowsocksr-ob')" color="primary">
						<mu-select multiple v-model="shadowsocksrConfig.obfs" color="primary" full-width>
							<mu-option v-for="option in obfs" :key="option" :label="option" :value="option">
							</mu-option>
						</mu-select>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="3" md="3" sm="6">
					<mu-form-item prop="switch" :label="$t('admin.node.connect.switch-label-status')">
						<mu-switch color="primary" v-model="shadowsocksrConfig.status"
						           :label="$t('admin.node.connect.switch-label-status-' + shadowsocksrConfig.status)">
						</mu-switch>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="3" md="3" sm="6">
					<mu-form-item prop="switch" :label="$t('admin.node.connect.switch-label-override')">
						<mu-switch color="primary" v-model="shadowsocksrConfig.override"
						           :label="$t('admin.node.connect.switch-label-override-' + shadowsocksrConfig.override)">
						</mu-switch>
					</mu-form-item>
				</mu-col>
			</mu-row>
		</mu-form>
		<mu-button slot="action" flat color="primary" :class="{'override': isEmptyConfig}" v-loading="isUpdatingConfig"
		           data-mu-loading-size="24" @click="saveShadowsocksrConfig()" :disabled="isEmptyConfig">
			<mu-icon left value="save" />{{$t('admin.node.connect.button-text-save-module-config', {moduleName: 'Shadowsocksr'})}}
		</mu-button>
	</mu-expansion-panel>
</template>

<script>
	export default {
		props:{
			nodeID: Number,
		},
		data: ()=> ({
			isLoaded: false,
			isEmptyConfig: false,
			isUpdatingConfig: false,
			isCreatingConfig: false,
			method:['none', 'aes-128-ctr', 'aes-192-ctr', 'aes-256-ctr', 'aes-128-cfb', 'aes-192-cfb',
				'aes-256-cfb', 'rc4', 'rc4-md5', 'rc4-md5-6', 'aes-128-cfb8', 'aes-192-cfb8', 'aes-256-cfb8',
				'salsa20', 'chacha20', 'xsalsa20', 'xchacha20', 'chacha20-ietf'],
			protocol: ['origin', 'verify_deflate', 'auth_sha1_v4', 'auth_aes128_md5', 'auth_aes128_sha1',
				'auth_chain_a', 'auth_chain_b', 'auth_chain_c', 'auth_chain_d', 'auth_chain_e', 'auth_chain_f',
				'auth_akarin_rand', 'auth_akarin_spec_a'],
			obfs: ['plain', 'http_simple', 'http_post', 'random_head', 'tls1.2_ticket_auth', 'tls1.2_ticket_fastauth'],
			shadowsocksrConfig: {
				status: false,
				override: false
			}
		}),
		mounted(){
			this.getShadowsocksrConfig();
		},
		methods: {
			getShadowsocksrConfig(){
				axios.get('/api/v1/admin/getNodeConnectivityConfig', {
					params:{
						module: 'shadowsocksr',
						nodeID: this.nodeID,
					}})
					.then(resp =>{
						if(!_.isEmpty(resp.data)){
							resp.data.status = resp.data.status === 1;
							resp.data.override = resp.data.override === 1;
							resp.data.method = resp.data.method.split(',');
							resp.data.protocol = resp.data.protocol.split(',');
							resp.data.obfs = resp.data.obfs.split(',');
							this.shadowsocksrConfig = resp.data;
							this.isCreatingConfig = false;
							this.isEmptyConfig = false;
						}else{
							this.isEmptyConfig = true;
						}
						this.$emit('moduleFinishLoading', "shadowsocksRModule");
						this.isLoaded = true;
					});
			},
			saveShadowsocksrConfig(){
				this.isUpdatingConfig = true;
				axios.post('/api/v1/admin/updateNodeConnectivityConfig',
					{
						module: 'shadowsocksr',
						nodeID: this.nodeID,
						data: this.shadowsocksrConfig
					})
					.then(resp =>{
						this.isUpdatingConfig = false;
						if(resp.data === 1){
							this.$toast.success(i18n.t('admin.node.connect.toast-save-connectivity-config-success', {moduleName: 'ShadowsocksR'}));
							this.getShadowsocksrConfig();
						}else{
							this.$toast.error(i18n.t('admin.node.connect.toast-save-connectivity-config-error', {moduleName: 'ShadowsocksR'}));
						}

					})
					.catch(resp =>{

					});
			},
			createShadowsocksrConfig(){
				this.isCreatingConfig = true;
				axios.post('/api/v1/admin/createNodeConnectivityConfig',
					{
						module: 'shadowsocksr',
						nodeID: this.nodeID,
					})
					.then(resp =>{
						if(resp.data !== 0){
							this.$toast.success(i18n.t('admin.node.connect.toast-create-connectivity-config-success', {moduleName: 'ShadowsocksR'}));
							this.getShadowsocksrConfig();
						}else{
							this.$toast.error(i18n.t('admin.node.connect.toast-create-connectivity-config-error', {moduleName: 'ShadowsocksR'}));
						}

					})
					.catch(resp =>{

					});
			}
		}
	}
</script>

<style scoped>

</style>