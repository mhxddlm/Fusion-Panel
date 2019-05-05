<template>
	<mu-card raised>
		<mu-tabs :value.sync="pageAttributes.statusActive" inverse indicator-color="secondary" full-width>
			<mu-tab>{{$t('admin.node.edit.tab-text-cpu')}}</mu-tab>
			<mu-tab>{{$t('admin.node.edit.tab-text-load')}}</mu-tab>
			<mu-tab>{{$t('admin.node.edit.tab-text-ram')}}</mu-tab>
			<mu-tab>{{$t('admin.node.edit.tab-text-network')}}</mu-tab>
		</mu-tabs>
		<mu-card-title :title="$t('admin.node.edit.card-title-server-status-info') + ' - ' + selectedStatusText" />
		<mu-card-text>
			<mu-flex justify-content="center">
				<la-cartesian :height="75" :data="selectedStatus" autoresize>
					<la-line curve animated :width="2" prop="value" color="url(#color-1)"></la-line>
					<la-line v-show="pageAttributes.statusActive === 3" curve animated :width="2" prop="upload" color="url(#color-2)"></la-line>
					<defs>
						<linearGradient id="color-1" x1="0" y1="0" x2="0" y2="1">
							<stop offset="0" stop-color="#E040FB"></stop>
							<stop offset="1" stop-color="#512DA8"></stop>
						</linearGradient>
						<linearGradient id="color-2" x1="0" y1="0" x2="0" y2="1">
							<stop offset="0" stop-color="#42B983"></stop>
							<stop offset="1" stop-color="#6FA8DC"></stop>
						</linearGradient>
					</defs>
				</la-cartesian>
			</mu-flex>
			<div v-show="pageAttributes.showStatusInfoSettings === true && centreControlled">
				<mu-flex justify-content="center">
					<p>Override by central control</p>
				</mu-flex>
			</div>

			<mu-form :model="pageAttributes" v-show="pageAttributes.showStatusInfoSettings === true && (centreControlled === null || !centreControlled)">
				<mu-form-item :label="$t('admin.node.edit.text-label-update-status-interval')">
					<mu-button small class="fusion-small-button" @click="pageAttributes.updateStatusInterval = 1" :disabled="pageAttributes.updateStatusInterval === 1">1s</mu-button>
					<mu-button small class="fusion-small-button" @click="pageAttributes.updateStatusInterval = 5" :disabled="pageAttributes.updateStatusInterval === 5">5s</mu-button>
					<mu-button small class="fusion-small-button" @click="pageAttributes.updateStatusInterval = 10" :disabled="pageAttributes.updateStatusInterval === 10">10s</mu-button>
					<mu-button small class="fusion-small-button" @click="pageAttributes.updateStatusInterval = -1" :disabled="pageAttributes.updateStatusInterval === -1">--</mu-button>
				</mu-form-item>
				<mu-form-item :label="$t('admin.node.edit.text-label-carousel-status')">
					<mu-button small class="fusion-small-button" @click="pageAttributes.carouselStatusInterval = 1" :disabled="pageAttributes.carouselStatusInterval === 1">1s</mu-button>
					<mu-button small class="fusion-small-button" @click="pageAttributes.carouselStatusInterval = 5" :disabled="pageAttributes.carouselStatusInterval === 5">5s</mu-button>
					<mu-button small class="fusion-small-button" @click="pageAttributes.carouselStatusInterval = 10" :disabled="pageAttributes.carouselStatusInterval === 10">10s</mu-button>
					<mu-button small class="fusion-small-button" @click="pageAttributes.carouselStatusInterval = -1" :disabled="pageAttributes.carouselStatusInterval === -1">--</mu-button>
				</mu-form-item>
			</mu-form>
		</mu-card-text>
		<mu-card-actions>
			<mu-flex justify-content="end">
				<mu-circular-progress v-show="pageAttributes.isUpdatingStatusInfo" :size="20" style="width:32px; height:32px; padding: 6px;"></mu-circular-progress>
				<mu-button small icon @click="pageAttributes.showStatusInfoSettings = !pageAttributes.showStatusInfoSettings"><mu-icon value="settings"></mu-icon></mu-button>
			</mu-flex>
		</mu-card-actions>
	</mu-card>
</template>

<script>
	export default {
		props:{
			nodeID: Number,
			centreControlled: Boolean,
			updateInterval: Number,
			carouselInterval: Number,
		},
		data: ()=>({
			pageAttributes: {
				statusActive: 0,
				updateStatusInterval: 5,
				carouselStatusInterval: 5,
				showStatusInfoSettings: false,
				isUpdatingStatusInfo: false,
			},
			nodeStatus:{
				cpu:[],
				ram:[],
				load: [],
				network: []
			},
			updateTimer: undefined,
			scrollTimer: undefined
		}),
		computed:{
			selectedStatus () {
				switch(this.pageAttributes.statusActive){
					case 0:
						return this.nodeStatus.cpu;
						break;
					case 1:
						return this.nodeStatus.load;
						break;
					case 2:
						return this.nodeStatus.ram;
						break;
					case 3:
						return this.nodeStatus.network;
						break;
				}
			},
			selectedStatusText () {
				switch(this.pageAttributes.statusActive){
					case 0:
						return i18n.t('admin.node.edit.tab-text-cpu');
						break;
					case 1:
						return i18n.t('admin.node.edit.tab-text-load');
						break;
					case 2:
						return i18n.t('admin.node.edit.tab-text-ram');
						break;
					case 3:
						return i18n.t('admin.node.edit.tab-text-network');
						break;
				}
			},
			shouldUpdateStatus () {
				return this.pageAttributes.updateStatusInterval > 0;
			},
			shouldCarouselStatus () {
				return this.pageAttributes.carouselStatusInterval > 0;
			}
		},
		mounted(){
			this.updateTimer = setInterval(() =>{
				if(this.pageAttributes.updateStatusInterval !== -1)
					this.updateServerStatusInfo();
				}, this.pageAttributes.updateStatusInterval * 1000);
			this.scrollTimer = setInterval(() =>{
				if(this.pageAttributes.carouselStatusInterval !== -1)
					this.carouselStatus();
			}, this.pageAttributes.carouselStatusInterval * 1000);

		},
		beforeDestroy(){
			clearInterval(this.updateTimer);
			clearInterval(this.scrollTimer);
		},
		methods:{
			updateServerStatusInfo(){
				this.pageAttributes.isUpdatingStatusInfo = true;
				axios.get('/api/v1/admin/getNodeStatusInfo',{
					params:{
						id: this.nodeID,
					}})
					.then(resp => {
						let temp = {cpu:[], ram:[], load: [], network: []};
						resp.data.forEach(e => {
							temp.cpu.push(new Object({value: e.cpu}));
							temp.ram.push(new Object({value: e.memory}));
							temp.load.push(new Object({value: e.load}));
							temp.network.push(new Object({upload: e.upload, value: e.download}));
						});
						this.pageAttributes.isUpdatingStatusInfo = false;
						this.nodeStatus = temp;
					})
					.catch(resp =>{

					})
			},
			carouselStatus(){
				if(this.pageAttributes.statusActive === 3)
					this.pageAttributes.statusActive = 0;
				else
					this.pageAttributes.statusActive++;
			}
		}
	}
</script>

<style scoped>
	.mu-tab{
		min-width: 0 !important;
	}
	.fusion-small-button{
		min-width: 0 !important;
	}
</style>