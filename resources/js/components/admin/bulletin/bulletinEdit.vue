<template>
	<div>
		<mu-card raised>
			<mu-linear-progress color="secondary" v-show="loadingData"></mu-linear-progress>
			<mu-card-title :title="$t('admin.bulletin.edit.card-title-' + mode + '-bulletin')" />
			<mu-card-text>
				<vue-editor v-show="type !== undefined" v-model="newBulletin.content" :editorToolbar="customToolbar"></vue-editor>
				<p v-show="mode === 'update'">*{{$t('admin.bulletin.edit.text-field-why-update')}}</p>
			</mu-card-text>
			<mu-card-actions v-show="type !== undefined">
				<mu-button flat color="primary" @click="saveBulletin()" :disabled="type === undefined">{{$t('admin.bulletin.edit.button-text-' + mode)}}</mu-button>
				<mu-button flat @click="openPreviewDialog = true" :disabled="type === undefined">{{$t('admin.bulletin.edit.button-text-preview')}}</mu-button>
			</mu-card-actions>
		</mu-card>
		<mu-card v-show="type !== undefined" raised>
			<mu-card-title :title="$t('admin.bulletin.edit.card-title-history-bulletin')" />
			<mu-card-text>
				<mu-expansion-panel v-for="bulletin in bulletinList" :key="bulletin.id">
					<div slot="header">{{$t('admin.bulletin.edit.card-title-history-published-at', {time: bulletin.created_at})}}</div>
					<div v-html="bulletin.content"></div>
					<mu-button slot="action" flat color="red" @click="deleteBulletin(bulletin.id)">{{$t('admin.bulletin.edit.button-text-delete-version')}}</mu-button>
					<mu-button slot="action" flat color="primary" @click="showEditBulletinDialog(bulletin.id)">{{$t('admin.bulletin.edit.button-text-edit')}}</mu-button>
				</mu-expansion-panel>
			</mu-card-text>
		</mu-card>
		<mu-dialog :title="$t('admin.bulletin.edit.dialog-title-edit-bulletin')" width="800" max-width="90%" :esc-press-close="false" :overlay-close="false" :open.sync="openEditBulletinDialog">
			<vue-editor v-show="type !== undefined" v-model="editBulletin.content" :editorToolbar="customToolbar"></vue-editor>
			<p>{{$t('admin.bulletin.edit.text-field-edit-not-recommend')}}</p>
			<mu-button slot="actions" flat @click="openEditBulletinDialog = false">{{$t('admin.bulletin.edit.dialog-button-text-close')}}</mu-button>
			<mu-button slot="actions" flat color="primary" @click="saveEditedBulletin()">{{$t('admin.bulletin.edit.dialog-button-text-edit-save')}}</mu-button>
		</mu-dialog>
		<mu-dialog width="360" transition="slide-bottom" fullscreen :open.sync="openPreviewDialog">
			<mu-appbar color="primary" :title="$t('admin.bulletin.edit.dialog-title-preview')">
				<mu-button slot="left" icon @click="openPreviewDialog = false">
					<mu-icon value="close"></mu-icon>
				</mu-button>
			</mu-appbar>
			<div style="padding: 24px;">
				<div v-html="newBulletin.content"></div>
			</div>
		</mu-dialog>
	</div>
</template>

<script>
	import { VueEditor } from 'vue2-editor'
	export default {
		props:{
			type: String
		},
		data: ()=>({
			mode: 'pls-select',
			openPreviewDialog: false,
			openEditBulletinDialog: false,
			newBulletin:{
				id: undefined,
				type: undefined,
				content: undefined
			},
			editBulletin:{
				id: undefined,
				type: undefined,
				content: undefined
			},
			loadingData: false,
			bulletinList: [],
			customToolbar: [
				[{ 'header': [false, 1, 2, 3, 4, 5, 6, ] }],
				['bold', 'italic', 'underline', 'strike'],
				[{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
				['blockquote', 'code-block'],
				[{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
				[{ 'indent': '-1'}, { 'indent': '+1' }],
				[{ 'color': [] }],
			]
		}),
		watch:{
			type: {
				handler: function (a, b) {
					this.getData();
				}
			}
		},
		mounted(){
			if(!_.isEmpty(this.type))
				this.getData();
		},
		methods:{
			getData(){
				this.newBulletin.type = this.type;
				this.loadingData = true;
				axios.get('/api/v1/admin/getBulletinListByType',{
					params:{
						type: this.type
					}})
					.then(resp =>{
						this.bulletinList = resp.data;
						this.mode = "new";
						if(Date.now() - Date.parse(this.bulletinList[0].created_at) <= 300000){
							this.mode = "update";
							this.newBulletin.id = this.bulletinList[0].id;
							this.newBulletin.content = this.bulletinList[0].content;
							this.bulletinList = _.drop(this.bulletinList);
						}
						this.loadingData = false;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			showEditBulletinDialog(id){
				this.editBulletin = this.bulletinList.find(e => e.id === id);
				this.openEditBulletinDialog = true;
			},
			saveBulletin(){
				axios.post('/api/v1/admin/updateBulletinInfo', {data: this.newBulletin})
					.then(resp => {

					})
					.catch(resp =>{

					})
			},
			saveEditedBulletin(){
				axios.post('/api/v1/admin/updateBulletinInfo', {data: this.editBulletin})
					.then(resp => {

					})
					.catch(resp =>{

					})
			},
			deleteBulletin(id){
				axios.post('/api/v1/admin/deleteBulletinInfo', {data: id})
					.then(resp => {

					})
					.catch(resp =>{

					})
			},
		},
		components: {
			VueEditor
		}
	}
</script>

<style scoped>

</style>