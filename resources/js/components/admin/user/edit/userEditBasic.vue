<template>
	<mu-card raised>
		<mu-card-title :title="$t('admin.user.edit.card-title-edit-info')" />
		<mu-card-text>
			<mu-form :model="userBasicInfo">
				<mu-form-item prop="input" :label="$t('admin.user.edit.text-label-name')">
					<mu-text-field color="primary" v-model="userBasicInfo.name"></mu-text-field>
				</mu-form-item>
				<mu-row gutter>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.user.edit.text-label-email')">
							<mu-text-field color="primary" v-model="userBasicInfo.email"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.user.edit.text-label-phone')">
							<mu-text-field color="primary" v-model="userBasicInfo.phone"></mu-text-field>
						</mu-form-item>
					</mu-col>
				</mu-row>
				<mu-row gutter>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.user.edit.text-label-speed-limit')">
							<mu-text-field color="primary" v-model="userBasicInfo.speed_limit"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.user.edit.text-label-balance')">
							<mu-text-field color="primary" v-model="userBasicInfo.balance" type="number"></mu-text-field>
						</mu-form-item>
					</mu-col>
				</mu-row>
				<mu-row gutter>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.user.edit.text-label-invite-token-remain')">
							<mu-text-field color="primary" v-model="userBasicInfo.invite_token_remain" type="number"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="switch" :label="$t('admin.user.edit.switch-label-status')">
							<mu-switch color="primary" v-model="userBasicInfo.status" :label="this.userBasicInfo.status? $t('admin.user.edit.switch-label-enable') : $t('admin.user.edit.switch-label-disable')"></mu-switch>
						</mu-form-item>
					</mu-col>
				</mu-row>
			</mu-form>
		</mu-card-text>
		<mu-card-actions style="padding-top: -10px;">
			<mu-button color="primary" @click="saveBasicInfo()" v-loading="isSavingInfo" data-mu-loading-size="24" flat>{{$t('admin.user.edit.button-text-save-info')}}</mu-button>
		</mu-card-actions>
	</mu-card>
</template>

<script>
	import md5 from 'js-md5';
	export default {
		props: {
			userID: Number,
		},
		data: ()=>({
			userBasicInfo: {},
			isSavingInfo: false,
		}),
		mounted() {
			this.retrieveUserBasicInfo();
		},
		methods: {
			retrieveUserBasicInfo(){
				axios.get('/api/v1/admin/getUserBasicInfo',{
					params:{
						id: this.userID
					}})
					.then(resp => {
						resp.data.speed_limit = resp.data.speed_limit === 0 ? i18n.t('terms.unlimited') : this.dataConvertSpeed(resp.data.speed_limit);
						this.userBasicInfo = resp.data;
						this.userBasicInfo.emailHash = md5(this.userBasicInfo.email);
						this.$emit('globalUserBasicInfo', resp.data);
						this.$emit('moduleFinishLoading', "userBasicInfo");
					})
					.catch(resp =>{

					})
			},
			saveBasicInfo(){
				this.isSavingInfo = true;
				axios.post('/api/v1/admin/updateUserBasicInfo', {data: this.userBasicInfo})
					.then(resp =>{
						this.isSavingInfo = false;
						if(resp.data === 1)
							this.$toast.success(i18n.t('admin.user.edit.toast-save-basic-info-success'));
						else
							this.$toast.error(i18n.t('admin.user.edit.toast-save-basic-info-error'));
						console.log(resp.data);
					})
					.catch(resp =>{

					})
			}
		},
	}
</script>

<style scoped>

</style>