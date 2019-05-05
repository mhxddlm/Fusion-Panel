<template>
	<mu-flex justify-content="center" style="height:81px;">
		<mu-linear-progress mode="determinate" :value="percent"  :size="15" color="#E040FB" style=" margin-top: 34.2px; border-radius: 7.5px"></mu-linear-progress>
	</mu-flex>
</template>

<script>
	export default{
		props: {
			item: Number,
		},
		data: () => ({
			percent: 0,
		}),
		mounted(){
			setTimeout(() => {this.percent = 90;}, 1500);
		},
		computed:{
			reversed(){
				if(isMobile())
					return 0;
				else
					return this.item;
			}
		},
		methods: {
			render(h) {
				return h('container', {
					staticClass: 'fusion-data-percent',
					props: {
						item: this.item,
					},
				});
			}
		}
	};
</script>

<style scoped>

</style>