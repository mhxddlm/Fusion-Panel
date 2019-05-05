<template>
	<mu-card raised>
		<mu-card-title :title="$t('user.settings.me.card-title-edit-panel')" />
		<mu-card-text>
			<mu-row gutter>
				<mu-col span="12" lg="6" md="6" sm="12">
					<mu-select :label="$t('user.settings.me.button-text-select-theme')"
					           v-model="theme" @change="setThemeNew(theme)" color="primary" full-width>
						<mu-option :label="$t('user.settings.me.select-text-choose-theme-light')" value="purple" icon>
							<mu-list-item-action icon>
								<mu-icon color="primary" value="brightness_1" />
							</mu-list-item-action>
							<mu-list-item-content>
								<mu-list-item-title>
									{{$t('user.settings.me.select-text-choose-theme-light')}}
								</mu-list-item-title>
							</mu-list-item-content>
						</mu-option>
					</mu-select>
				</mu-col>
				<mu-col span="12" lg="6" md="6" sm="12">
					<mu-select :label="$t('user.settings.me.button-text-select-i18n')"
					           v-model="language" @change="setLangNew(language)" color="primary" full-width>
						<mu-option label="English(US)" value="en-US">
							<mu-list-item-action>
								<img src="https://www.countryflags.io/US/flat/24.png">
							</mu-list-item-action>
							<mu-list-item-content>
								<mu-list-item-title>
									English(US)
								</mu-list-item-title>
							</mu-list-item-content>
						</mu-option>
						<mu-option label="简体中文" value="zh-CN">
							<mu-list-item-action>
								<img src="https://www.countryflags.io/CN/flat/24.png">
							</mu-list-item-action>
							<mu-list-item-content>
								<mu-list-item-title>
									简体中文
								</mu-list-item-title>
							</mu-list-item-content>
						</mu-option>
						<mu-option label="日本語" value="ja-JA">
							<mu-list-item-action>
								<img src="https://www.countryflags.io/JP/flat/24.png">
							</mu-list-item-action>
							<mu-list-item-content>
								<mu-list-item-title>
									日本語
								</mu-list-item-title>
							</mu-list-item-content>
						</mu-option>
					</mu-select>
				</mu-col>
			</mu-row>
		</mu-card-text>
	</mu-card>
</template>

<script>
	import theme from 'muse-ui/lib/theme';
	export default {
		data: ()=> ({
			theme: window.localStorage.getItem('theme') || 'purple',
			language: window.localStorage.localeLanguage || 'en-US'
		}),
		methods:{
			setLangNew(lang){
				this.$store.dispatch('setLangNew', lang);
				this.$toast.success(i18n.t('change-language-success'));
			},
			setThemeNew(type){
				switch(type){
					case 'light':
						window.localStorage.setItem('theme', 'purple');
						theme.use('purple');
						break;
					case 'dark':
						window.localStorage.setItem('theme', 'purple-dark');
						theme.use('purple-dark');
						break;
				}
			}
		}
	}
</script>

<style scoped>

</style>