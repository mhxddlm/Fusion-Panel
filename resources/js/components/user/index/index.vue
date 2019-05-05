<template>
	<mu-container>
		<mu-paper :z-depth="5" class="card index-hello" style="border-radius: 10px;">
			<mu-card-text>
				<h1 v-html="welcomeWord" style="font-size: 40px; margin-top: -0.2em; color: white;"></h1>
				<h5 style="font-size: 15px; color: white;">{{$t('user.index.card-title-flow-welcome-subtitle')}}</h5>
				<div style="height: 80px;"></div>
			</mu-card-text>
		</mu-paper>
		<mu-row gutter style="margin: -105px 8px 20px 8px">
			<mu-col span="12" lg="4" md="12" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('user.components.data-usage-linear.text')" />
					<mu-card-text>
						<fusion-data-linear :item="1"/>
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="4" md="12" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('user.components.data-usage-percent.text')" />
					<mu-card-text>
						<fusion-data-percent :item="0" />
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="4" md="12" sm="12">
				<mu-card raised>
					<mu-card-title :title="$t('user.components.data-usage-linear.text')" />
					<mu-card-text>
						<fusion-data-linear :item="1"/>
					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
		<mu-row gutter>
			<mu-col span="12" lg="7" md="6" sm="12" order="2">
				<mu-card raised>
					<mu-card-title :title="$t('user.index.card-title-user-group')"/>
					<mu-card-text>
						<fusion-user-group-info-table />
					</mu-card-text>
				</mu-card>
			</mu-col>
			<mu-col span="12" lg="5" md="6" sm="12" order="1">
				<mu-card raised>
					<mu-card-title :title="$t('user.index.card-title-bulletin')"/>
					<mu-card-text>
						<fusion-bulletin type="userIndex"/>
					</mu-card-text>
				</mu-card>
			</mu-col>
		</mu-row>
	</mu-container>
</template>

<script>
	const dataUsageLinear = () => import('./dataUsageLinear.vue');
	const dataUsagePercent = () => import('./dataUsagePercent.vue');
	const bulletin = () => import('../modules/bulletin.vue');
	const userGroupInfoTable = () => import('./userGroupInfoTable.vue');
	export default {
		computed: {
			welcomeWord(){
				return i18n.t('user.index.card-title-flow-welcome-' + Math.floor(new Date().getHours()/6).toString() + '-' + (1 + Math.floor(Math.random() * 3)));
			}
		},

		components:{
			'fusion-data-percent': dataUsagePercent,
			'fusion-data-linear': dataUsageLinear,
			'fusion-bulletin': bulletin,
			'fusion-user-group-info-table': userGroupInfoTable
		},

	}
</script>

<style scoped>
	.index-divider{
		margin-top:20px;
		margin-bottom: 20px;
	}
	@media(min-width: 800px){
		.index-hello{
			background-image: url('/img/desktop_bg.png');
			background-attachment: fixed;
		}
	}

	@media(max-width: 800px){
		.index-hello{
			background-image: url('/img/mobile_bg.png');
			background-attachment: fixed;
		}
	}
</style>