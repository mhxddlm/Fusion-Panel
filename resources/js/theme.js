import theme from 'muse-ui/lib/theme';
import * as colors from "muse-ui/lib/theme/colors";

theme.add('purple', {
	primary: '#512DA8',
	secondary: '#E040FB',
	success: colors.lightGreen700,
	warning: colors.orange800,
	info: colors.lightBlue200,
	error: colors.deepOrange900,
	track: '#bdbdbd',
	text: {
		primary: '#212121',
		secondary: '#757575',
		alternate: '#fff',
		disabled: '#757575',
		hint: 'rgba(0, 0, 0, 0.38)'
	},
	divider: '#BDBDBD',
	background: {
		paper: '#fff',
		chip: '#e0e0e0',
		default: '#fafafa'
	}
}, 'light');

theme.add('purple-dark', {
	primary: '#E040FB',
	secondary: '#512DA8',
	success: '#4caf50',
	warning: '#fdd835',
	info: '#512DA8',
	error: '#f44336',
	track: '#757575',
	text: {
		primary: '#fff',
		secondary: 'rgba(255, 255, 255, 0.7)',
		alternate: '#303030',
		disabled: 'rgba(255, 255, 255, 0.3)',
		hint: 'rgba(255, 255, 255, 0.3)'
	},
	divider: 'rgba(255, 255, 255, 0.3)',
	background: {
		paper: '#424242',
		chip: '#616161',
		default: '#303030'
	}
}, 'dark').addCreateTheme((theme) =>{
	return `
	.mu-form-item__focus{
		color: ${theme.primary}
	}
	.mu-input__focus{
		color: ${theme.primary}
	}
	.mu-linear-progress-indeterminate,
	.mu-linear-progress-background{
		background-color: ${theme.primary}
	}
	.mu-circle-spinner{
		border-color: ${theme.secondary}
	}
	.mu-pagination-item.mu-button.is-current{
		background-color: ${theme.primary}
	}
	.mu-checkbox-checked{
		color: ${theme.primary}
	}
	.is-selected{
		color: ${theme.primary}
	}
	`});


theme.use(window.localStorage.getItem('theme') || 'purple');