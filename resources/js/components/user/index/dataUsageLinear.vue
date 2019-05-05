<template>
	<mu-flex justify-content="center" style="height: 81px;">
		<la-cartesian :height="75" :data="values" autoresize>
			<la-line curve animated :width="2" prop="value" color="url(#color-id)"></la-line>
			<defs>
				<linearGradient id="color-id" x1="0" y1="0" x2="0" y2="1">
					<stop offset="0" stop-color="#512DA8"></stop>
					<stop offset="1" stop-color="#E040FB"></stop>
				</linearGradient>
			</defs>
		</la-cartesian>
	</mu-flex>
</template>

<script>
	export default{
		props: {
			range: Number,
			item: Number,
		},
		data: () => ({
			values: [
				{ value: 0 },
				{ value: 2 },
				{ value: 5 },
				{ value: 9 },
				{ value: 5 },
				{ value: 10 },
				{ value: 3 },
				{ value: 5 },
				{ value: 0 },
				{ value: 1 },
				{ value: 8 },
				{ value: 2 },
				{ value: 9 },
				{ value: 0 }
			],
		}),
		computed:{
			reversed(){
				if(isMobile())
					return 0;
				else
					return this.item;
			}
		},
		mounted(){
		},
		methods: {
		}
	};
</script>

<style scoped>

</style>