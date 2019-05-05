<template>
	<mu-container v-html="content" v-loading="!isDisplay">
	</mu-container>
</template>

<script>
	export default {
		props:{
			type: String,
		},
		data:() => ({
			isDisplay: false,
			content: null,
		}),
		mounted(){
			axios.get('/api/v1/user/getBulletin',{
				params:{
					type: this.type
				}})
				.then(resp =>{
					this.content = resp.data;
					this.isDisplay = true;
				})
				.catch(resp =>{
					console.log(resp);
				})
		},
		methods: {
			render(h) {
				return h('container', {
					staticClass: 'fusion-bulletin',
					props: {
						type: this.type,
					},
				});
			}
		}
	}
</script>

<style scoped>

</style>