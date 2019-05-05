<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="api-token" content="{{ session('api-token') }}">
	<meta name="api-url" content="{{ env('API_URL') }}">


	<!--     Fonts and icons     -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">

	<!-- Scripts -->
	<script src="{{ asset('/js/app.js') }}" defer></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.2.3/velocity.min.js"></script>
	<script src="{{asset('/js/jquery-3.3.1.min.js')}}"></script>
	<style type="text/css">
		.fade-enter-active, .fade-leave-active {
			transition: opacity .5s ease;
		}
		.fade-enter, .fade-leave-to
		{
			opacity: 0;
		}
		.mu-card-title-container{
			padding-bottom: 4px !important;
		}
		.mu-card{
			margin-top: 20px !important;
			border-radius: 10px !important;
		}
		.card{
			margin-top: 20px !important;
		}
		.mu-raised-button{
			border-radius: 10px !important;
		}
		.mu-bottom-item{
			min-width: 30px !important;
		}
		.mu-flat-button{
			border-radius: 10px !important;
		}
		.mu-table td{
			padding-left: 5px !important;
			padding-right: 5px !important;
		}
		td.mu-checkbox-col{
			padding-left: 24px !important;
			padding-right: 24px !important;
		}
	</style>
</head>

<body style="">
	<div id="app">
		<router-view></router-view>
	</div>
</body>
