<template>
	<mu-card raised>
		<mu-card-title :title="$t('user.settings.me.card-title-edit-info')" />
		<mu-card-text>
			<mu-form :model="userBasicInfo">
				<mu-form-item prop="input" :label="$t('user.settings.me.text-label-name')">
					<mu-text-field color="primary" v-model="userBasicInfo.name"></mu-text-field>
				</mu-form-item>
				<mu-row gutter>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('user.settings.me.text-label-email')">
							<mu-text-field color="primary" v-model="userBasicInfo.email"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('user.settings.me.text-label-phone')">
							<mu-text-field color="primary" v-model="userBasicInfo.phone"></mu-text-field>
						</mu-form-item>
					</mu-col>
				</mu-row>
			</mu-form>
		</mu-card-text>
		<mu-card-actions style="padding-top: -10px;">
			<mu-button color="primary" @click="saveBasicInfo()" v-loading="isSavingInfo" data-mu-loading-size="24" flat>
				<mu-icon left value="save" />{{$t('user.settings.me.button-text-save-info')}}
			</mu-button>
		</mu-card-actions>
	</mu-card>
</template>

<script>
	import md5 from 'js-md5';
	export default {
		data: ()=>({
			userBasicInfo: {},
			isSavingInfo: false,
		}),
		mounted() {
			this.retrieveUserBasicInfo();
		},
		methods: {
			retrieveUserBasicInfo(){
				axios.get('/api/v1/user/getUserBasicInfo')
					.then(resp => {
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
				axios.post('/api/v1/user/saveUserInfo', {data: this.userBasicInfo})
					.then(resp =>{
						this.isSavingInfo = false;
						if(resp.data === 1)
							this.$toast.success(i18n.t('user.settings.me.toast-save-basic-info-success'));
						else
							this.$toast.error(i18n.t('user.settings.me.toast-save-basic-info-error'));
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