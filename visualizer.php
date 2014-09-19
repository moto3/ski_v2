<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Ski</title>
<script language="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<style>
	#map {width:800px;border:1px solid #cc0000;margin:auto;}
	.cell {position:relative;width:38px;height:38px;border:1px solid #ccc;float:left;}
	
</style>


</head>

<body>

<button onclick="generate_code()">Generate Code</button>

<div id="row" style="display:none;">
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<div class="cell"></div>
<br clear="all" />
</div>

<div id="map"></div>

<script>
	$(function(){
		var row_html = $("#row").html().toString();
		$("#row").html('');
		for(var i =0 ; i < 300; i++){
			$("#map").append(row_html);
		}
		$(".cell").each(function(){
			$(this).attr('data-id', 0);
		}).click(function(){
			switch($(this).attr('data-id')){
				case '0':
					$(this).attr('data-id', 1).css({'background-color':'#cfc'});
				break;
				case '1':
					$(this).attr('data-id', 2).css({'background-color':'#fcc'});
				break;
				case '2':
					$(this).attr('data-id', 0).css({'background-color':'#fff'});
				break;
			}
		});
	});
	
	function generate_code(){
		var total_cols = 20;
		var first_col = [];
		var return_array = [first_col];
		var row_counter = 0;
		var col_counter = 0;
		$(".cell").each(function(){
			if( row_counter >= total_cols ){
				row_counter = 0;
				col_counter++;
				var new_array = [];
				return_array.push(new_array);
			}
			return_array[col_counter].push($(this).attr('data-id'));
			row_counter++;
		});
		
		console.log(return_array);
	}
	
</script>

</body>
</html>
