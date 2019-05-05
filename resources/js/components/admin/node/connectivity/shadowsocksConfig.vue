<template>
	<mu-expansion-panel :zDepth="8" style="border-radius: 10px; margin-top: 20px;" :expand="isLoaded">
		<div slot="header" style="margin-top: 20px;margin-bottom: 20px;">
			<div style="font-size: 20px; font-weight: bold;">{{$t('admin.node.connect.expansion-title-module-config', {moduleName: "Shadowsocks"})}}</div>
			<div style="font-size: 13px; font-weight: 400;"v-html="$t('admin.node.connect.expansion-title-module-shadowsocks-subtitle')"></div>
		</div>
		<div v-show="isEmptyConfig" class="override-text">
			<mu-flex justify-content="center">
				{{$t('admin.node.connect.text-field-no-connectivity-config', {moduleName: 'Shadowsocks'})}}
			</mu-flex>
			<mu-flex justify-content="center">
				<mu-button color="primary" @click="createShadowsocksConfig()" v-loading="isCreatingConfig"
				           data-mu-loading-size="24">
					{{$t('admin.node.connect.button-text-create-module-config', {moduleName: 'Shadowsocks'})}}
				</mu-button>
			</mu-flex>
		</div>
		<mu-form :model="shadowsocksConfig" :class="{'override': isEmptyConfig}">

			<mu-row gutter>
				<mu-col span="12" lg="6" md="6" sm="12">
					<mu-form-item prop="input" :label="$t('admin.node.connect.text-label-shadowsocks-port-start')" color="primary">
						<mu-text-field v-model="shadowsocksConfig.port_start" color="primary" type="number"></mu-text-field>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="6" md="6" sm="12">
					<mu-form-item prop="input" :label="$t('admin.node.connect.text-label-shadowsocks-port-end')" color="primary">
						<mu-text-field v-model="shadowsocksConfig.port_end" color="primary" type="number"></mu-text-field>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="6" md="6" sm="12">
					<mu-form-item prop="select" :label="$t('admin.node.connect.select-label-shadowsocks-encryption')" color="primary">
						<mu-select multiple v-model="shadowsocksConfig.encryption" color="primary" full-width>
							<mu-option v-for="option in shadowsocksEncryptions" :key="option" :label="option" :value="option">
							</mu-option>
						</mu-select>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="3" md="3" sm="6">
					<mu-form-item prop="switch" :label="$t('admin.node.connect.switch-label-status')">
						<mu-switch color="primary" v-model="shadowsocksConfig.status"
						           :label="$t('admin.node.connect.switch-label-status-' + shadowsocksConfig.status)">
						</mu-switch>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="3" md="3" sm="6">
					<mu-form-item prop="switch" :label="$t('admin.node.connect.switch-label-override')">
						<mu-switch color="primary" v-model="shadowsocksConfig.override"
						           :label="$t('admin.node.connect.switch-label-override-' + shadowsocksConfig.override)">
						</mu-switch>
					</mu-form-item>
				</mu-col>
			</mu-row>
		</mu-form>
		<mu-button slot="action" flat color="primary" :class="{'override': isEmptyConfig}" v-loading="isUpdatingConfig"
		           data-mu-loading-size="24" @click="saveShadowsocksConfig()" :disabled="isEmptyConfig">
			<mu-icon left value="save" />{{$t('admin.node.connect.button-text-save-module-config', {moduleName: 'Shadowsocks'})}}
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
			shadowsocksEncryptions: ['aes-128-gcm', 'aes-192-gcm', 'aes-256-gcm', 'aes-128-cfb', 'aes-192-cfb',
				'aes-256-cfb', 'aes-128-ctr', 'aes-192-ctr', 'aes-256-ctr', 'camellia-128-cfb',
				'camellia-192-cfb', 'camellia-256-cfb', 'bf-cfb', 'chacha20-ietf-poly1305', 'salsa20',
				'chacha20', 'chacha20-ietf', 'rc4-md5'],
			shadowsocksConfig: {
				status: false,
				override: false
			}
		}),
		mounted(){
			this.getShadowsocksConfig();
		},
		methods: {
			getShadowsocksConfig(){
				axios.get('/api/v1/admin/getNodeConnectivityConfig', {
					params:{
						module: 'shadowsocks',
						nodeID: this.nodeID,
					}})
					.then(resp =>{
						if(!_.isEmpty(resp.data)){
							resp.data.status = resp.data.status === 1;
							resp.data.override = resp.data.override === 1;
							resp.data.encryption = resp.data.encryption.split(',');
							this.shadowsocksConfig = resp.data;
							this.isCreatingConfig = false;
							this.isEmptyConfig = false;

						}else{
							this.isEmptyConfig = true;
						}
						this.$emit('moduleFinishLoading', "shadowsocksModule");
						this.isLoaded = true;
					});
			},
			saveShadowsocksConfig(){
				this.isUpdatingConfig = true;
				axios.post('/api/v1/admin/updateNodeConnectivityConfig',
					{
						module: 'shadowsocks',
						nodeID: this.nodeID,
						data: this.shadowsocksConfig
					})
					.then(resp =>{
						this.isUpdatingConfig = false;
						if(resp.data === 1){
							this.$toast.success(i18n.t('admin.node.connect.toast-save-connectivity-config-success', {moduleName: 'Shadowsocks'}));
							this.getShadowsocksConfig();
						}else{
							this.$toast.error(i18n.t('admin.node.connect.toast-save-connectivity-config-error', {moduleName: 'Shadowsocks'}));
						}

					})
					.catch(resp =>{

					});
			},
			createShadowsocksConfig(){
				this.isCreatingConfig = true;
				axios.post('/api/v1/admin/createNodeConnectivityConfig',
					{
						module: 'shadowsocks',
						nodeID: this.nodeID,
					})
					.then(resp =>{
						if(resp.data !== 0){
							this.$toast.success(i18n.t('admin.node.connect.toast-create-connectivity-config-success', {moduleName: 'Shadowsocks'}));
							this.getShadowsocksConfig();
						}else{
							this.$toast.error(i18n.t('admin.node.connect.toast-create-connectivity-config-error', {moduleName: 'Shadowsocks'}));
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