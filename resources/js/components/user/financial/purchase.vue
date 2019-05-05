<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="12" md="12" sm="12">

			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	export default {
		data: () =>({
			items:[]
		}),
		mounted(){
			axios.get('/api/v1/user/getMerchandiseItems')
				.then(resp =>{
					this.items = resp.data;
				})
				.catch(resp =>{
					console.log(resp)
				})
		},
		methods:{
			charge(){

			}
		}
	}
</script>

<style scoped>

</style>