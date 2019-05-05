<template>
	<mu-dialog width="260" :open="open" transition="fade" dialog-class="" :esc-press-close="false"
	           :overlay-close="false" :overlay-opacity="0.9" overlay-color="white" :lock-scroll="true">
		<div v-show="!showLoadingAnimation">
		<mu-flex justify-content="center">
			<transition name="fade" mode="out-in">
				<mu-icon :size="55" value="check_circle" color="success" />
			</transition>
		</mu-flex>
		</div>
		<div v-show="showLoadingAnimation">
		<mu-flex justify-content="center">
			<transition name="fade" mode="out-in">
				<mu-circular-progress v-show="type === 'circle'" :mode="animationMode" :value="loaded * (100/total)" color="secondary" :size="50"/>
			</transition>
			<transition name="fade" mode="out-in">
				<mu-linear-progress v-show="type === 'linear'" :mode="animationMode" :value="loaded * (100/total)" color="secondary"/>
			</transition>
		</mu-flex>
		</div>
		<br/>
		<mu-flex justify-content="center">
			<div v-show="!hideText">
				<div v-show="loaded > 0">
					{{text || $t('terms.module-loading')}} ({{loaded}}/{{total}})
				</div>
				<div v-show="loaded === 0">
					{{$t('terms.module-initiating-connection')}}
				</div>
			</div>
		</mu-flex>
	</mu-dialog>
</template>

<script>
	export default {
		props:{
			mode: {
				default: "determinate",
				type: String
			},
			loaded: Number,
			total: Number,
			type: String,
			text: String,
			hideText: Boolean,
		},
		data: ()=>({
			open: true,
			showLoadingAnimation: true
		}),
		watch:{
			loaded(){
				if(this.loaded === this.total) {
					setTimeout(() => {
						this.showLoadingAnimation = false;
					}, 200);
					setTimeout(() => {
						this.open = false
					}, 500);
				}
			}
		},
		computed:{
			animationMode(){
				if(this.mode === 'determinate')
					if(this.loaded === 0)
						return "indeterminate";
					else
						return "determinate";
				else
					return this.mode;
			}
		}
	}
</script>

<style scoped>
	.dialog-back{
		filter: ;
	}
</style>