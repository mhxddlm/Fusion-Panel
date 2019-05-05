<template>
	<mu-container>
		<mu-flex justify-content="center">
			<mu-card id="main-card" style="width: 100%; max-width: 375px; margin: 0 auto;" raised>
				<mu-form ref="form" :model="userInfo" @keyup.enter="submit()">
					<mu-card-title :title="loginMethod === 0 ? $t('login.card-title-login') : $t('login.card-title-nyaccount')" />
					<mu-card-text v-if="loginMethod === 0">
						<mu-flex justify-content="center">
							<mu-avatar color="primary" :size="80">
								<mu-icon value="account_circle" :size="36"></mu-icon>
							</mu-avatar>
						</mu-flex>
						<div style="height: 20px;"></div>
						<mu-form-item :label="$t('terms.email')" prop="email" :rules="emailRules">
							<mu-text-field color="primary" v-model="userInfo.email" prop="email"></mu-text-field>
						</mu-form-item>
						<mu-form-item :label="$t('terms.password')" prop="password" :rules="passwordRules">
							<mu-text-field color="primary" type="password" v-model="userInfo.password" prop="password"></mu-text-field>
						</mu-form-item>
					</mu-card-text>
					<mu-card-text v-if="loginMethod === 1">
						<mu-flex justify-content="center">
							<mu-avatar color="primary" :size="80">
								<img src="/svg/NyaIcon.svg">
							</mu-avatar>
						</mu-flex>
						<mu-flex justify-content="center">
							<h5 style="font-family: 'Press Start 2P', cursive;">Nyaccount</h5>
						</mu-flex>
						<p>{{$t('login.text-nyaccount-description')}}</p>
					</mu-card-text>
					<mu-card-actions v-if="loginMethod === 0">
						<mu-button color="primary" v-loading="showLoading" data-mu-loading-size="24" @click="submit" flat>{{$t('login.button-login')}}</mu-button>
						<mu-button flat>{{ $t('login.button-forget-password') }}</mu-button>
					</mu-card-actions>
					<mu-card-actions v-if="loginMethod === 1">
						<mu-button full-width color="primary" @click="showAuthFrame();">{{$t('login.button-nyaccount')}}<mu-icon size="12"value="open_in_new" right></mu-icon></mu-button>
					</mu-card-actions>
				</mu-form>
			</mu-card>
		</mu-flex>
		<mu-dialog width="360" transition="slide-bottom" fullscreen :open.sync="openAuthFrame">
			<mu-appbar color="primary" title="Nyaccount CAS">
				<mu-button slot="left" icon @click="openAuthFrame = !openAuthFrame; isLoadingNyaAuth = true">
					<mu-icon value="close"></mu-icon>
				</mu-button>
				<mu-button slot="right" flat  @click="openAuthFrame = !openAuthFrame; isLoadingNyaAuth = true">
					Done
				</mu-button>
			</mu-appbar>
			<div style="padding: 10px;" v-loading="isLoadingNyaAuth">
				<iframe src="/auth/Nyaccount" id="authIframe" width="100%" frameborder="no" border="0" scrolling="no" allowtransparency="yes" style="min-height: 700px;" ></iframe>
			</div>
		</mu-dialog>
	</mu-container>
</template>

<script>
	export default {
		data () {
			return {
				emailRules: [
					{ validate: (val) => !!val, message: i18n.t('login.text-rules-email-required')},
				],
				passwordRules: [
					{ validate: (val) => !!val, message: i18n.t('login.text-rules-password-required')},
					{ validate: (val) => val.length >= 7, message: i18n.t('login.text-rules-password-length')}
				],
				userInfo: {
					email: '',
					password: '',
				},
				showLoading: false,
				isLoadingNyaAuth: true,
				loginMethod: 0,
				openAuthFrame: false
			}
		},
		mounted(){
			document.getElementById('sidebar').classList.add('mu-primary-color','mu-elevation-4');
			document.getElementById('sidebar').style.backgroundColor="";
			$('.mu-card').css({"margin-top": $(window).height()/2 - $('#main-card').height()/2 - 30});
		},
		methods: {
			submit () {
				this.$refs.form.validate().then((result) => {
					if(result){
						this.showLoading = true;
						var app = this;
						var user = app.userInfo;
						axios.post('/auth/login', user)
							.then(resp =>{
								console.log(resp.data);
								this.$toast.info(i18n.t('login.success-toast-text'));
								setTimeout(() => {
									axios.get('/sys/apiToken')
										.then(resp =>{
											if(!_.isNull(resp.data) && !_.isEmpty(resp.data))
											{
												document.head.querySelector('meta[name="api-token"]').content = resp.data;
												this.$toast.success(i18n.t('login.api-success-toast-text'));
												window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + resp.data;
												this.$store.dispatch('storeUserInfo');
												setTimeout(() => {app.$router.push({name: 'userIndex'});}, 2000);
											}else{
												this.$alert(i18n.t('login.api-error-text'), i18n.t('login.error-dialog-title'));
											}
											this.showLoading = false;
										})
								}, 1000);
							})
							.catch(resp => {
								switch(resp.response.status){
									case 422:
										this.$alert(i18n.t('login.error-entity-none-dialog-text'), i18n.t('login.error-dialog-title'));
										break;
									default:
										this.$alert(i18n.t('login.error-dialog-text'), i18n.t('login.error-dialog-title'));
								}
								this.showLoading = false;
							})
					}
				});
			},
			resize(){
				$('.mu-card').css({"margin-top": $(window).height()/2 - $('#main-card').height()/2 - 30});
			},
			showAuthFrame(){
				this.openAuthFrame = !this.openAuthFrame;
				setTimeout(() => {this.isLoadingNyaAuth = false;}, 2500);
			}
		}
	};
</script>

<style scoped>
	@import url('https://fonts.googleapis.com/css?family=Press+Start+2P');
</style>