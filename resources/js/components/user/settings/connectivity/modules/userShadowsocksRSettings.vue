<template>
	<mu-card raised>
		<mu-card-title :title="$t('user.settings.connect.card-title-module-default-profile', {moduleName: 'ShadowsocksR'})" />
		<mu-card-text>
			<div v-show="shadowsocksrProfile.override" class="override-text">
				{{$t('user.settings.connect.text-field-override-by-admin')}}
			</div>
			<div v-show="isEmptyProfile && !isEmptyConfig" class="override-text">
				<mu-flex justify-content="center">
					{{$t('user.settings.connect.text-field-no-connectivity-profile', {moduleName: 'ShadowsocksR'})}}
				</mu-flex>
				<mu-flex justify-content="center">
					<mu-button color="primary" @click="createUserShadowsocksRProfile()" v-loading="isCreatingProfile"
					           data-mu-loading-size="24">
						{{$t('user.settings.connect.button-text-create-module-profile', {moduleName: 'shadowsocksR'})}}
					</mu-button>
				</mu-flex>
			</div>
			<div v-show="isEmptyProfile && isEmptyConfig" class="override-text">
				<mu-flex justify-content="center">
					<div v-html="$t('user.settings.connect.text-field-no-connectivity-all', {moduleName: 'ShadowsocksR'})"></div>
				</mu-flex>
			</div>
			<mu-form :model="shadowsocksrProfile" :class="{'override': shadowsocksrProfile.override || isEmptyProfile}">
				<mu-row gutter>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('user.settings.connect.text-label-shadowsocksr-port')" color="primary">
							<mu-text-field v-model="shadowsocksrProfile.port" type="number" color="primary"
							               :min="shadowsocksrConfig.port_start" :max="shadowsocksrConfig.port_end" disabled>
							</mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="password" :label="$t('user.settings.connect.text-label-shadowsocksr-password')" color="primary">
							<mu-text-field v-model="shadowsocksrProfile.password"
							               :action-icon="visibility ? 'visibility_off' : 'visibility'"
							               :action-click="() => (visibility = !visibility)"
							               :type="visibility ? 'text' : 'password'" color="primary"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="select" :label="$t('user.settings.connect.select-label-shadowsocksr-method')" color="primary">
							<mu-select v-model="shadowsocksrProfile.method" color="primary" full-width>
								<mu-option
										v-for="option in method"
										:key="option" :label="option" :value="option"
										:disabled="!shadowsocksrConfig.method.find((e) =>{return e === option})">
								</mu-option>
							</mu-select>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="select" :label="$t('user.settings.connect.select-label-shadowsocksr-protocol')" color="primary">
							<mu-select v-model="shadowsocksrProfile.protocol" color="primary" full-width>
								<mu-option
										v-for="option in protocol"
										:key="option" :label="option" :value="option"
										:disabled="!shadowsocksrConfig.protocol.find((e) =>{return e === option})">
								</mu-option>
							</mu-select>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="select" :label="$t('user.settings.connect.select-label-shadowsocksr-obfs')" color="primary">
							<mu-select v-model="shadowsocksrProfile.obfs" color="primary" full-width>
								<mu-option
										v-for="option in obfs"
										:key="option" :label="option" :value="option"
										:disabled="!shadowsocksrConfig.obfs.find((e) =>{return e === option})">
								</mu-option>
							</mu-select>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="switch" :label="$t('user.settings.connect.switch-label-status')">
							<mu-switch color="primary" v-model="shadowsocksrProfile.status"
							           :label="$t('user.settings.connect.switch-label-status-'+
							           shadowsocksrProfile.status + '-override-' + shadowsocksrProfile.override)" :disabled="shadowsocksrProfile.override">
							</mu-switch>
						</mu-form-item>
					</mu-col>
				</mu-row>
			</mu-form>
		</mu-card-text>
		<mu-card-actions>
			<mu-button flat color="primary" :class="{'override': shadowsocksrProfile.override || isEmptyProfile}" v-loading="isUpdatingProfile"
			           data-mu-loading-size="24" @click="saveShadowsocksRProfile()" :disabled="shadowsocksrProfile.override || isEmptyProfile">
				<mu-icon left value="save" />{{$t('user.settings.connect.button-text-save-module-profile', {moduleName: 'ShadowsocksR'})}}
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
			loadCount : 0,
			isEmptyProfile: false,
			isEmptyConfig: false,
			isUpdatingProfile: false,
			isCreatingProfile: false,
			visibility: false,
			method:['none', 'aes-128-ctr', 'aes-192-ctr', 'aes-256-ctr', 'aes-128-cfb', 'aes-192-cfb',
				'aes-256-cfb', 'rc4', 'rc4-md5', 'rc4-md5-6', 'aes-128-cfb8', 'aes-192-cfb8', 'aes-256-cfb8',
				'salsa20', 'chacha20', 'xsalsa20', 'xchacha20', 'chacha20-ietf'],
			protocol: ['origin', 'verify_deflate', 'auth_sha1_v4', 'auth_aes128_md5', 'auth_aes128_sha1',
				'auth_chain_a', 'auth_chain_b', 'auth_chain_c', 'auth_chain_d', 'auth_chain_e', 'auth_chain_f',
				'auth_akarin_rand', 'auth_akarin_spec_a'],
			obfs: ['plain', 'http_simple', 'http_post', 'random_head', 'tls1.2_ticket_auth', 'tls1.2_ticket_fastauth'],
			shadowsocksrProfile: {
				status: false,
				override: false
			},
			shadowsocksrConfig: {
				method: [],
				protocol: [],
				obfs: [],
			}

		}),
		watch:{
			loadCount(){
				if (this.loadCount === 2)
					this.$emit('moduleFinishLoading', "shadowsocksRModule");
			},
			nodeID: function(newVal, oldVal){
				if(newVal !== oldVal){
					this.getShadowsocksRProfile();
					this.getShadowsocksRConfig();
				}
			}
		},
		mounted() {
			this.getShadowsocksRProfile();
			this.getShadowsocksRConfig();
		},
		methods: {
			getShadowsocksRProfile(){
				axios.get('/api/v1/user/getNodeConnectivityProfile', {
					params:{
						module: 'shadowsocksr',
						nodeID: this.nodeID,
					}})
					.then(resp =>{
						if(!_.isEmpty(resp.data)){
							resp.data[0].status = resp.data[0].status === 1;
							resp.data[0].override = resp.data[0].override === 1;
							this.shadowsocksrProfile = resp.data[0];
							this.isCreatingProfile = false;
							this.isEmptyProfile = false;
						}else{
							this.isEmptyProfile = true;
						}
						this.loadCount++;
					});

			},
			getShadowsocksRConfig(){
				axios.get('/api/v1/user/getNodeConnectivityConfig', {
					params:{
						module: 'shadowsocksr',
						nodeID: this.nodeID,
					}})
					.then(resp =>{
						if(!_.isEmpty(resp.data)){
							resp.data.status = resp.data.status === 1;
							resp.data.override = resp.data.override === 1;
							this.shadowsocksrConfig = resp.data;
							this.shadowsocksrConfig.method = this.shadowsocksrConfig.method.split(',');
							this.shadowsocksrConfig.protocol = this.shadowsocksrConfig.protocol.split(',');
							this.shadowsocksrConfig.obfs = this.shadowsocksrConfig.obfs.split(',');
							this.isEmptyConfig = false;
						}else{
							this.isEmptyConfig = true;
						}
						this.loadCount++;
					});
			},
			saveShadowsocksRProfile(){
				this.isUpdatingProfile = true;
				axios.post('/api/v1/user/updateUserConnectivityProfile',
					{
						module: 'shadowsocksr',
						nodeID: this.nodeID,
						data: this.shadowsocksrProfile
					})
					.then(resp =>{
						this.isUpdatingProfile = false;
						if(resp.data === 1){
							this.$toast.success(i18n.t('user.settings.connect.toast-save-connectivity-profile-success', {moduleName: 'ShadowsocksR'}));
							this.getShadowsocksRProfile();
						}else{
							this.$toast.error(i18n.t('user.settings.connect.toast-save-connectivity-profile-error', {moduleName: 'ShadowsocksR'}));
						}
					})
					.catch(resp =>{

					});
			},
			createUserShadowsocksRProfile(){
				this.isCreatingProfile = true;
				axios.post('/api/v1/user/createUserConnectivityProfile',
					{
						module: 'shadowsocksr',
						nodeID: this.nodeID,
					})
					.then(resp =>{
						if(resp.data !== 0){
							this.$toast.success(i18n.t('user.settings.connect.toast-create-connectivity-profile-success', {moduleName: 'ShadowsocksR'}));
							this.getShadowsocksRProfile();
						}else{
							this.$toast.error(i18n.t('user.settings.connect.toast-create-connectivity-profile-error', {moduleName: 'ShadowsocksR'}));
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