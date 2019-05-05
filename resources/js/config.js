// MuseUI Configs
import Vue from "vue";
import Message from "muse-ui-message";
import Toast from "muse-ui-toast";

Vue.config.devtools = true;

Message.config({
	okLabel: 'OK',
	cancelLabel: 'Cancel',
});
Toast.config({
	position: 'bottom-end',
	time: 2000,
	closeIcon: 'close',
	close: false,
	successIcon: 'check_circle',
	infoIcon: 'info',
	warningIcon: 'priority_high',
	errorIcon: 'warning'
});