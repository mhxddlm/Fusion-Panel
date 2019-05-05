<template>
	<mu-container>
		<mu-flex justify-content="center">
			<mu-card id="main-card" style="width: 100%; max-width: 400px; margin: 0 auto;" raised>
				<mu-form ref="form" :model="userInfo" @keyup.enter="handleNext()" @keyup.tab="handleNext()" @submit.native.prevent>
					<mu-card-title :title="$t('register.card-title')"></mu-card-title>
					<mu-card-text>
						<mu-stepper :active-step="activeStep" color="primary"  orientation="vertical">
							<mu-step>
								<mu-step-label>
									{{welcomeMsg}}
								</mu-step-label>
								<mu-step-content v-if="activeStep === 0">
									<mu-form-item ref="code_sec" prop="code" :rules="codeRules">
										<mu-text-field color="primary" v-model="userInfo.code" prop="code"></mu-text-field>
									</mu-form-item>
									<p>{{$t('register.ins-step-0')}}</p>
									<mu-alert color="error" delete v-if="alert" @delete="closeAlert()">
										<mu-icon left value="warning"></mu-icon>{{alertMsg}}
									</mu-alert>
								</mu-step-content>
							</mu-step>
							<mu-step ref="email_username_sec">
								<mu-step-label>
									{{$t('register.title-step-1')}}
								</mu-step-label>
								<mu-step-content v-if="activeStep === 1">
									<mu-form-item :label="$t('terms.email')" prop="email" :rules="emailRules">
										<mu-text-field type="email" color="primary" v-model="userInfo.email" prop="email"></mu-text-field>
									</mu-form-item>
									<mu-form-item :label="$t('terms.username')" prop="name" :rules="nameRules">
										<mu-text-field type="text" color="primary" v-model="userInfo.name" prop="name"></mu-text-field>
									</mu-form-item>
									<p>{{$t('register.ins-step-1')}}</p>
									<mu-alert color="error" delete v-if="alert" @delete="closeAlert()">
										<mu-icon left value="warning"></mu-icon>{{alertMsg}}
									</mu-alert>
								</mu-step-content>
							</mu-step>
							<mu-step ref="password_sec">
								<mu-step-label>
									{{$t('register.title-step-2')}}
								</mu-step-label>
								<mu-step-content v-if="activeStep === 2">
									<mu-form-item :label="$t('terms.password')" prop="password" :rules="passwordRules">
										<mu-text-field type="password" color="primary" v-model="userInfo.password" prop="password"></mu-text-field>
									</mu-form-item>
									<mu-form-item :label="$t('register.text-label-repassword')" prop="password_confirmation" :rules="passwordRules">
										<mu-text-field type="password" color="primary" v-model="userInfo.password_confirmation" prop="password_confirmation"></mu-text-field>
									</mu-form-item>
									<p>{{$t('register.ins-step-2')}}</p>
									<mu-alert color="error" delete v-if="alert" @delete="closeAlert()">
										<mu-icon left value="warning"></mu-icon>{{alertMsg}}
									</mu-alert>
								</mu-step-content>
							</mu-step>
							<mu-step ref="agree_sec">
								<mu-step-label>
									{{$t('register.title-step-3')}}
								</mu-step-label>
								<mu-step-content v-if="activeStep === 3">
									<mu-form-item prop="isAgree" :rules="argeeRules">
										<mu-checkbox :label="$t('register.text-label-agree')" color="primary" v-model="userInfo.isAgree"></mu-checkbox>
									</mu-form-item>
									<p>{{$t('register.ins-step-3')}}</p>
								</mu-step-content>
							</mu-step>
						</mu-stepper>
					</mu-card-text>
					<mu-card-actions v-if="!finished">
						<mu-button :disabled="activeStep <= 1" @click="handlePrev" flat>{{$t('register.button-previous')}}</mu-button>
						<mu-button color="primary" v-loading="showLoading" data-mu-loading-size="24" :disabled="isServerDisabled === true || showLoading === true" @click="handleNext" flat>{{$t('register.button-next')}}</mu-button>
						<router-link :to="{name: 'authLogin'}" ><mu-button flat>{{$t('register.button-login')}}</mu-button></router-link>
					</mu-card-actions>
				</mu-form>
			</mu-card>
		</mu-flex>
	</mu-container>
</template>

<script>
	export default {
		data (){
			return {
				userInfo:{
					code: '',
					email: '',
					name: '',
					password: '',
					password_confirmation: '',
					isAgree: false
				},
				codeRules: [
					{ validate: (val) => !!val, message: i18n.t('register.text-rules-invitation-code-required')},
				],
				emailRules: [
					{ validate: (val) => !!val, message: i18n.t('register.text-rules-email-required')},
				],
				nameRules: [
					{ validate: (val) => !!val, message: i18n.t('register.text-rules-username-required')},
				],
				passwordRules: [
					{ validate: (val) => !!val, message: i18n.t('register.text-rules-password-required')},
				],
				argeeRules: [{ validate: (val) => !!val, message: i18n.t('register.text-rules-agree-required')}],
				activeStep: 0,
				isServerDisabled: false,
				alert: false,
				showLoading: false,
				welcomeMsg: i18n.t('register.title-step-0-default'),
				alertMsg: '',
			};
		},
		mounted(){
			document.getElementById('sidebar').classList.add('mu-primary-color','mu-elevation-4');
			document.getElementById('sidebar').style.backgroundColor="";
			$('.mu-card').css({"margin-top": $(window).height()/2 - $('#main-card').height()/2 - 30});
		},
		computed: {
			finished () {
				return this.activeStep > 3;
			}
		},
		methods: {
			handleNext () {
				const app = this;
				const user = app.userInfo;
				this.alert = false;
				this.$refs.form.validate().then((result) => {
					if (result === true){
						this.showLoading = true;
						switch (this.activeStep) {
							case 0:
								axios.get('/api/v1/userAuth/getCodeInfo',{
									params:{
										code: user.code,
									}})
									.then(resp =>{
										switch (resp['data']['code']) {
											case 1:
												this.welcomeMsg = i18n.t('register.title-step-0-pass-invite', {name: resp['data']['name']});
												this.activeStep++;
												break;
											case 0:
											case -1:
											case -2:
												this.alertMsg = i18n.t('register.error-invitation-code-error' + resp['data']['code']);
												this.alert = true;
										}
										this.showLoading = false;
									})
									.catch(resp =>{
										this.alertMsg = i18n.t('register.error-invitation-code-error');
										this.alert = true;
										this.showLoading = false;
										console.log(resp);
									});
								break;
							case 1:
								axios.post('/api/v1/userAuth/checkEmailUsername', user)
									.then(resp =>{
										if(resp['data']['email'] === 1 && resp['data']['name'] === 1){
											this.welcomeMsg = i18n.t('register.title-step-0-get-username', {name: user.name});
											this.activeStep++;
										}
										else{
											let temp = undefined;
											if (resp['data']['email'] !== 1 && resp['data']['name'] === 1)
												temp = i18n.t('terms.email');
											else if (resp['data']['name'] !== 1 && resp['data']['email'] === 1)
												temp = i18n.t('terms.username');
											else temp = i18n.t('register.error-email-username-error-both');
											this.alertMsg = i18n.t('register.error-email-username-error', {temp: temp});
											this.alert = true;
										}
										this.showLoading = false;
										console.log(resp);
									})
									.catch(resp =>{
										this.showLoading = false;
										console.log(resp);
									});
								break;
							case 2:
								var isOK = true;
								if(user.password !== user.password_confirmation) {
									this.alertMsg = i18n.t('register.error-password-re-not-match');
									this.alert = true;
									isOK = false;
								}
								jQuery.map([/.{7,}/, /[a-z]+/, /[0-9]+/, /[A-Z]+/], function(regexp) {
									if(!user.password.match(regexp)){
										this.alertMsg = i18n.t('register.error-password-limit-format');
										this.alert = true;
										isOK = false;
									}
								}.bind(this));

								if(isOK === true)
									this.activeStep++;
								this.showLoading = false;
								break;
							case 3:
								axios.post('/auth/register', user)
									.then(resp =>{
										this.$alert(i18n.t('register.success-dialog-text'),i18n.t('register.success-dialog-title') + user.name);
										setTimeout(() => {
											axios.get('/sys/apiToken')
												.then(resp =>{
													if(!_.isNull(resp.data) && !_.isEmpty(resp.data))
													{
														document.head.querySelector('meta[name="api-token"]').content = resp.data;
														this.$toast.success(i18n.t('login.api-success-toast-text'));
														window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + resp.data;
														this.$store.dispatch('storeUserInfo');
														setTimeout(() => {app.$router.push({name: 'userIndex'});}, 1500);
													}else{
														this.$alert(i18n.t('login.api-error-text'), i18n.t('login.error-dialog-title'));
													}
													this.showLoading = false;
												})
										}, 1000);
									})
									.catch(resp =>{
										this.alertMsg = "";
										this.alert = true;
										console.log(resp);
									})
						}
					}
					console.log(result);
				});

			},
			handlePrev () {
				if(this.activeStep > 1)
					this.activeStep--;
			},
			closeAlert () {
				this.alert = false;
			}
		}
	}
</script>

<style scoped>

</style>