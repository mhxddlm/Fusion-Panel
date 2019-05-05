<template>
	<mu-container>
		<mu-row gutter>
			<mu-col span="12" lg="6" md="6" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('admin.ticket.list.card-title-tickets-new')" />
					<mu-card-text>
						<mu-card v-for="ticket in newTicketList" :key="ticket.id" raised>
							<mu-card-header :title="ticket.title" :sub-title="$t('admin.ticket.list.card-subtitle-from', {name: ticket.name})">
								<mu-avatar slot="avatar">
									<img :src="'https://www.gravatar.com/avatar/' + emailHash(ticket.email)">
								</mu-avatar>
							</mu-card-header>
							<mu-card-text>
								{{ticket.content}}
							</mu-card-text>
							<mu-card-actions>
								<mu-button tag="button" flat color="primary" @click="userWantToSelectThisTicketHuh(ticket.id)">{{$t('admin.ticket.list.button-text-reply')}}</mu-button>
								<mu-menu cover>
									<mu-button flat>{{$t('admin.ticket.list.button-text-action')}}</mu-button>
									<mu-list slot="content">
										<mu-list-item button color="red">
											<mu-list-item-action><mu-icon value="close"></mu-icon></mu-list-item-action>
											<mu-list-item-title>{{$t('admin.ticket.detail.button-text-close')}}</mu-list-item-title>
										</mu-list-item>
										<mu-list-item button color="warning">
											<mu-list-item-action><mu-icon value="lock"></mu-icon></mu-list-item-action>
											<mu-list-item-title>{{$t('admin.ticket.detail.button-text-lock')}}</mu-list-item-title>
										</mu-list-item>
										<mu-list-item button color="primary">
											<mu-list-item-action><mu-icon value="archive"></mu-icon></mu-list-item-action>
											<mu-list-item-title>{{$t('admin.ticket.detail.button-text-archive')}}</mu-list-item-title>
										</mu-list-item>
									</mu-list>
								</mu-menu>
							</mu-card-actions>
						</mu-card>
					</mu-card-text>
				</mu-card>
				<mu-card raised>
					<mu-card-title :title="$t('admin.ticket.list.card-title-tickets-open')" />
					<mu-card-text>
						<mu-card v-for="ticket in openedTicketList" :key="ticket.id" raised>
							<mu-card-header :title="ticket.title" :sub-title="$t('admin.ticket.list.card-subtitle-from', {name: ticket.name})">
								<mu-avatar slot="avatar">
									<img :src="'https://www.gravatar.com/avatar/' + emailHash(ticket.email)">
								</mu-avatar>
							</mu-card-header>
							<mu-card-text>
								{{ticket.content}}
							</mu-card-text>
							<mu-card-actions>
								<mu-button flat color="red">{{$t('admin.ticket.list.button-text-close')}}</mu-button>
								<mu-button tag="button" flat color="primary" @click="userWantToSelectThisTicketHuh(ticket.id)">{{$t('admin.ticket.list.button-text-reply')}}</mu-button>
							</mu-card-actions>
						</mu-card>
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="6" md="6" sm="12">
				<fusion-ticket-detail :id="selectedID" />
			</mu-col>
		</mu-row>
		<ell3y-loader :loaded="processedCount" :total="totalCount" type="circle" :hideText="true"/>
	</mu-container>
</template>

<script>
	import md5 from 'js-md5';
	const ticketDetail = () => import('./ticketDetail.vue');
	const moduleLoading = () => import('../../../public/moduleLoading.vue');
	export default {
		data: () =>({
			ticketList: [],
			processedCount: 0,
			totalCount: 0,
			selectedID: 0,
			detailedTicket:{}
		}),
		mounted(){
			this.getOpenTicketList();
		},
		computed:{
			newTicketList(){
				return _.filter(this.ticketList, o => {return (o.status === 2);});
			},
			openedTicketList(){
				return _.filter(this.ticketList, o => {return (o.status === 1);});
			}
		},
		methods:{
			getOpenTicketList(){
				this.totalCount = 1;
				axios.get('/api/v1/admin/getTicketList',{
					params:{
						showClosed: false,
					}})
					.then(resp =>{
						this.processedCount = 1;
						this.ticketList = resp.data;
					})
					.catch(resp =>{
						console.log(resp);
					})
			},
			userWantToSelectThisTicketHuh(id){
				if(isMobile()){
					this.$router.push({ name: 'adminTicketDetail', params: { id: id } })
				}else{
					this.selectedID = id;
				}
			},
			emailHash(email){
				return md5(email);
			},
		},
		components:{
			'fusion-ticket-detail': ticketDetail,
			'ell3y-loader': moduleLoading,
		}
	}
	function isMobile () {
		return window.innerWidth < 660;
	}
</script>

<style scoped>

</style>