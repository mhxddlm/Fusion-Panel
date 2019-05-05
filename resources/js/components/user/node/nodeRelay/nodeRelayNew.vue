<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="4" md="4" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('user.node-relay.card-title-new-relay')" />
					<mu-card-text>
						<mu-form ref="form" :model="relay">
							<mu-stepper :active-step="activeStep" orientation="vertical">
								<mu-step>
									<mu-step-label>
										{{stepperTitle[0]}}
									</mu-step-label>
									<mu-step-content>
										<p>
											Please pick a relay node from node list.<br>
											THIS IS THE NODE YOU CONNECT WITH YOUR CLIENT.
										</p>
									</mu-step-content>
								</mu-step>
								<mu-step>
									<mu-step-label>
										{{stepperTitle[1]}}
									</mu-step-label>
									<mu-step-content>
										<p>
											Please pick a final node from node list that is different than relay node.<br>
											THIS IS THE NODE YOU WILL FINALLY CONNECT TO.
										</p>
									</mu-step-content>
								</mu-step>
								<mu-step>
									<mu-step-label>
										{{stepperTitle[2]}}
									</mu-step-label>
									<mu-step-content>
										<mu-form-item prop="name" :rules="nameRules">
											<mu-text-field color="primary" v-model="relay.name" prop="name"></mu-text-field>
										</mu-form-item>
									</mu-step-content>
								</mu-step>
								<mu-step>
									<mu-step-label>
										{{stepperTitle[3]}}
									</mu-step-label>
									<mu-step-content>
										{{summary}}
									</mu-step-content>
								</mu-step>
							</mu-stepper>
						</mu-form>
					</mu-card-text>
					<mu-card-actions>
						<mu-button :disabled="activeStep < 1" @click="handlePrev" >{{$t('register.button-previous')}}</mu-button>
						<mu-button color="primary" v-loading="showLoading" data-mu-loading-size="24" :disabled="showLoading === true || activeStep < 2" @click="handleNext">{{nextButton}}</mu-button>
					</mu-card-actions>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="8" md="8" sm="12">
				<mu-card v-loading="!isDisplayGroup" style="padding-bottom: 20px;" raised>
					<mu-card-title :title="$t('user.node-list.card-title-group')"></mu-card-title>
					<mu-container style="margin-top: 16px;">
						<mu-expansion-panel v-for="groupInfo in groupInfos" :key="groupInfo.id" :expand="isExpand">
							<div slot="header" style="font-size: 20px; font-weight: bold;">{{groupInfo.name}}</div>
							<fusion-node-in-group :gid="groupInfo.id" :relayStep.sync="activeStep" :finalNode.sync="relay.finalSelected" :relayNode.sync="relay.relaySelected" v-on:loadNodeDetail="showNodeDetail" v-on:selectNode="selectNode"/>
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
			activeStep: 0,
			groupInfos: [],
			relay: {
				id: null,
				relaySelected: -1,
				relayName: '',
				finalSelected: -1,
				finalName: '',
				name: '',
			},
			stepperTitle: {
				0: i18n.t('user.node-relay.stepper-select-0'),
				1: i18n.t('user.node-relay.stepper-select-1'),
				2: i18n.t('user.node-relay.stepper-select-2'),
				3: i18n.t('user.node-relay.stepper-select-3'),
			},
			summary: i18n.t('user.node-relay.stepper-select-done-text-default'),
			nameRules: [
				{ validate: (val) => !!val, message: i18n.t('user.node-relay.stepper-select-name-error-null')},
				{ validate: (val) => val.length >= 3, message: i18n.t('user.node-relay.stepper-select-name-error-less')}
			],
		}),
		mounted(){
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
		computed:{
			nextButton(){
				switch (this.activeStep) {
					case 0:
					case 1:
						return i18n.t('user.node-relay.button-text-select');
						break;
					case 2:
						return i18n.t('user.node-relay.button-text-next');
						break;
					case 3:
						return i18n.t('user.node-relay.button-text-submit');
						break;
				}
			}
		},
		methods:{
			selectNode(a){
				switch (this.activeStep) {
					case 0:
						this.relay.relaySelected = a[0];
						this.relay.relayName = a[1];
						this.stepperTitle[0] = i18n.t('user.node-relay.stepper-select-relay-done', {nodeName: a[1]});
						break;
					case 1:
						this.relay.finalSelected = a[0];
						this.relay.finalName = a[1];
						this.stepperTitle[1] = i18n.t('user.node-relay.stepper-select-final-done', {nodeName: a[1]});
						this.relay.name = this.relay.relayName + " -> " + this.relay.finalName;
						break;
					default:
						this.$alert("Stepper failed to load.", "System Error");
				}
				this.activeStep++;
			},
			showNodeDetail(id){
				console.log(id);
			},
			handleNext () {
				switch(this.activeStep){
					case 2:
						this.$refs.form.validate().then((result) => {
							if (result === true){
								this.activeStep++;
								this.summary = i18n.t('user.node-relay.stepper-select-done-text-final', {relayName: this.relay.relayName, finalName: this.relay.finalName});
							}
						});
						break;
					case 3:
						if(this.relay.relaySelected === this.relay.finalSelected){
							this.$alert("For unknown reason, relay and final nodes are the same.", "System Error");
							return false;
						}
						const newRelay = this.relay;
						delete newRelay['relayName'];
						delete newRelay['finalName'];
						axios.post('/api/v1/user/newRelay', {data: newRelay})
							.then(resp =>{
								console.log(resp.data);
							})
							.catch(resp =>{

							})
						break;
					default:
						console.warn('Non activeStep action is found.');
				}
			},
			handlePrev () {
				if(this.activeStep > 0){
					this.activeStep--;
					switch (this.activeStep) {
						case 0:
							this.relay.relaySelected = -1;
							break;
						case 1:
							this.relay.finalSelected = -1;
							break;
					}
					this.stepperTitle[this.activeStep] = i18n.t('user.node-relay.stepper-select-' + this.activeStep);
				}
			},
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