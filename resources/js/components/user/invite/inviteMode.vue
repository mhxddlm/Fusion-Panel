<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="6" sm="12">
				<mu-card raised>
					<mu-card-text>
						<mu-row gutter>
							<mu-col span="12" lg="4" md="4" sm="12">
								<mu-flex justify-content="center">
									<h1>{{invitedUser.length}}</h1>
								</mu-flex>
								<mu-flex justify-content="center">
									{{$t('user.invite.invite.text-field-total-invited-user')}}
								</mu-flex>
							</mu-col>
							<mu-col span="12" lg="4" md="4" sm="12">
								<mu-flex justify-content="center">
									<h1>{{$store.state.userInfo.invite_token_remain || 0}}</h1>
								</mu-flex>
								<mu-flex justify-content="center">
									{{$t('user.invite.invite.text-field-invite-token-remain')}}
								</mu-flex>
							</mu-col>
							<mu-col span="12" lg="4" md="4" sm="12">
								<mu-flex justify-content="center">
									<mu-button fab color="primary" @click="joinPromoteProgram()"
									           style="margin-top: 11.75px; margin-bottom: 11.75px">
										<mu-icon value="arrow_upward"></mu-icon>
									</mu-button>
								</mu-flex>
								<mu-flex justify-content="center">
									{{$t('user.invite.invite.text-field-upgrade-promote')}}
								</mu-flex>
							</mu-col>
						</mu-row>
						<mu-divider style="margin-top: 20px; margin-bottom: 20px;"></mu-divider>
						<mu-flex justify-content="center">
							{{$t('user.invite.invite.text-field-total-invited-user')}}
						</mu-flex>
						<div class="friends-container">
							<mu-flex justify-content="center">
								<p v-show="invitedUser.length === 0" style="margin-top:10.5px; margin-bottom: 10.5px;">
									{{$t('user.invite.promote.text-field-no-friend')}}
								</p>
								<mu-tooltip v-for="(user, index) in invitedUser" :key="index" :content="user.name">
									<mu-avatar  :size="40" :class="{'cover-up': index !== 0}">
										<img :src="'https://www.gravatar.com/avatar/' + user.email + '?s=40&d=404'" onerror="this.src='/img/avatar_404.png'">
									</mu-avatar>
								</mu-tooltip>
							</mu-flex>
						</div>
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="6" sm="12">
				<mu-card raised>
					<mu-card-text>

					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	export default {
		data: ()=> ({
			invitedUser: []
		}),
		computed:{

		},
		mounted(){
			this.getInviteInfo();
		},
		methods:{
			getInviteInfo(){
				axios.get('/api/v1/user/getInviteInfo')
					.then(resp =>{
						this.invitedUser = resp.data.member;
					})
			},
			joinPromoteProgram(){
				axios.post('/api/v1/user/joinPromoteProgram')
					.then(resp =>{
						if(resp.data !== 0){
							this.$store.dispatch('storeUserInfo');
							this.$alert(i18n.t('user.invite.invite.text-field-welcome-promote'),
								i18n.t('user.invite.invite.dialog-title-welcome-promote'));
							this.$router.push({ name: 'userInvitePromote'});
						}
					})
			}
		}
	}
</script>

<style scoped>
	.cover-up{
		margin-left: -15px;
	}
	.friends-container{
		margin-top: 10px;
		background-color: #e0e0e0;
		border-radius: 10px;
		height: 72px;
		padding-top: 18px;
	}
</style>