<template>
	<mu-card raised>
		<mu-card-title :title="$t('admin.node.edit.card-title-edit-info')" />
		<mu-card-text>
			<mu-form :model="nodeBasicInfo">
				<mu-row gutter>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-name')">
							<mu-text-field color="primary" v-model="nodeBasicInfo.name"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-area')">
							<mu-text-field color="primary" v-model="nodeBasicInfo.area"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-address')">
							<mu-text-field color="primary" v-model="nodeBasicInfo.address"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-real-ip')">
							<mu-text-field color="primary" v-model="nodeBasicInfo.real_ip"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-controller-key')">
							<mu-text-field color="primary" v-model="nodeBasicInfo.key"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-controller-port')">
							<mu-text-field color="primary" v-model="nodeBasicInfo.controller_port"></mu-text-field>
						</mu-form-item>
					</mu-col>
				</mu-row>
				<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-description')">
					<vue-editor v-model="nodeBasicInfo.description" :editorToolbar="customToolbar"></vue-editor>
				</mu-form-item>
				<mu-row gutter>
					<mu-col span="12" lg="3" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-rate')">
							<mu-text-field color="primary" v-model="nodeBasicInfo.rate" type="number"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="3" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-speed-limit')">
							<mu-text-field color="primary" v-model="nodeBasicInfo.speed_limit"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="3" md="6" sm="12">
						<mu-form-item prop="switch" :label="$t('admin.node.edit.switch-label-relay-status')">
							<mu-switch color="primary" v-model="nodeBasicInfo.allow_relay" :label="this.nodeBasicInfo.allow_relay? $t('admin.node.edit.switch-label-relay-enable') : $t('admin.node.edit.switch-label-relay-disable')"></mu-switch>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="3" md="6" sm="12">
						<mu-form-item prop="switch" :label="$t('admin.node.edit.switch-label-hidden-status')">
							<mu-switch color="primary" v-model="nodeBasicInfo.hidden" :label="this.nodeBasicInfo.hidden? $t('admin.node.edit.switch-label-hidden') : $t('admin.node.edit.switch-label-shown')"></mu-switch>
						</mu-form-item>
					</mu-col>
				</mu-row>

			</mu-form>
		</mu-card-text>
		<mu-card-actions style="padding-top: -10px;">
			<mu-button color="primary" @click="saveBasicInfo()" flat>{{$t('admin.node.edit.button-text-save-info')}}</mu-button>
		</mu-card-actions>
	</mu-card>
</template>

<script>
	import { VueEditor } from 'vue2-editor'
	export default {
		props: {
			nodeID: Number,
		},
		data: ()=>({
			nodeBasicInfo: {},
			customToolbar: [
				[{ 'size': ['small', false, 'large', 'huge'] }],
				['bold', 'italic', 'underline', 'strike'],
				[{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
				['blockquote', 'code-block'],
				[{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
				[{ 'indent': '-1'}, { 'indent': '+1' }],
				[{ 'color': [] }],
			]
		}),
		mounted() {
			this.retrieveNodeBasicInfo();
		},
		methods: {
			retrieveNodeBasicInfo(){
				axios.get('/api/v1/admin/getNodeBasicInfo',{
					params:{
						id: this.nodeID
					}})
					.then(resp => {
						this.nodeBasicInfo = resp.data;
						this.$emit('globalNodeBasicInfo', resp.data);
						this.$emit('moduleFinishLoading', "nodeBasicInfo");
					})
					.catch(resp =>{

					})
			},
			saveBasicInfo(){
				axios.post('/api/v1/admin/updateNodeBasicInfo', {data: this.nodeBasicInfo})
					.then(resp =>{
						console.log(resp.data);
					})
					.catch(resp =>{

					})
			}
		},
		components: {
			VueEditor
		}
	}
</script>

<style scoped>

</style>