<template>
	<mu-container>
		<mu-flex justify-content="center">
			<mu-card class="single-card-center" raised>
				<mu-card-text style="padding: 30px">
					<mu-row gutter align-items="center"  align-content="center">
						<mu-col span="12" lg="5" md="5" sm="12">
							<mu-flex justify-content="center" align-content="center">
								<img src="/img/fun/access_denied.png" width="200"/>
							</mu-flex>
						</mu-col>
						<mu-col span="12" lg="7" md="7" sm="12">
							<mu-flex justify-content="center">
								<h1 style="font-size: 40px">{{$t('error.403.text-field-access-denied')}}</h1>
							</mu-flex>
							<mu-flex justify-content="center">
								<div v-html="$t('error.403.text-field-access-denied-explain')" class="small-container"></div>
							</mu-flex>
						</mu-col>
					</mu-row>
				</mu-card-text>
				<mu-card-actions>
					<mu-flex justify-content="center">
						<mu-button color="primary"
						@click="$router.push({name: $route.params.from})">{{$t('error.403.button-text-go-back')}}</mu-button>
					</mu-flex>
				</mu-card-actions>
			</mu-card>
		</mu-flex>
	</mu-container>
</template>

<script>
	export default {
		data: ()=>({
			countDown: 5
		}),
		computed:{
		},
		watch: {
		},
		mounted() {
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
		margin-top: 10px;
		min-width: 100%;
		background-color: #e0e0e0;
		border-radius: 10px;
		padding: 20px;
	}
</style>