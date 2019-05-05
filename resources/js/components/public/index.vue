<template>
	<mu-container>
		<mu-row gutter :class="{'index-content-intro-0': !isMobile}">
			<div>
				<div class="index-background" id="index-background"></div>
			</div>
			<mu-col span="12" lg="8" md="8" sm="12">
				<div style="height:316px; margin-top: 20px; border-radius: 5px; ">
					<mu-flex justify-content="center" style="padding-top: 71.25px">
						<mu-flex :justify-content="isMobile ? 'center' : 'end'">
							<img src="/svg/logo.svg" class="index-logo">
						</mu-flex>
						<mu-flex :justify-content="isMobile ? 'center' : 'start'" :style="isMobile ? 'width: 50%; margin-left: 20px;' : ''">
							<h1 class="index-site-name" style="font-size: 3.5em; font-family: 'Cinzel', serif; color: white" v-html="APP_NAME_HTML"></h1>
						</mu-flex>
					</mu-flex>
				</div>
			</mu-col>
			<mu-col span="12" lg="3" md="3" sm="12">
				<mu-card id="login-card" style="padding: 7px;" class="index-login-card" raised>
					<mu-card-title :title="$t('login.card-title-login')"></mu-card-title>
					<mu-card-text>
						<div v-if="loggedIn">
							<mu-flex justify-content="center" style="margin-top: 14px; margin-bottom: 33px;">
								<mu-avatar :size="80">
									<img :src="avatarUrl" onerror="this.src='/img/avatar_404.png'">
								</mu-avatar>
							</mu-flex>
							<mu-flex justify-content="center">
								<p>Welcome back, {{$store.state.userInfo.name}}</p>
							</mu-flex>
						</div>
						<mu-form v-if="!loggedIn" ref="form" :model="userInfo" @keyup.enter="submit()">
							<mu-form-item :label="$t('terms.email')" prop="email" :rules="emailRules">
								<mu-text-field color="primary" v-model="userInfo.email" prop="email"></mu-text-field>
							</mu-form-item>
							<mu-form-item :label="$t('terms.password')" prop="password" :rules="passwordRules">
								<mu-text-field color="primary" type="password" v-model="userInfo.password" prop="password"></mu-text-field>
							</mu-form-item>
						</mu-form>
					</mu-card-text>
					<mu-card-actions>
						<mu-button color="primary" v-if="loggedIn" :to="{name: 'userIndex'}" tag="button" full-width><mu-icon size="18" left value="dashboard"></mu-icon>Dashboard</mu-button>
						<mu-button color="primary" v-if="!loggedIn" v-loading="showLoading" data-mu-loading-size="24" @click="submit" full-width><mu-icon size="18" left value="lock"></mu-icon>{{$t('login.button-login')}}</mu-button>
					</mu-card-actions>
				</mu-card>
			</mu-col>
		</mu-row>
		<div class="index-content">
			<mu-card style="border-radius: 30px 30px 0px 0px !important;" raised>
				<mu-card-text class="index-content-intro-1">
					<mu-row gutter style="margin-top: 50px;">
						<mu-col span="12" lg="4" md="4" sm="12">
							<mu-flex justify-content="center">
								<mu-icon size="72" value="extension"></mu-icon>
							</mu-flex>
							<mu-flex justify-content="center">
								<h1 style="font-weight: lighter;">Friendly Extended</h1>
							</mu-flex>
						</mu-col>
						<mu-col span="12" lg="4" md="4" sm="12">
							<mu-flex justify-content="center">
								<mu-icon size="72" value="whatshot"></mu-icon>
							</mu-flex>
							<mu-flex justify-content="center">
								<h1 style="font-weight: lighter;">Meticulously Crafted</h1>
							</mu-flex>
						</mu-col>
						<mu-col span="12" lg="4" md="4" sm="12">
							<mu-flex justify-content="center">
								<mu-icon size="72" value="verified_user"></mu-icon>
							</mu-flex>
							<mu-flex justify-content="center">
								<h1 style="font-weight: lighter;">Securely Encrypted</h1>
							</mu-flex>
						</mu-col>
					</mu-row>
				</mu-card-text>
			</mu-card>
		</div>
	</mu-container>
</template>

<script>
	import md5 from 'js-md5';
	export default {
		data: ()=>({
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
			scroll: 0,
			showLoading: false,
		}),
		watch:{
			scroll(){


			}

		},
		computed: {
			avatarUrl() {
				return this.$store.state.userInfo.email == null ?
					'/img/avatar_404.png' : 'https://www.gravatar.com/avatar/' + md5(this.$store.state.userInfo.email);
			},
			loggedIn(){
				return !_.isEmpty(this.$store.state.userInfo);
			},
			isMobile(){
				return window.innerWidth < 800;
			}
		},
		mounted(){
			this.handleScale = () =>{
				document.getElementById('index-background').style.backgroundSize = (130 - window.scrollY * 0.05) + "%";
				if(window.scrollY >= 100){
					document.getElementById('sidebar').classList.add('mu-primary-color','mu-elevation-4');
					document.getElementById('sidebar').style.backgroundColor="";
				}
				else{
					document.getElementById('sidebar').classList.remove('mu-primary-color','mu-elevation-4');
					document.getElementById('sidebar').style.backgroundColor="rgba(0, 0, 0, 0)";
				}
			};
			window.addEventListener("scroll", this.handleScale);
		},
		beforeDestroy(){
			window.removeEventListener("scroll", this.handleScale);
		},
		methods:{
			submit () {
				this.$refs.form.validate().then((result) => {
					if(result){
						this.showLoading = true;
						var app = this;
						var user = app.userInfo;
						axios.post('/auth/login', user)
							.then(resp =>{
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
			}
		}
	}
</script>

<style lang="less" scoped>
	.index-card-height{
		height:316px !important;
	}
	.index-content{
		z-index: 100;
		background-color: white;
		width: 100%;
		top: 20px;
	}
	.index-content-intro-0{
		padding-top: 75px;
		padding-bottom: 159px;
		margin-left: 0px !important;
		margin-right: 0px !important;
	}
	.index-content-intro-1{
		min-height: 500px;
	}

</style>
<style scoped>
	.index-background{
		z-index: 0;
		top: 0;
		left: 0;
		right: 0;
		height: 100%;
		width: 100%;
		background-size: 130%;
		background-position: top;
		background-attachment: fixed;
		position: absolute;
	}
	.row{
		margin-left: 0px;
		margin-right: 0px;
	}
	@media(min-width: 800px){
		.index-background{
			background-image: url('/img/desktop_bg.png');
		}
		.index-logo{
			height: 141.5px;
			width: 141.5px;
		}
		.index-site-name{
			line-height: 100px;
		}
		.index-site-name > small{
			font-size: 20px;
		}
	}

	@media(max-width: 800px){
		.index-background{
			background-image: url('/img/mobile_bg.png');
		}
		.index-logo{
			height: 100px;
			width: 100px;
		}
		.index-site-name{
			line-height: 40px;
			margin-top: 0;
		}
		.index-site-name > small{
			font-size: 20px;
		}
		.index-login-card{
			margin: 0;
		}
	}
</style>