<template>
	<mu-card raised>
		<mu-card-title :title="$t('user.settings.connect.card-title-module-default-profile', {moduleName: 'Shadowsocks'})" />
		<mu-card-text>
			<div v-show="shadowsocksProfile.override" class="override-text">
				{{$t('user.settings.connect.text-field-override-by-admin')}}
			</div>
			<div v-show="isEmptyProfile && !isEmptyConfig" class="override-text">
				<mu-flex justify-content="center">
					{{$t('user.settings.connect.text-field-no-connectivity-profile', {moduleName: 'Shadowsocks'})}}
				</mu-flex>
				<mu-flex justify-content="center">
					<mu-button color="primary" @click="createUserShadowsocksProfile()" v-loading="isCreatingProfile"
					           data-mu-loading-size="24">
						{{$t('user.settings.connect.button-text-create-module-profile', {moduleName: 'Shadowsocks'})}}
					</mu-button>
				</mu-flex>
			</div>
			<div v-show="isEmptyProfile && isEmptyConfig" class="override-text">
				<mu-flex justify-content="center">
					<div v-html="$t('user.settings.connect.text-field-no-connectivity-all', {moduleName: 'Shadowsocks'})"></div>
				</mu-flex>
			</div>
			<mu-form :model="shadowsocksProfile" :class="{'override': shadowsocksProfile.override || isEmptyProfile}">
				<mu-row gutter>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('user.settings.connect.text-label-shadowsocks-port')" color="primary">
							<mu-text-field v-model="shadowsocksProfile.port" type="number" color="primary"
							               :min="shadowsocksConfig.port_start"
							               :max="shadowsocksConfig.port_end" disabled></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="password" :label="$t('user.settings.connect.text-label-shadowsocks-password')" color="primary">
							<mu-text-field v-model="shadowsocksProfile.password" color="primary"
							               :action-icon="visibility ? 'visibility_off' : 'visibility'"
							               :action-click="() => (visibility = !visibility)"
							               :type="visibility ? 'text' : 'password'"></mu-text-field>
						</mu-form-item>
					</mu-col>
				</mu-row>

				<mu-row gutter>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="select" :label="$t('user.settings.connect.select-label-shadowsocks-encryption')" color="primary">
							<mu-select  v-model="shadowsocksProfile.encryption" full-width>
								<mu-option color="primary"
										v-for="option in shadowsocksEncryptions"
										:key="option" :label="option" :value="option"
										:disabled="!shadowsocksConfig.encryption.find((e) =>{return e === option})">
								</mu-option>
							</mu-select>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="switch" :label="$t('user.settings.connect.switch-label-status')" color="primary">
							<mu-switch color="primary" v-model="shadowsocksProfile.status"
							           :label="$t('user.settings.connect.switch-label-status-'+
							           shadowsocksProfile.status + '-override-' + shadowsocksProfile.override)" :disabled="shadowsocksProfile.override">
							</mu-switch>
						</mu-form-item>
					</mu-col>
				</mu-row>
			</mu-form>
		</mu-card-text>
		<mu-card-actions>
			<mu-button flat color="primary" :class="{'override': shadowsocksProfile.override || isEmptyProfile}" v-loading="isUpdatingProfile"
			           data-mu-loading-size="24" @click="saveShadowsocksProfile()" :disabled="shadowsocksProfile.override || isEmptyProfile">
				<mu-icon left value="save" />{{$t('user.settings.connect.button-text-save-module-profile', {moduleName: 'Shadowsocks'})}}
			</mu-button>
		</mu-card-actions>
	</mu-card>
</template>

<script>
	export default {
		props:{
			nodeID:{
				type: Number,
				default: 0
			}
		},
		data: ()=> ({
			loadCount: 0,
			isEmptyProfile: false,
			isEmptyConfig: false,
			isUpdatingProfile: false,
			isCreatingProfile: false,
			visibility: false,
			shadowsocksEncryptions: ['aes-128-gcm', 'aes-192-gcm', 'aes-256-gcm', 'aes-128-cfb', 'aes-192-cfb',
				'aes-256-cfb', 'aes-128-ctr', 'aes-192-ctr', 'aes-256-ctr', 'camellia-128-cfb',
				'camellia-192-cfb', 'camellia-256-cfb', 'bf-cfb', 'chacha20-ietf-poly1305', 'salsa20',
				'chacha20', 'chacha20-ietf', 'rc4-md5'],
			shadowsocksProfile: {
				status: false,
				override: false
			},
			shadowsocksConfig: {
				encryption: []
			}

		}),
		watch: {
			loadCount(){
				if (this.loadCount === 2)
					this.$emit('moduleFinishLoading', "shadowsocksModule");
			},
			nodeID: function(newVal, oldVal){
				if(newVal !== oldVal){
					this.getShadowsocksProfile();
					this.getShadowsocksConfig();
				}
			}
		},
		mounted() {
			this.getShadowsocksProfile();
			this.getShadowsocksConfig();
		},
		methods: {
			getShadowsocksProfile(){
				axios.get('/api/v1/user/getNodeConnectivityProfile', {
					params:{
						module: 'shadowsocks',
						nodeID: this.nodeID,
					}})
					.then(resp =>{
						if(!_.isEmpty(resp.data)){
							resp.data[0].status = resp.data[0].status === 1;
							resp.data[0].override = resp.data[0].override === 1;
							this.shadowsocksProfile = resp.data[0];
							this.isCreatingProfile = false;
							this.isEmptyProfile = false;
						}else{
							this.isEmptyProfile = true;
						}
						this.loadCount++;
					});

			},
			getShadowsocksConfig(){
				axios.get('/api/v1/user/getNodeConnectivityConfig', {
					params:{
						module: 'shadowsocks',
						nodeID: this.nodeID,
					}})
					.then(resp =>{
						if(!_.isEmpty(resp.data)) {
							resp.data.status = resp.data.status === 1;
							resp.data.override = resp.data.override === 1;
							this.shadowsocksConfig = resp.data;
							this.shadowsocksConfig.encryption = this.shadowsocksConfig.encryption.split(',');
							this.isEmptyConfig = false;
						}else{
							this.isEmptyConfig = true;
						}
						this.loadCount++;
					});
			},
			saveShadowsocksProfile(){
				this.isUpdatingProfile = true;
				axios.post('/api/v1/user/updateUserConnectivityProfile',
					{
						module: 'shadowsocks',
						nodeID: this.nodeID,
						data: this.shadowsocksProfile
					})
					.then(resp =>{
						this.isUpdatingProfile = false;
						if(resp.data === 1){
							this.$toast.success(i18n.t('user.settings.connect.toast-save-connectivity-profile-success', {moduleName: 'Shadowsocks'}));
							this.getShadowsocksProfile();
						}else{
							this.$toast.error(i18n.t('user.settings.connect.toast-save-connectivity-profile-error', {moduleName: 'Shadowsocks'}));
						}
					})
					.catch(resp => {

					});
			},
			createUserShadowsocksProfile(){
				this.isCreatingProfile = true;
				axios.post('/api/v1/user/createUserConnectivityProfile',
					{
						module: 'shadowsocks',
						nodeID: this.nodeID,
					})
					.then(resp =>{
						if(resp.data !== 0){
							this.$toast.success(i18n.t('user.settings.connect.toast-create-connectivity-profile-success', {moduleName: 'Shadowsocks'}));
							this.getShadowsocksProfile();
						}else{
							this.$toast.error(i18n.t('user.settings.connect.toast-create-connectivity-profile-error', {moduleName: 'Shadowsocks'}));
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