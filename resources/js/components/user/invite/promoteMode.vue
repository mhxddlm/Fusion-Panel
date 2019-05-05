<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="6" md="6" sm="12">
				<mu-card raised>
					<mu-card-text>
						<mu-row gutter>
							<mu-col span="12" lg="4" md="4" sm="12">
								<div v-show="promoteProgress >= 100">
									<mu-flex justify-content="center" >
										<mu-icon :size="92" value="check_circle" color="success" />
									</mu-flex>
								</div>
								<div v-show="promoteProgress < 100">
									<mu-flex justify-content="center">
										<mu-circular-progress mode="determinate" color="primary"
										                      :stroke-width="7" :value="promoteProgress" :size="92" />
									</mu-flex>
								</div>
							</mu-col>
							<mu-col span="12" lg="8" md="8" sm="12">
								<mu-paper :z-depth="5" class="simple-paper">
									<mu-card-text>
										<mu-flex justify-content="center">
											<div style="font-size: 40px;">
												{{$store.state.userInfo.promote_program}}
											</div>
										</mu-flex>
									</mu-card-text>
								</mu-paper>
							</mu-col>
						</mu-row>
						<mu-divider style="margin-top: 20px; margin-bottom: 20px;"></mu-divider>
						<mu-row gutter>
							<mu-col span="12" lg="4" md="4" sm="12">
								<mu-flex justify-content="center">
									<h1>{{invitedUser.length}}</h1>
								</mu-flex>
								<mu-flex justify-content="center">
									{{$t('user.invite.promote.text-field-total-invited-user')}}
								</mu-flex>
							</mu-col>
							<mu-col span="12" lg="4" md="4" sm="12">
								<mu-flex justify-content="center">
									<h1>$ {{$store.state.userInfo.promote_money}}</h1>
								</mu-flex>
								<mu-flex justify-content="center">
									{{$t('user.invite.promote.text-field-current-progress')}}
								</mu-flex>
							</mu-col>
							<mu-col span="12" lg="4" md="4" sm="12">
								<mu-flex justify-content="center">
									<h1>$ {{promoteMoneyTransferLimit}}</h1>
								</mu-flex>
								<mu-flex justify-content="center">
									{{$t('user.invite.promote.text-field-target-money')}}
								</mu-flex>
							</mu-col>
						</mu-row>
						<mu-divider style="margin-top: 20px; margin-bottom: 20px;"></mu-divider>
						<mu-flex justify-content="center">
							{{$t('user.invite.promote.text-field-total-invited-user')}}
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
					<mu-card-actions>
						<mu-button color="primary" v-show="promoteProgress >= 100">{{$t('user.invite.promote.button-text-rebate')}}</mu-button>
						<mu-button flat color="primary">{{$t('user.invite.promote.button-text-copy-link')}}</mu-button>
					</mu-card-actions>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="6" md="6" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('user.invite.promote.card-title-promote-info')" />
					<mu-card-text>

					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	export default {
		data: ()=>({
			promoteMoneyTransferLimit: 0,
			promoteMember: 0,
			invitedUser:[],
		}),
		computed:{
			promoteProgress(){
				return this.$store.state.userInfo.promote_money / this.promoteMoneyTransferLimit * 100;
			},
		},
		beforeMount(){
		},
		mounted(){
			this.getPromoteInfo();
		},
		methods:{
			getPromoteInfo(){
				axios.get('/api/v1/user/getPromoteInfo')
					.then(resp =>{
						this.invitedUser = resp.data.member;
						this.promoteMoneyTransferLimit = resp.data.promoteTransferLimit;
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
	.simple-paper{
		border-radius: 10px;
	}
	@media(max-width: 800px){
		.simple-paper{
			margin-top: 20px;
		}
	}
</style>