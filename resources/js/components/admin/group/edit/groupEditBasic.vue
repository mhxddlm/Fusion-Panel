<template>
	<mu-card raised>
		<mu-card-title :title="$t('admin.group.edit.card-title-edit-info')" />
		<mu-card-text>
			<mu-form :model="groupBasicInfo">
				<mu-form-item prop="input" :label="$t('admin.group.edit.text-label-name')">
					<mu-text-field color="primary" v-model="groupBasicInfo.name"></mu-text-field>
				</mu-form-item>
				<mu-row gutter>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.group.edit.text-label-order')">
							<mu-text-field color="primary" v-model="groupBasicInfo.order"></mu-text-field>
						</mu-form-item>
					</mu-col>
					<mu-col span="12" lg="6" md="6" sm="12">
						<mu-form-item prop="input" :label="$t('admin.group.edit.text-label-speed-limit')">
							<mu-text-field color="primary" v-model="groupBasicInfo.speed_limit"></mu-text-field>
						</mu-form-item>
					</mu-col>
				</mu-row>
				<mu-form-item prop="input" :label="$t('admin.node.edit.text-label-description')">
					<vue-editor v-model="groupBasicInfo.description" :editorToolbar="customToolbar"></vue-editor>
				</mu-form-item>
				<mu-form-item prop="switch" :label="$t('admin.group.edit.switch-label-hidden')">
					<mu-switch color="primary" v-model="groupBasicInfo.hidden" :label="this.groupBasicInfo.hidden? $t('admin.group.edit.switch-label-hidden-enable') : $t('admin.group.edit.switch-label-hidden-disable')"></mu-switch>
				</mu-form-item>
			</mu-form>
		</mu-card-text>
		<mu-card-actions style="padding-top: -10px;">
			<mu-button color="primary" @click="saveBasicInfo()" flat>{{$t('admin.group.edit.button-text-save-info')}}</mu-button>
		</mu-card-actions>
	</mu-card>
</template>

<script>
	import { VueEditor } from 'vue2-editor'
	export default {
		props: {
			groupID: Number,
		},
		data: ()=>({
			groupBasicInfo: {},
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
			this.retrieveGroupBasicInfo();
		},
		methods: {
			retrieveGroupBasicInfo(){
				axios.get('/api/v1/admin/getGroupBasicInfo',{
					params:{
						id: this.groupID
					}})
					.then(resp => {
						resp.data.speed_limit = resp.data.speed_limit === 0 ? i18n.t('terms.unlimited') : this.dataConvertSpeed(resp.data.speed_limit);
						resp.data.hidden = resp.data.hidden === 1 ? true : false;
						this.groupBasicInfo = resp.data;
						this.$emit('globalGroupBasicInfo', resp.data);
						this.$emit('moduleFinishLoading', "groupBasicInfo");
					})
					.catch(resp =>{

					})
			},
			saveBasicInfo(){
				axios.post('/api/v1/admin/updateGroupBasicInfo', {data: this.groupBasicInfo})
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