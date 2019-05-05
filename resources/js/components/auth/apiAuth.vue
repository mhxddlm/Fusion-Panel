<template>
	<mu-container>
		<mu-flex justify-content="center">
			<mu-card class="single-card-center" raised>
				<mu-card-text style="padding: 30px">
				<mu-row gutter align-items="center"  align-content="center">
					<mu-col span="12" lg="5" md="5" sm="12">
						<mu-flex justify-content="center" align-content="center">
							<mu-icon :size="200" value="security" v-show="$route.params.to !== undefined" :color="iconColor"></mu-icon>
							<img src="/img/fun/error.png" v-show="$route.params.to === undefined" width="200"/>
						</mu-flex>
					</mu-col>
					<mu-col span="12" lg="7" md="7" sm="12">
						<mu-flex justify-content="center">
							<h1 style="font-size: 40px">{{largerText}}</h1>
						</mu-flex>
						<mu-flex justify-content="center">
							<div v-html="smallText" class="small-container"></div>
						</mu-flex>
					</mu-col>
				</mu-row>
				</mu-card-text>
			</mu-card>
		</mu-flex>
	</mu-container>
</template>

<script>
	export default {
		data: ()=>({
			largerText: i18n.t('api-auth.text-field-permission-lost'),
			smallText: i18n.t('api-auth.text-field-getting-permission'),
			loadOnce: false,
		}),
		computed:{
			iconColor(){
				return _.isUndefined(this.$store.state.userInfo.permission) ? 'red' : 'green';
			}
		},
		watch: {
			'$store.state.userInfo.permission'(newV, oldV){
				if(!_.isEmpty(newV) && !this.loadOnce && !_.isUndefined(this.$route.params.to)){
					this.loadOnce = true;
					this.largerText = i18n.t('api-auth.text-field-welcome-back');
					this.smallText = i18n.t('api-auth.text-field-welcome-back-explain');
					setTimeout(() =>{
						_.isEmpty(this.$route.params.to)? this.$router.go(-1) :
							this.$router.push({path: this.$route.params.to});}, 1000);
				}
			}
		},
		mounted() {
			if(_.isEmpty(this.$store.state.userInfo.permission) && !_.isUndefined(this.$route.params.to))
				setTimeout(() =>{this.$store.dispatch('storeUserInfo');}, 200);
			else{
				this.largerText = i18n.t('api-auth.text-field-stale-request');
				this.smallText = i18n.t('api-auth.text-field-stale-request-explain');
			}
			document.getElementById('sidebar').classList.add('mu-primary-color','mu-elevation-4');
			document.getElementById('sidebar').style.backgroundColor="";
		}
	}
</script>

<style lang="less" scoped>
	@media(min-width: 800px){
		.single-card-center{
			width: 900px;
			position: absolute;
			top: 20%;
		}
	}
	.small-container{
		min-width: 100%;
		margin-top: 10px;
		background-color: #e0e0e0;
		border-radius: 10px;
		padding: 20px;
	}
</style>