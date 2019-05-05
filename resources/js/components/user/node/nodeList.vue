<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" :lg="showDesktopNodeDetail ? 6 : 12" :md="showDesktopNodeDetail ? 6 : 12" sm="12" class="animate-move">
				<fusion-node-group v-for="group in groupInfos" :key="group.id" :groupID="group.group_id"
				                   :showDesktopNodeDetail.sync="showDesktopNodeDetail" @showNodeDetail="showNodeDetail" />
			</mu-col>
			<mu-col span="12" lg="6" md="6" sm="12" class="node-detail animate-move" v-show="showDesktopNodeDetail">
				<fusion-node-detail :nodeID="detailNodeID" />

				<div class="sticky" style="display: none;">
					<mu-flex justify-content="center">
						<mu-flex justify-content="start" align-items="center" style="width: 75px;">
							<mu-button fab color="primary">
								<mu-icon value="near_me"></mu-icon>
							</mu-button>
						</mu-flex>
						<mu-flex justify-content="end" align-items="center" style="width: 75px;">
							<mu-button fab color="red" @click="hideNodeDetail()">
								<mu-icon value="close"></mu-icon>
							</mu-button>
						</mu-flex>
					</mu-flex>
				</div>
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	const nodeGroup = () => import('./nodeGroup.vue');
	const nodeDetail = () => import('./nodeDetail.vue');
	export default {
		data: ()=>({
			groupInfos: [],
			showDesktopNodeDetail: false,
			detailNodeID: 0,
		}),
		mounted(){
			axios.get('/api/v1/user/getUserGroupInfo')
				.then(resp =>{
					this.groupInfos = resp.data;
				})
				.catch(resp =>{
					console.log(resp);
				});
			this.handleResize = () => {
				this.isExpand = !isMobile();
			};
			window.addEventListener('resize', this.handleResize);
		},
		methods:{
			showNodeDetail(id){
				if(!isMobile()){
					this.detailNodeID = id;
					if(!this.showDesktopNodeDetail){
						document.getElementsByClassName('node-detail')[0].classList.add('bounceInRight', 'animated');
						this.showDesktopNodeDetail = true;
						setTimeout(()=>{
							document.getElementsByClassName('sticky')[0].style.display = "";
							document.getElementsByClassName('node-detail')[0].classList.remove('bounceInRight', 'animated');
							document.getElementsByClassName('sticky')[0].classList.add('bounceInRight', 'animated');
						}, 1000);
						setTimeout(()=>{
							document.getElementsByClassName('sticky')[0].classList.remove('bounceInRight', 'animated');
						}, 2000);
					}
				}else{
					this.$router.push({ name: 'userNodeDetail', params: { id: id } })
				}
			},
			hideNodeDetail(){
				document.getElementsByClassName('sticky')[0].classList.add('bounceOutRight', 'animated');
				setTimeout(()=>{
					document.getElementsByClassName('node-detail')[0].classList.add('bounceOutRight', 'animated');
				}, 500);
				setTimeout(()=>{
					document.getElementsByClassName('sticky')[0].style.display = "none";
					document.getElementsByClassName('sticky')[0].classList.remove('bounceOutRight', 'animated');
					document.getElementsByClassName('node-detail')[0].classList.remove('bounceOutRight', 'animated');
					this.showDesktopNodeDetail = false;
				}, 1500);
			}
		},
		components: {
			'fusion-node-group': nodeGroup,
			'fusion-node-detail': nodeDetail,
		}
	}
	function isMobile () {
		return window.innerWidth < 800;
	}
</script>

<style lang="less" scoped>
	.mu-dialog-body{
		padding: 0px;
	}
	.mu-dialog-body > .container{
		padding: 0px;
	}
	.animate-move{
		transition: all .5s ease;
	}
	@media (min-width: 800px){
		.sticky{
			position: fixed;
			bottom: 28px;
			right: 20px;
		}
	}
</style>