<template>
	<mu-container>
		<title>{{titleName}}</title>
		<mu-drawer :z-depth="home ? 0 : 4" color='transparent' :class="{'is-drawer-open-user': (userPage && docked)}"
		           :open.sync="open" :docked="docked" :home="home" style="border-radius: 0px !important;">
			<router-view name="sidebarContent"/>
		</mu-drawer>
		<mu-appbar id="sidebar" :z-depth="home ? 0 : 4" :color="home ? 'transparent' : 'primary'" class="mu-appbar-header"
		           :class="{'is-open': (!home && open && docked), 'is-only-title': !(home || !docked), 'home-bg': home, 'is-open-user': (userPage && docked)}">
			<mu-button v-if="home || !docked" @click="toggleMenu()" icon slot="left">
				<mu-icon value="menu" ></mu-icon>
			</mu-button>
			{{pageName || ''}}
			<mu-menu slot="right">
				<mu-button flat>
					<mu-avatar>
						<img :src="avatarUrl" onerror="this.src='/img/avatar_404.png'">
					</mu-avatar>
				</mu-button>
				<mu-list v-if="!home" slot="content">
					<router-link :to="{name:'userIndex'}">
						<mu-list-item button>
							<mu-list-item-content>
								<mu-list-item-title>{{$t('pages.user.dashboard')}}</mu-list-item-title>
							</mu-list-item-content>
						</mu-list-item>
					</router-link>
					<mu-list-item button @click="logout()">
						<mu-list-item-content>
							<mu-linear-progress v-if="loggingOut" mode="determinate" :value="loggingOutPer" :size="15" :color="loggingOutPer === 100 ? 'success': 'warning'"></mu-linear-progress>
							<mu-list-item-title v-if="!loggingOut">{{$t('pages.exit')}}</mu-list-item-title>
						</mu-list-item-content>
					</mu-list-item>
				</mu-list>
				<mu-list v-if="home" slot="content">
					<router-link :to="{name:'publicIndex'}">
						<mu-list-item button>
							<mu-list-item-content>
								<mu-list-item-title>{{$t('pages.home')}}</mu-list-item-title>
							</mu-list-item-content>
						</mu-list-item>
					</router-link>
					<router-link :to="{name:'authRegister'}">
						<mu-list-item button>
							<mu-list-item-content>
								<mu-list-item-title>{{$t('pages.register')}}</mu-list-item-title>
							</mu-list-item-content>
						</mu-list-item>
					</router-link>
					<router-link :to="{name:'authLogin'}">
						<mu-list-item button>
							<mu-list-item-content>
								<mu-list-item-title>{{$t('pages.login')}}</mu-list-item-title>
							</mu-list-item-content>
						</mu-list-item>
					</router-link>
				</mu-list>
			</mu-menu>
		</mu-appbar>
		<mu-container :class="{'is-open': (!home && open && docked), 'is-open-user': (userPage && docked), 'app-content-home': home, 'app-content': !home}">
			<transition name="fade" mode="out-in">
				<router-view name="pageContent"/>
			</transition>
		</mu-container>
	</mu-container>
</template>

<script>
	import md5 from 'js-md5';
	export default {
		data: () => ({
			docked: isDesktop(),
			isMobile: isMobile(),
			activeMenu: false,
			open: false,
			loggingOut: false,
			loggingOutPer: 0
		}),
		mounted() {
			this.changeNav();
			this.handleResize = () => {
				this.changeNav();
				this.isMobile = isMobile();
			};
			window.addEventListener('resize', this.handleResize);
			const api_token = document.head.querySelector('meta[name="api-token"]').content;
			if(!_.isEmpty(api_token)) {
				this.$store.dispatch('storeUserInfo');
			}
		},
		computed: {
			home () {
				return (this.$route && this.$route.meta && !this.$route.meta.name.includes("user.") && !this.$route.meta.name.includes("admin."));
			},
			userPage (){
				return this.$route.meta.name.includes("user.");
			},
			pageName () {
				return i18n.t('pages.' + this.$route.meta.name);
			},
			titleName () {
				return i18n.t('pages.' + this.$route.meta.name) + ' - ' + this.APP_NAME;
			},
			avatarUrl() {
				return this.$store.state.userInfo.email == null ?
					'/img/avatar_404.png' : 'https://www.gravatar.com/avatar/' + md5(this.$store.state.userInfo.email);
			},
			path () {
				return this.$route.path;
			}
		},
		watch: {
			home (val) {
				this.open = !val;
				this.changeNav();
			},
			pageName () {
				this.activeMenu = false;
			},
			path () {
				if (!this.docked)
					this.open = false;
			},
		},
		methods: {
			changeNav () {
				const desktop = isDesktop();
				this.docked = this.home ? false : desktop;
				if (desktop === this.desktop) return;
				if (!desktop && this.desktop && this.open)
					this.open = false;
				if (desktop && !this.desktop && !this.open && !this.home)
					this.open = true;
				this.desktop = desktop;
			},
			setLangNew: function(lang){
				this.$store.dispatch('setLangNew', lang);
				this.$toast.success(i18n.t('change-language-success'));
			},
			toggleMenu () {
				this.open = !this.open;
			},
			logout(){
				this.loggingOut = true;
				this.loggingOutPer = 33;
				document.head.querySelector('meta[name="csrf-token"]').content = "";
				document.head.querySelector('meta[name="api-token"]').content = "";
				window.axios.defaults.headers.common['Authorization'] = "";
				window.axios.defaults.headers.common['X-CSRF-TOKEN'] = "";

				axios.post('/api/v1/userAuth/logout')
					.then(resp =>{
						this.loggingOutPer += 33;
					});
				setTimeout(() =>{
					this.loggingOutPer = 100;
					this.$toast.success(i18n.t('logout-success'));
					}, 3000);
				setTimeout(()=>{this.$router.go()}, 4000);
			}
		}
	}

	function isDesktop () {
		return window.innerWidth > 1350;
	}

	function isMobile () {
		return window.innerWidth < 800;
	}
</script>

<style lang="less" scoped>
	@import '~muse-ui/lib/styles/vars.less';
	body{
		margin:0;
		padding:0;
	}

	.is-drawer-open-user{
		width: 350px !important;
	}

	.container{
		margin:0;
		padding:0;
		max-width: 100%;
	}
	.mu-app-drawer {
		border-right: 1px solid rgba(0, 0, 0, 0.73);
	}

	.mu-appbar-header {
		position: fixed;
		left: 0;
		right: 0;
		top: 0;
		z-index: 201;
		overflow: hidden;
		&.is-open {
			left: 256px;
			box-shadow: 3px 4px 5px 0 rgba(0,0,0,.14) !important;
		}
		&.is-open-user {
			left: 350px !important;
			box-shadow: 3px 4px 5px 0 rgba(0,0,0,.14) !important;
		}
		&.is-only-title {
			.mu-appbar-title {
				margin-left: 16px;
			}
		}
	}
	.app-content {
		transition: all .45s cubic-bezier(.23,1,.32,1);
		padding-top: 70px;
		padding-left: 20px;
		padding-right: 20px;
		padding-bottom: 20px;
	}
	.app-content-home {
		padding-top: 70px;
		padding-left: 0px;
		padding-right: 0px;
		padding-bottom: 0px;
	}
	.app-content.is-open {
		padding-left: calc(256px + 20px);
	}
	.app-content.is-open-user {
		padding-left: calc(350px + 20px) !important;
	}
</style>