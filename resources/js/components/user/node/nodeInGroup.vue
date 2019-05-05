<template>
	<mu-container>
			<transition-group name="fade" tag="div" class="row flex-row justify-content-start align-items-start" v-on:before-enter="beforeEnter" v-on:enter="enter" v-on:leave="leave" mode="out-in">
				<mu-col span="12" :lg="showDesktopNodeDetail ? 12 : 4" :md="showDesktopNodeDetail ? 12 : 6" sm="12" v-for="node in nodes" :key="node.id">
					<mu-card raised>
						<mu-row>
							<mu-col span="2">
								<mu-flex justify-content="end" style="padding-top: 16px;">
									<img :src="'https://www.countryflags.io/' + node.area + '/flat/64.png'" height="36px">
								</mu-flex>
							</mu-col>
							<mu-col span="10">
								<mu-flex justify-content="start">
									<mu-card-title :title="node.name" style="padding-left: 8px;"></mu-card-title>
								</mu-flex>
							</mu-col>
						</mu-row>
						<mu-card-text v-html="node.des" style="height: 100px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;" />
						<mu-card-actions>
							<mu-row>
								<mu-col sm="2">
									<mu-flex justify-content="start">
										<mu-button small icon color="primary" @click="confirmButton(node.id, node.name)"><mu-icon value="wifi_tethering"></mu-icon></mu-button>
									</mu-flex>
								</mu-col>
								<mu-col sm="6" offset="1">
									<mu-flex justify-content="center" style="padding-top: 11px">
										<mu-linear-progress mode="determinate" :value="node.status == null ? 2 : node.status.load" :size="10" :max="2" :color="node.status == null ? 'grey600': node.status.load > 1 ? 'red900' : 'green700'" style="border-radius: 5px"></mu-linear-progress>
									</mu-flex>
								</mu-col>
								<mu-col sm="2" offset="1">
									<mu-flex justify-content="end">
										<mu-button small icon @click="$emit('showNodeDetail', node.id)"><mu-icon value="more_horiz"></mu-icon></mu-button>
									</mu-flex>
								</mu-col>
							</mu-row>
						</mu-card-actions>
					</mu-card>
				</mu-col>
			</transition-group>
	</mu-container>
</template>

<script>
	export default {
		props:{
			gid: Number,
			showDesktopNodeDetail: Boolean,
		},
		data:() => ({
			isDisplay: false,
			nodes:[],
		}),
		mounted(){
			axios.get('/api/v1/user/getNodeInGroup',{
				params:{
					group: this.gid
				}})
				.then(resp =>{
					this.nodes = resp.data;
					this.isDisplay = true;
				})
				.catch(resp =>{
					console.log(resp);
				})
		},
		methods: {
			confirmButton(id, name) {
			},
			render(h) {
				return h('container', {
					staticClass: 'fusion-node-in-group',
					props: {
						gid: this.gid,
					},
				});
			},
			beforeEnter: function (el) {
				el.style.opacity = 0
			},
			enter: function (el,done) {
				var delay = el.dataset.index * 150;
				setTimeout(function () {
					Velocity(
						el,
						{ opacity: 1},
						{ complete: done }
					)
				}, delay)
			},
			leave: function (el,done) {
				var delay = el.dataset.index * 150;
				setTimeout(function () {
					Velocity(
						el,
						{ opacity: 0},
						{ complete: done }
					)
				}, delay)
			}
		}
	}
</script>

<style lang="less" scoped>
	.mu-linear-progress {
		.mu-linear-progress-determinate {
			border-bottom-right-radius: 5px !important;
			border-top-right-radius: 5px !important;
		}
	}

</style>