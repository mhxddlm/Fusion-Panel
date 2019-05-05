exports.install = function (Vue, options) {
	Vue.prototype.APP_NAME = process.env.MIX_APP_NAME || "Fusion";
	Vue.prototype.APP_NAME_HTML = process.env.MIX_APP_NAME_HTML || "Fusion";
	Vue.prototype.APP_FRONT_END_VERSION = "0.7.0(d12687)";
	Vue.prototype.APP_BACK_END_VERSION = "0.7.0(d12687)";
	Vue.prototype.dataConvertSpeed = function (e){
		let unit = "bps";
		if(e >= 1024) {
			unit = "Kbps";
			e /= 1024;
		}
		if(e >= 1024) {
			unit = "Mbps";
			e /= 1024;
		}
		if(e >= 1024){
			unit = "Gbps";
			e /= 1024;
		}
		if(e >= 1024){
			unit = "Tbps";
			e /= 1024;
		}
		return _.round(e, 2) + unit;
	};
	Vue.prototype.dataConvertTraffic = function (e){
		let unit = "B";
		if(e >= 1024) {
			unit = "KB";
			e /= 1024;
		}
		if(e >= 1024) {
			unit = "MB";
			e /= 1024;
		}
		if(e >= 1024){
			unit = "GB";
			e /= 1024;
		}
		if(e >= 1024){
			unit = "TB";
			e /= 1024;
		}
		return _.round(e, 2) + unit;
	};
};