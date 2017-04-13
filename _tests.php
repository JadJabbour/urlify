<?php

?>
<html>
	<head>
		<title>URLify Test Console</title>
		<style>
			div{
				min-height: 600px;
			}
			#console{
				width: 90%;
				margin: 0 auto;
				border: 5px solid black;
			}
			#left{
				float: left;
				width: 49%;
				border-right: 5px solid black;
				overflow: hidden;
			}
			#right{
				float: right;
				width: 49%;
				border-left: 5px solid black;
				overflow: hidden;
			}
			.current{
				width: 100%;
				height: 150px;
				border-bottom: 5px solid black;
				padding: 5px 5px 5px 5px;
				background-color: #82172b;
			}
			.current .success{
				background-color: #C0D890;
			}
			.current .failed{
				background-color: red;
			}
			.btn{
				width: 100%;
				padding-top: 20px;
			}
			.btn button{
				float: left;
				font-size: 20px;
				margin-left: 45%;
			}
		</style>
	</head>
	<body>
		<h5><b>*Requires an internet connection to load dependencies</b></h5>
		<div id="console">
			<div id="left"></div>
			<div id="right"></div>
		</div>
		<div class="btn">
			<button id='start'>Start Tests</button>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
		<script>
		var page = {};

		page.current_scenario = 0;

		page.scenarios = [
			{
				name: 'creating_new_url__short_url__error',
				description: 'attempts to create a short url link with short_url less than 6 chars',
				url: 'create_short_url',
				method: 'POST',
				data: {
					short_url: 'test', 
					text: 'a test message'
				},
				expected: 'fail'
			},
			{
				name: 'creating_new_url__success',
				description: 'attempts to create a short url link',
				url: 'create_short_url',
				method: 'POST',
				data: {
					short_url: 'test00', 
					text: 'a test message 2'
				},
				expected: 'success'
			},
			{
				name: 'creating_new_url__non_unique__success',
				description: 'attempts to create with the same short url as before, creates & returns a new unique short_url',
				url: 'create_short_url',
				method: 'POST',
				data: {
					short_url: 'test00', 
					text: 'a test message 3'
				},
				expected: 'success'
			},
			{
				name: 'retreiving_url__invalid_short_url__error',
				description: 'attempts to get a short url that doesnt exist (test22)',
				url: 'retrieve_text/test22',
				method: 'GET',
				data: {},
				expected: 'fail'
			},
			{
				name: 'retreiving_url__valid_short_url__success',
				description: 'attempts to get a short url content',
				url: 'retrieve_text/test00',
				method: 'GET',
				data: {},
				expected: 'success'
			},
			{
				name: 'get_stats__success',
				description: 'attempts to get stats',
				url: 'stats',
				method: 'GET',
				data: {},
				expected: 'success'
			},
			{
				name: 'get_all__success',
				description: 'attempts to get all saved content',
				url: 'all',
				method: 'GET',
				data: {},
				expected: 'success'
			},
			{
				name: 'remove_short_url__success',
				description: 'attempts to remove saved content',
				url: 'remove/test00',
				method: 'POST',
				data: {},
				expected: 'success'
			},
			{
				name: 'remove_short_url__doesnt_exist__error',
				description: 'attempts to remove saved content',
				url: 'remove/test55',
				method: 'POST',
				data: {},
				expected: 'fail'
			},
			{
				name: 'get_all_2__success',
				description: 'attempts to get all saved content (again, after remove)',
				url: 'all',
				method: 'GET',
				data: {},
				expected: 'success'
			}
		];

		page.html = "<div id='{id}' class='current'>{values}</div>";

		page.log_test = function(html){
			$(page.console._current).prepend(html);
		}

		page.log_output = function(html){
			$(page.console._output).prepend(html);
		}

		page.parse_output = function(object, channel, test_id){
			var values = '';

			for(var attr in object){
			    if(object.hasOwnProperty(attr)){
			    	values += ('<b>' + attr + '</b>: ' + attr == 'data' ? JSON.stringify(object[attr]) : object[attr] + '<br>');
			    }
			}

			eval('page.log_' + channel + '("' + page.html.replace('{id}', test_id).replace('{values}', values) + '")');
		}

		page.run_scenario = function(index){
			if(index == page.scenarios.length){
				alert('All tests are done');
				return true;
			}

			var scenario = page.scenarios[index];
			page.parse_output(scenario, 'test', scenario.name);

			$.ajax({
				url: scenario.url,
				method: scenario.method,
				data: scenario.data,
			}).done(function(data){

			}).fail(function(data){

			}).always(function(data){
				page.parse_output(data, 'output', scenario.name);
				page.current_scenario++;
				page.run_scenario(page.current_scenario);
			});
		}

		$('document').ready(function(){
			page.console = {};
			page.console._el = $('#console');
			page.console._current = $('#left');
			page.console._output = $('#right');

			$('#start').click(function(){
				$(this).hide();
				page.run_scenario(page.current_scenario);
			});
		});
		</script>
	</body>
</html>