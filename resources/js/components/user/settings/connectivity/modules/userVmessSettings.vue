<template>
	<mu-card raised>
		<mu-card-title :title="$t('user.settings.connect.card-title-module-default-profile', {moduleName: 'Vmess'})" />
		<mu-card-text>
			<div v-show="vmessProfile.override" class="override-text">
				{{$t('user.settings.connect.text-field-override-by-admin')}}
			</div>
			<div v-show="isEmptyProfile && !isEmptyConfig" class="override-text">
				<mu-flex justify-content="center">
					{{$t('user.settings.connect.text-field-no-connectivity-profile', {moduleName: 'Vmess'})}}
				</mu-flex>
				<mu-flex justify-content="center">
					<mu-button color="primary" @click="createUserVmessProfile()" v-loading="isCreatingProfile"
					           data-mu-loading-size="24">
						{{$t('user.settings.connect.button-text-create-module-profile', {moduleName: 'Vmess'})}}
					</mu-button>
				</mu-flex>
			</div>
			<div v-show="isEmptyProfile && isEmptyConfig" class="override-text">
				<mu-flex justify-content="center">
					<div v-html="$t('user.settings.connect.text-field-no-connectivity-all', {moduleName: 'Vmess'})"></div>
				</mu-flex>
			</div>
			<mu-form :model="vmessProfile" :class="{'override': vmessProfile.override || isEmptyProfile}">
				<mu-form-item prop="input" :label="$t('user.settings.connect.text-label-vmess-uuid')" color="primary">
					<mu-text-field v-model="vmessProfile.uuid" color="primary"disabled></mu-text-field>
				</mu-form-item>
				<mu-row gutter>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="select" :label="$t('user.settings.connect.select-label-vmess-encryption')" color="primary">
							<mu-select  v-model="vmessProfile.encryption" color="primary" full-width>
								<mu-option
										v-for="option in vmessEncryptions"
										:key="option" :label="option" :value="option"
										:disabled="option !== vmessConfig.encryption">
								</mu-option>
							</mu-select>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="switch" :label="$t('user.settings.connect.switch-label-status')">
							<mu-switch color="primary" v-model="vmessProfile.status"
							           :label="$t('user.settings.connect.switch-label-status-'+
							           vmessProfile.status + '-override-' + vmessProfile.override)" :disabled="vmessProfile.override">
							</mu-switch>
						</mu-form-item>
					</mu-col>
				</mu-row>
			</mu-form>
		</mu-card-text>
		<mu-card-actions>
			<mu-button flat color="primary" :class="{'override': vmessProfile.override || isEmptyProfile}" v-loading="isUpdatingProfile"
			           data-mu-loading-size="24" @click="saveVmessProfile()" :disabled="vmessProfile.override || isEmptyProfile">
				<mu-icon left value="save" />{{$t('user.settings.connect.button-text-save-module-profile', {moduleName: 'Vmess'})}}
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
			vmessEncryptions:['auto', 'aes-128-cfb', 'aes-128-gcm', 'chacha20-ietf', 'none'],
			vmessProfile: {
				status: false,
				override: false,
			},
			vmessConfig: {}

		}),
		watch:{
			loadCount(){
				if (this.loadCount ===2)
					this.$emit('moduleFinishLoading', "vmessModule");
			},
			nodeID: function(newVal, oldVal){
				if(newVal !== oldVal){
					this.getVmessProfile();
					this.getVmessConfig();
				}
			}
		},
		mounted() {
			this.getVmessProfile();
			this.getVmessConfig();
		},
		methods: {
			getVmessProfile(){
				axios.get('/api/v1/user/getNodeConnectivityProfile', {
					params:{
						module: 'vmess',
						nodeID: this.nodeID,
					}})
					.then(resp =>{
						if(!_.isEmpty(resp.data)){
							resp.data[0].status = resp.data[0].status === 1;
							resp.data[0].override = resp.data[0].override === 1;
							this.vmessProfile = resp.data[0];
							this.isCreatingProfile = false;
							this.isEmptyProfile = false;
						}else{
							this.isEmptyProfile = true;
						}
						this.loadCount++;
					});

			},
			getVmessConfig(){
				axios.get('/api/v1/user/getNodeConnectivityConfig', {
					params:{
						module: 'vmess',
						nodeID: this.nodeID,
					}})
					.then(resp =>{
						if(!_.isEmpty(resp.data)) {
							resp.data.status = resp.data.status === 1;
							resp.data.override = resp.data.override === 1;
							this.vmessConfig = resp.data;
							this.isEmptyConfig = false;
						}else{
							this.isEmptyConfig = true;
						}
						this.loadCount++;
					});
			},
			saveVmessProfile(){
				this.isUpdatingProfile = true;
				axios.post('/api/v1/user/updateUserConnectivityProfile',
					{
						module: 'vmess',
						nodeID: this.nodeID,
						data: this.vmessProfile
					})
					.then(resp =>{
						this.isUpdatingProfile = false;
						if(resp.data === 1){
							this.$toast.success(i18n.t('user.settings.connect.toast-save-connectivity-profile-success', {moduleName: 'Vmess'}));
							this.getVmessProfile();
						}else{
							this.$toast.error(i18n.t('user.settings.connect.toast-save-connectivity-profile-error', {moduleName: 'Vmess'}));
						}

					})
					.catch(resp =>{

					});
			},
			createUserVmessProfile(){
				this.isCreatingProfile = true;
				axios.post('/api/v1/user/createUserConnectivityProfile',
					{
						module: 'vmess',
						nodeID: this.nodeID,
					})
					.then(resp =>{
						if(resp.data !== 0){
							this.$toast.success(i18n.t('user.settings.connect.toast-create-connectivity-profile-success', {moduleName: 'Vmess'}));
							this.getVmessProfile();
						}else{
							this.$toast.error(i18n.t('user.settings.connect.toast-create-connectivity-profile-error', {moduleName: 'Vmess'}));
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