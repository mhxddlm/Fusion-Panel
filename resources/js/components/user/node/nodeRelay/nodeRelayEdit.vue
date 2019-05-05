<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="4" md="4" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('user.node-relay.card-title-edit-relay')" />
					<mu-card-text v-loading="!isDisplayGroup">
						<mu-form ref="form" :model="relay">
							<mu-stepper :active-step="activeStep" orientation="vertical" :linear="false">
								<mu-step :completed="newRelay.relaySelected > 0">
									<mu-step-button @click="changeStep(0)">
										{{stepperTitle[0]}}
									</mu-step-button>
									<mu-step-content>
										<p>
											Please pick a relay node from node list.<br>
											THIS IS THE NODE YOU CONNECT WITH YOUR CLIENT.
										</p>
									</mu-step-content>
								</mu-step>
								<mu-step :completed="newRelay.finalSelected > 0">
									<mu-step-button @click="changeStep(1)">
										{{stepperTitle[1]}}
									</mu-step-button>
									<mu-step-content>
										<p>
											Please pick a final node from node list that is different than relay node.<br>
											THIS IS THE NODE YOU WILL FINALLY CONNECT TO.
										</p>
									</mu-step-content>
								</mu-step>
								<mu-step :completed="newRelay.name != null">
									<mu-step-button @click="changeStep(2)">
										{{stepperTitle[2]}}
									</mu-step-button>
									<mu-step-content>
										<mu-form-item prop="name" :rules="nameRules">
											<mu-text-field color="primary" v-model="relay.name" prop="name"></mu-text-field>
										</mu-form-item>
									</mu-step-content>
								</mu-step>
								<mu-step :completed="newRelay.name != null && newRelay.finalSelected > 0 && newRelay.relaySelected > 0">
									<mu-step-button @click="changeStep(3)">
										{{stepperTitle[3]}}
									</mu-step-button>
									<mu-step-content>
										{{summary}}
									</mu-step-content>
								</mu-step>
							</mu-stepper>
						</mu-form>
					</mu-card-text>
					<mu-card-actions>
						<mu-button color="primary" v-loading="showLoading" data-mu-loading-size="24" @click="submitChange()" :disabled="!(newRelay.name != null && newRelay.finalSelected > 0 && newRelay.relaySelected > 0)">{{$t('user.node-relay.button-text-submit-change')}}</mu-button>
					</mu-card-actions>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="8" md="8" sm="12">
				<mu-card v-loading="!isDisplayGroup" style="padding-bottom: 20px;" raised>
					<mu-card-title :title="$t('user.node-list.card-title-group')"></mu-card-title>
					<mu-container style="margin-top: 16px;">
						<mu-expansion-panel v-for="groupInfo in groupInfos" :key="groupInfo.id" :expand="isExpand">
							<div slot="header" style="font-size: 20px; font-weight: bold;">{{groupInfo.name}}</div>
							<fusion-node-in-group :gid="groupInfo.id" :relayStep.sync="activeStep" :finalNode.sync="newRelay.finalSelected" :relayNode.sync="newRelay.relaySelected" v-on:loadNodeDetail="showNodeDetail" v-on:selectNode="selectNode"/>
						</mu-expansion-panel>
					</mu-container>
				</mu-card>
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	const nodeInGroup = () => import('./nodeInGroupForRelay.vue');
	const nodeDetail = () => import('../nodeDetail.vue');
	export default {
		data: () =>({
			showLoading: false,
			isDisplayGroup: false,
			isExpand: !isMobile(),
			activeStep: -1,
			groupInfos: [],
			relay: {
				id: null,
				relaySelected: null,
				relayNodeName: '',
				finalSelected: null,
				finalNodeName: '',
				name: '',
			},
			newRelay: {
				id: null,
				relaySelected: null,
				relayNodeName: '',
				finalSelected: null,
				finalNodeName: '',
				name: '',
			},
			stepperTitle: {
				0: 'Loading...',
				1: 'Loading...',
				2: i18n.t('user.node-relay.stepper-edit-2-loaded'),
				3: i18n.t('user.node-relay.stepper-edit-3-default'),
			},
			summary: i18n.t('user.node-relay.stepper-select-done-text-default'),
			nameRules: [
				{ validate: (val) => !!val, message: i18n.t('user.node-relay.stepper-select-name-error-null')},
				{ validate: (val) => val.length >= 3, message: i18n.t('user.node-relay.stepper-select-name-error-less')}
			],
		}),
		mounted(){
			axios.get('/api/v1/user/getRelayInfo',{
				params:{
					id: this.$route.params.id,
				}})
				.then(resp => {
					this.relay = resp.data;
					this.relay['relaySelected'] = this.relay['relay_node'];
					this.relay['finalSelected'] = this.relay['final_node'];
					delete this.relay['relay_node'];
					delete this.relay['final_node'];
					this.newRelay = this.relay;
					this.stepperTitle[0] = i18n.t('user.node-relay.stepper-edit-0-loaded', {nodeNameOld: this.relay.relayNodeName});
					this.stepperTitle[1] = i18n.t('user.node-relay.stepper-edit-1-loaded', {nodeNameOld: this.relay.finalNodeName});
				})
				.catch(resp => {

				});

			axios.get('/api/v1/user/getUserGroup')
				.then(resp =>{
					this.groupInfos = resp.data;
					this.isDisplayGroup = true;
				})
				.catch(resp =>{
					console.log(resp);
				})
			this.handleResize = () => {
				this.isExpand = !isMobile();
			};
			window.addEventListener('resize', this.handleResize);
		},
		methods:{
			submitChange(){
				axios.post('/api/v1/user/updateRelay', {data: this.newRelay})
					.then(resp =>{
						console.log(resp.data);
					})
					.catch(resp =>{

					})
			},
			selectNode(a){
				switch (this.activeStep) {
					case 0:
						this.newRelay.relaySelected = a[0];
						this.newRelay.relayName = a[1];
						this.stepperTitle[0] = i18n.t('user.node-relay.stepper-edit-relay-done', {nodeNameOld: this.relay.relayNodeName, nodeNameNew: a[1]});
						break;
					case 1:
						this.newRelay.finalSelected = a[0];
						this.newRelay.finalName = a[1];
						this.stepperTitle[1] = i18n.t('user.node-relay.stepper-edit-final-done', {nodeNameOld: this.relay.finalNodeName, nodeNameNew: a[1]});

						break;
					default:
						this.$alert("Stepper failed to load.", "System Error");
				}
				this.newRelay.name = this.newRelay.relayName + " -> " + this.newRelay.finalName;
				this.activeStep = -1;
			},
			showNodeDetail(id){
				console.log(id);
			},
			changeStep(a){
				this.activeStep = a;
				switch(this.activeStep){
					case 0:
						this.newRelay.relaySelected = -1;
						break;
					case 1:
						this.newRelay.finalSelected = -1;
						break;
				}
				this.stepperTitle[a] = i18n.t('user.node-relay.stepper-edit-' + a + '-default');
			}
		},
		components: {
			'fusion-node-in-group': nodeInGroup,
			'fusion-node-detail': nodeDetail,
		}
	}
	function isMobile () {
		return window.innerWidth < 800;
	}
</script>

<style scoped>

</style>