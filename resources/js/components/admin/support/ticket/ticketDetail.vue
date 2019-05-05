<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" :lg="colSpan" :md="colSpan" sm="12" :class="{'sticky': colSpan === 6}">
				<mu-card v-show="isMobile === false" raised>
					<mu-flex justify-content="center">
						<p>{{$t('admin.ticket.detail.text-field-select-from-right')}}</p>
					</mu-flex>
				</mu-card>
				<mu-card v-show="this.id === undefined && this.ticketID !== 0" raised>
					<mu-card-title :title="content.title" />
					<mu-card-text>
						<p>{{content.content}}</p>
					</mu-card-text>
					<mu-divider style="margin-top: 10px;"/>
					<mu-card-actions>
						<mu-row gutter>
							<mu-col span="10" sm="10">
								<mu-flex justify-content="end">
								<p style="margin: 0; font-size: 12px;">{{$t('admin.ticket.detail.text-field-opened-by', {name: content.name})}}<br>
									{{$t('admin.ticket.detail.text-field-email', {email: content.email})}}</p>
								</mu-flex>
							</mu-col>
							<mu-col span="2" sm="2">
								<mu-flex justify-content="end">
									<mu-avatar :size="36">
										<img :src="'https://www.gravatar.com/avatar/' + emailHash(content.email)">
									</mu-avatar>
								</mu-flex>
							</mu-col>
						</mu-row>
					</mu-card-actions>
				</mu-card>
				<mu-card v-show="this.ticketID !== 0" raised>
					<mu-card-title :title="$t('admin.ticket.detail.card-title-actions')" />
					<mu-card-text>
						<mu-row gutter>
							<mu-col span="4" sm="4">
								<mu-flex justify-content="center" align-items="center">
									<mu-button flat color="red" @click="ticketAction('close')" :disabled="content.status <= 0"><mu-icon left value="close"></mu-icon>{{$t('admin.ticket.detail.button-text-close')}}</mu-button>
								</mu-flex>
							</mu-col>
							<mu-col span="4" sm="4">
								<mu-flex justify-content="center" align-items="center">
									<mu-button flat color="warning" @click="ticketAction('lock')" :disabled="content.status <= -1"><mu-icon left value="lock"></mu-icon>{{$t('admin.ticket.detail.button-text-lock')}}</mu-button>
								</mu-flex>
							</mu-col>
							<mu-col span="4" sm="4">
								<mu-flex justify-content="center" align-items="center">
									<mu-button flat color="primary" @click="ticketAction('archive')" :disabled="content.status === -2"><mu-icon left value="archive"></mu-icon>{{$t('admin.ticket.detail.button-text-archive')}}</mu-button>
								</mu-flex>
							</mu-col>
						</mu-row>
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" :lg="colSpan" :md="colSpan" sm="12">
				<mu-card v-show="isLoadingReply === true" raised>
					<mu-card-text>
						<mu-flex justify-content="center">
							<mu-circular-progress :size="36"></mu-circular-progress>
						</mu-flex>
					</mu-card-text>
				</mu-card>
				<mu-card v-show="replies.length === 0 && ticketID !== 0 && isLoadingReply !== true" raised>
					<mu-card-text>
						<mu-flex justify-content="center">
							<p>{{$t('admin.ticket.detail.text-field-no-reply')}}</p>
						</mu-flex>
					</mu-card-text>
				</mu-card>
				<transition-group name="fade" tag="div"  v-on:before-enter="beforeEnter" v-on:enter="enter" v-on:leave="leave" mode="out-in">
					<mu-card v-for="reply in replies" :key="reply.id" raised>
						<mu-card-text>
							<p class="overflow">{{reply.content}}</p>
						</mu-card-text>
						<mu-divider style="margin-top: 5px;"/>
						<mu-card-actions>
							<mu-row v-if="reply.replied_by === content.created_by" gutter>
								<mu-col span="10" sm="10">
									<mu-flex justify-content="end">
										<p style="margin: 0; font-size: 12px;">{{$t('admin.ticket.detail.text-field-replied-by', {name: users[reply.replied_by].name})}}<br>
											{{$t('admin.ticket.detail.text-field-replied-at', {time: reply.created_at})}}</p>
									</mu-flex>
								</mu-col>
								<mu-col span="2" sm="2">
									<mu-flex justify-content="end">
										<mu-avatar :size="36">
											<img :src="'https://www.gravatar.com/avatar/' + emailHash(users[reply.replied_by].email) + '?s=36&d=404'" onerror="this.src='/img/avatar_404.png'">
										</mu-avatar>
									</mu-flex>
								</mu-col>
							</mu-row>
							<mu-row v-if="reply.replied_by !== content.created_by" gutter>
								<mu-col span="2" sm="2">
									<mu-flex justify-content="start">
										<mu-avatar :size="36">
											<img :src="'https://www.gravatar.com/avatar/' + emailHash(users[reply.replied_by].email) + '?s=36&d=404'" onerror="this.src='/img/avatar_404.png'">
										</mu-avatar>
									</mu-flex>
								</mu-col>
								<mu-col span="10" sm="10">
									<mu-flex justify-content="start">
										<p style="margin: 0; font-size: 12px;">{{$t('admin.ticket.detail.text-field-replied-by', {name: users[reply.replied_by].name})}}<br>
											{{$t('admin.ticket.detail.text-field-replied-at', {time: reply.created_at})}}</p>
									</mu-flex>
								</mu-col>
							</mu-row>
						</mu-card-actions>
					</mu-card>
				</transition-group>
				<mu-alert color="red" v-show="content.status <= 0" style="margin-top: 20px;">
					<mu-icon left value="warning"></mu-icon> {{ticketWarning}}
				</mu-alert>
				<mu-card v-show="content.status >= 0" raised>
					<mu-card-title :title="$t('admin.ticket.detail.card-title-reply')" />
					<mu-card-text>
						<mu-text-field v-model="newReply.content" multi-line :rows="4" full-width></mu-text-field>
					</mu-card-text>
					<mu-card-actions>
						<mu-button flat color="primary" v-loading="isSendingReply" data-mu-loading-size="24" @click="replyTicket">{{$t('admin.ticket.detail.button-text-reply')}}</mu-button>
					</mu-card-actions>
				</mu-card>
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	import md5 from 'js-md5';
	export default {
		props:{
			id: Number,
		},
		data: ()=>({
			ticketID: undefined,
			isSendingReply: false,
			isLoadingReply: false,
			ticketWarning: undefined,
			content: {
				title: '',
				name: '',
				email: '',
			},
			newReply: {
				id: undefined,
				content: undefined
			},
			replies: [],
			users: [],
		}),
		watch:{
			id: function(a, b){
				this.ticketID = this.id;
				if(a !== b && !_.isNull(a) && a > 0)
					this.getTicketDetail();
			}
		},
		computed:{
			colSpan(){
				return this.id === undefined ? 6 : 12;
			},
			isMobile(){
				return window.innerWidth < 660;
			}
		},
		beforeMount(){
			this.ticketID = _.isEmpty(this.id) ? _.toSafeInteger(this.$route.params.id) : this.id;
			this.newReply.id = this.ticketID;
		},
		mounted(){
			if(!_.isNull(this.ticketID) && this.ticketID > 0) {
				this.getTicketDetail();
			}
		},
		methods:{
			getTicketDetail(){
				this.isLoadingReply = true;
				axios.get('/api/v1/admin/getTicketDetail',{
					params:{
						id: this.ticketID,
					}})
					.then(resp =>{
						this.content = resp.data.content;
						this.replies = resp.data.replies;
						this.users = resp.data.users;
						this.content.name = this.users[this.content.created_by].name;
						this.content.email = this.users[this.content.created_by].email;
						if(this.content.status <= 0)
							switch(this.content.status){
								case 0:
									this.ticketWarning = i18n.t('admin.ticket.detail.text-field-ticket-closed', {time: this.content.updated_at});
									break;
								case -1:
									this.ticketWarning = i18n.t('admin.ticket.detail.text-field-ticket-locked', {time: this.content.updated_at});
									break;
								case -2:
									this.ticketWarning = i18n.t('admin.ticket.detail.text-field-ticket-archived', {time: this.content.updated_at});
									break;
							}
						this.isLoadingReply = false;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			replyTicket(){
				this.isSendingReply = true;
				this.newReply.id = this.ticketID;
				axios.post('/api/v1/admin/replyTicket', {data: this.newReply})
					.then(resp =>{
						this.getTicketDetail();
						this.newReply.content = undefined;
						this.isSendingReply = false;
						console.log(resp.data);
					})
					.catch(resp =>{

					})
			},
			ticketAction(action){
				let a = {};
				a.id = this.ticketID;
				a.action = action;
				axios.post('/api/v1/admin/ticketActions', {data: a})
					.then(resp =>{
						this.getTicketDetail();
						console.log(resp.data);
					})
					.catch(resp =>{

					})
			},
			emailHash(email){
				return md5(email);
			},
			beforeEnter: function (el) {
				el.style.opacity = 0;
				el.style.maxHeight = 0;
			},
			enter: function (el, done) {
				const delay = el.dataset.index * 200;
				setTimeout(function () {
					Velocity(
						el,
						{ opacity: 1, maxHeight: 1000},
						{ complete: done }
					)
				}, delay)
			},
			leave: function (el, done) {
				const delay = el.dataset.index * 200;
				setTimeout(function () {
					Velocity(
						el,
						{ opacity: 0, maxHeight: 0},
						{ complete: done }
					)
				}, delay)
			}
		}
	}
</script>

<style scoped>
	.sticky{
		position: sticky;
		position: -webkit-sticky;
		top: 64px;
	}
	.overflow{
		white-space: pre-wrap;
		word-wrap: break-word;      /* IE */
	}
</style>