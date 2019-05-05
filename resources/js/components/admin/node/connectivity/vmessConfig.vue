<template>
	<mu-expansion-panel :zDepth="8" style="border-radius: 10px; margin-top: 20px;" :expand="isLoaded">
		<div slot="header" style="margin-top: 20px;margin-bottom: 20px;">
			<div style="font-size: 20px; font-weight: bold;">{{$t('admin.node.connect.expansion-title-module-config', {moduleName: "Vmess"})}}</div>
			<div style="font-size: 13px; font-weight: 400;"v-html="$t('admin.node.connect.expansion-title-module-vmess-subtitle')"></div>
		</div>
		<div v-show="isEmptyConfig" class="override-text">
			<mu-flex justify-content="center">
				{{$t('admin.node.connect.text-field-no-connectivity-config', {moduleName: 'Vmess'})}}
			</mu-flex>
			<mu-flex justify-content="center">
				<mu-button color="primary" @click="createVmessConfig()" v-loading="isCreatingConfig"
				           data-mu-loading-size="24">
					{{$t('admin.node.connect.button-text-create-module-config', {moduleName: 'Vmess'})}}
				</mu-button>
			</mu-flex>
		</div>
		<mu-form :model="vmessConfig" :class="{'override': isEmptyConfig}">
			<mu-form-item prop="input" :label="$t('admin.node.connect.text-label-vmess-port')" color="primary">
				<mu-text-field v-model="vmessConfig.port" color="primary" type="number"></mu-text-field>
			</mu-form-item>
			<mu-row gutter>
				<mu-col span="12" lg="6" md="6" sm="12">
					<mu-form-item prop="select" :label="$t('admin.node.connect.select-label-vmess-encryption')" color="primary">
						<mu-select  v-model="vmessConfig.encryption" color="primary" full-width>
							<mu-option v-for="option in vmessEncryptions" :key="option" :label="option" :value="option">
							</mu-option>
						</mu-select>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="3" md="3" sm="6">
					<mu-form-item prop="switch" :label="$t('admin.node.connect.switch-label-status')">
						<mu-switch color="primary" v-model="vmessConfig.status"
						           :label="$t('admin.node.connect.switch-label-status-' + vmessConfig.status)">
						</mu-switch>
					</mu-form-item>
				</mu-col>
				<mu-col span="12" lg="3" md="3" sm="6">
					<mu-form-item prop="switch" :label="$t('admin.node.connect.switch-label-override')">
						<mu-switch color="primary" v-model="vmessConfig.override"
						           :label="$t('admin.node.connect.switch-label-override-' + vmessConfig.override)">
						</mu-switch>
					</mu-form-item>
				</mu-col>
			</mu-row>
		</mu-form>
		<mu-button slot="action" flat color="primary" :class="{'override': isEmptyConfig}" v-loading="isUpdatingConfig"
		           data-mu-loading-size="24" @click="saveVmessConfig()" :disabled="isEmptyConfig">
			<mu-icon left value="save" />{{$t('admin.node.connect.button-text-save-module-config', {moduleName: 'Vmess'})}}
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
			vmessEncryptions:['auto', 'aes-128-cfb', 'aes-128-gcm', 'chacha20-ietf', 'none'],
			vmessConfig: {
				status: false,
				override: false
			}
		}),
		mounted(){
			this.getVmessConfig();
		},
		methods: {
			getVmessConfig(){
				axios.get('/api/v1/admin/getNodeConnectivityConfig', {
					params:{
						module: 'vmess',
						nodeID: this.nodeID,
					}})
					.then(resp =>{
						if(!_.isEmpty(resp.data)){
							resp.data.status = resp.data.status === 1;
							resp.data.override = resp.data.override === 1;
							this.vmessConfig = resp.data;
							this.isCreatingConfig = false;
							this.isEmptyConfig = false;
						}else{
							this.isEmptyConfig = true;
						}
						this.$emit('moduleFinishLoading', "vmessModule");
						this.isLoaded = true;
					});
			},
			saveVmessConfig(){
				this.isUpdatingConfig = true;
				axios.post('/api/v1/admin/updateNodeConnectivityConfig',
					{
						module: 'vmess',
						nodeID: this.nodeID,
						data: this.vmessConfig
					})
					.then(resp =>{
						this.isUpdatingConfig = false;
						if(resp.data === 1){
							this.$toast.success(i18n.t('admin.node.connect.toast-save-connectivity-config-success', {moduleName: 'Vmess'}));
							this.getVmessConfig();
						}else{
							this.$toast.error(i18n.t('admin.node.connect.toast-save-connectivity-config-error', {moduleName: 'Vmess'}));
						}

					})
					.catch(resp =>{

					});
			},
			createVmessConfig(){
				this.isCreatingConfig = true;
				axios.post('/api/v1/admin/createNodeConnectivityConfig',
					{
						module: 'vmess',
						nodeID: this.nodeID,
					})
					.then(resp =>{
						if(resp.data !== 0){
							this.$toast.success(i18n.t('admin.node.connect.toast-create-connectivity-config-success', {moduleName: 'Vmess'}));
							this.getVmessConfig();
						}else{
							this.$toast.error(i18n.t('admin.node.connect.toast-create-connectivity-config-error', {moduleName: 'Vmess'}));
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