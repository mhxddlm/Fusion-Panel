<template>
	<mu-container>
			<transition-group name="fade" tag="div" class="row flex-row justify-content-start align-items-start" v-on:before-enter="beforeEnter" v-on:enter="enter" v-on:leave="leave" mode="out-in">
				<mu-col span="12" lg="4" md="6" sm="12" v-for="node in nodes" :key="node.id">
					<mu-card raised>
						<mu-card-title :title="node.name" />
						<mu-card-text v-html="node.des" />
						<mu-card-actions>
							<mu-button color="primary" style="margin-left: 5px; margin-right: 5px;"  flat @click="confirmButton(node.id, node.name)" :disabled="!(relayStep <= 1 && relayStep >= 0)">Select</mu-button>
							<mu-button color="secondary" style="margin-left: 5px; margin-right: 5px;" @click="$emit('loadNodeDetail', node.id)" flat>More</mu-button>
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
			relayStep: Number,
			relayNode: Number,
			finalNode: Number,
		},
		data:() => ({
			isDisplay: false,
			nodes:[],
			relaySelected: [],
			finalSelected: [],
		}),
		watch:{
			relayNode(){
				if(this.relayNode !== this.relaySelected['id']){
					if(this.relayNode !== null && this.relaySelected.length !== 0)
						this.nodes.push(this.relaySelected[0]); //release first
					this.relaySelected = this.nodes.filter(function(item){ return item['id'] === this.relayNode }.bind(this));
					this.nodes = this.nodes.filter(function(item){ return item['id'] !== this.relayNode }.bind(this));
				}
			},
			finalNode(){
				if(this.finalNode !== this.finalSelected['id']){
					if(this.finalNode !== null && this.finalSelected.length !== 0)
						this.nodes.push(this.finalSelected[0]); //release first
					this.finalSelected = this.nodes.filter(function(item){ return item['id'] === this.finalNode }.bind(this));
					this.nodes = this.nodes.filter(function(item){ return item['id'] !== this.finalNode }.bind(this));
				}
			}
		},
		mounted(){
			axios.get('/api/v1/user/getNodeInGroup',{
				params:{
					group: this.gid
				}})
				.then(resp =>{
					this.nodes = resp.data;
					if(this.relayNode != null){
						this.relaySelected = this.nodes.filter(function(item){ return item['id'] === this.relayNode }.bind(this));
						this.nodes = this.nodes.filter(function(item){ return item['id'] !== this.relayNode }.bind(this));
					}
					if(this.finalNode != null){
						this.finalSelected = this.nodes.filter(function(item){ return item['id'] === this.finalNode }.bind(this));
						this.nodes = this.nodes.filter(function(item){ return item['id'] !== this.finalNode }.bind(this));
					}
					this.isDisplay = true;
				})
				.catch(resp =>{
					console.log(resp);
				})
		},
		methods: {
			confirmButton(id, name) {
				switch (this.relayStep) {
					case 0:
						axios.post('/api/v1/user/checkIfNodeAllowRelay', {node_id: id})
							.then(resp =>{
								if(resp.data === 1){
									//this.relayNode = id;
									//this.relaySelected = this.nodes.filter(function(item){ return item['id'] === id });
									this.$emit('selectNode', [id, name]);
								}else{
									this.$alert('Sorry, this node is not suitable for Relay', 'Hmmm...');
								}
							})
							.catch(resp =>{

							})
						break;
					case 1:
						this.$emit('selectNode', [id, name]);
						break;
					default:
						this.$alert('Stepper out bound', 'System Error');
				}
			},
			render(h) {
				return h('container', {
					staticClass: 'fusion-node-in-group',
					props: {
						gid: this.gid,
						relayStep: this.relayStep,
						relayNode: this.relayNode,
						finalNode: this.finalNode
					},
				});
			},
			beforeEnter: function (el) {
				el.style.opacity = 0;
				el.style.maxWidth = 0;
			},
			enter: function (el,done) {
				var delay = el.dataset.index * 150
				setTimeout(function () {
					Velocity(
						el,
						{ opacity: 1, maxWidth: 200},
						{ complete: done }
					)
				}, delay)
			},
			leave: function (el,done) {
				var delay = el.dataset.index * 150;
				setTimeout(function () {
					Velocity(
						el,
						{ opacity: 0, maxWidth: 0},
						{ complete: done }
					)
				}, delay)
			}
		}
	}
</script>

<style scoped>

</style>