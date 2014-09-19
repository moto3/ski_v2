<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Ski</title>
<script language="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<style>
	html, body {width:100%;height:100%;margin:0;padding:0;background-color:#ccc;}

	#content_player {position:absolute;top:0;width:100%;height:600px;}
	#content_player_wrapper {position:relative;width:800px;height:600px;margin:auto;}
	#player {position:absolute;width:80px;height:100px;left:380px;top:40px;background:url('./images/player.gif') no-repeat 0 0;}
	
	#top {position:relative;width:802px;height:18px;background-image:url('./images/top.gif');border:1px solid #a08080;border-bottom:none;}
	#content {position:relative;width:800px;height:600px;border:2px solid #a08080;overflow:hidden}
	#content_shadow {position:relative;width:804px;border-top:1px solid #c0c0ff;border-left:1px solid #c0c0ff;border-right:1px solid #a08080;border-bottom:1px solid #a08080;}
	#content_shadow_outer {position:relative;width:806px;margin:20px auto;border-top:1px solid #a08080;border-left:1px solid #a08080;border-right:1px solid #606040;border-bottom:1px solid #606040;}
	
	
	#stage {position:absolute;width:800px;height:1200px;background:url('./images/bg.gif'); }
	.tree {position:absolute;width:70px;height:70px;background:url('./images/trees.gif') no-repeat -100px 0;}
	
	.jump {position:absolute;width:100px;height:20px;background:url('./images/jump.gif') no-repeat 0 0;top:0;left:0;}
	
	#content_effect {position:absolute;top:0;width:100%;height:600px;display:none;}
	#content_effect_wrapper {position:relative;width:800px;height:600px;background-color:#cc0000;margin:auto;}
	
	#content_scores {position:absolute;top:0;width:100%;height:600px;}
	#content_scores_wrapper {position:relative;width:800px;height:600px;;margin:auto;}
	#scores {font-family:"Courier New", Courier, monospace;position:absolute;padding:10px;border:1px solid #ccc;left:600px;top:60px;width:150px;background-color:#efefef;font-weight:bold;}
	
</style>


</head>

<body>



<div id="content_shadow_outer">
	<div id="content_shadow">
    	<div id="top"></div>
		<div id="content">
			<div id="stage">
	    		<div class="tree"></div>
	        	<div class="tree"></div>
	        	<div class="tree"></div>
	        	<div class="tree"></div>
	        	<div class="tree"></div>
	        	<div class="tree"></div>
	        	<div class="tree"></div>
	        	<div class="tree"></div>
	        	<div class="tree"></div>
	        	<div class="tree"></div>
	        	<div class="tree"></div>
        
	        	<div class="jump"></div>
	        	<div class="jump"></div>
	        	<div class="jump"></div>
	    	</div>
		</div>
	</div>
</div>

<div id="content_player">
	<div id="content_player_wrapper">
    	<div id="player"></div>
    </div>
</div>
<div id="content_effect">
	<div id="content_effect_wrapper"></div>
</div>
<div id="content_scores">
	<div id="content_scores_wrapper">
    	<div id="scores">
        	Time: 00s<br/>
        	Distance: 00ft<br/>
            Style: 00;
        </div>
    
    </div>
</div>

<script>
var timer_on = false;
var game_on = true;
var jump_on = false;
var jump_timer = 0;
var time = 0;
var distance = 0;
var style = 0;
var player_bg_position = 0;

var left_keydown = false;
var right_keydown = false;
var up_keydown = false;
var down_keydown = false;
var collision = false;

var stage_move = 0;
var stage_top = 0;
var tree_top = 0;
var tree_left = 0;
var player_strafe = 0;
var player_top = 60;
var player_left = 380;
var player_bg_postion = 0;

	$(window).load(function(){
		game_init();	
	});
	
	function game_init(){
		$(".tree").each(function(){
			$(this).css({'left':Math.floor(Math.random()*800) + 'px', 'top':(Math.floor(Math.random()*1000)+200)+'px', 'background-position': '-' + Math.floor(Math.random()*8)*70 + 'px 0'})
		});
		$(".jump").each(function(){
			$(this).css({'left':Math.floor(Math.random()*800) + 'px', 'top':(Math.floor(Math.random()*1000)+200)+'px'})
		});
		$(document).keydown(function(e){
			if(e.which == 39 || e.which == 37 || e.which == 38 || e.which == 40){
				e.preventDefault();
			}
			if(e.which == 37){
				left_keydown = true;
			}
			if(e.which == 38){
				up_keydown = true;
			}
			if(e.which == 39){
				right_keydown = true;
			}
			if(e.which == 40){
				down_keydown = true;
			}
		}).keyup(function(e){
			if(e.which == 37){
				left_keydown = false;
			}
			if(e.which == 38){
				up_keydown = false;
			}
			if(e.which == 39){
				right_keydown = false;
			}
			if(e.which == 40){
				down_keydown = false;
			}
		});
		game_start();
	}
	
	function game_start(){
		if(game_on){
			stage_top+= stage_move;
			$(".tree, .jump").each(function(){
				tree_top = parseInt($(this).css('top').replace('px',''));
				tree_left = parseInt($(this).css('left').replace('px',''));
				if(stage_top >= 600){
					tree_top = tree_top-600;
					tree_left = Math.floor(Math.random()*800);
					if(tree_top > 0){
						$(this).css({'top':tree_top + 'px'});
					}else{
						$(this).css({'top':(tree_top+1200) + 'px', 'left': tree_left + 'px'});
					}
				}
				collision = collision_check();
				if($(this).hasClass('tree')){
					if(collision && !jump_on){
						stage_move = 0;
						style -= 10;
						stage_top -= 140;
						game_on = false;
						$("#stage").animate({'margin-top':-stage_top + 'px'}, 500, function(){game_on = true;});
						$("#content_effect").css({'display':'block'}).fadeOut('fast');
					}
				}else{
					if(collision && !jump_on){
						jump_on = true;
						jump_timer = stage_move * 3;	
					}
				}
			});
			
			if(stage_top >= 600){
				stage_top = 0;
			}
			if(!jump_on){
			
				if(left_keydown){
					if(stage_move > 0){
						player_strafe -= 1;
						if(player_strafe < -3){
							player_strafe = -3;
						}
						player_left += player_strafe;
					}
				}
				if(player_left < 0){
					player_left = 0;	
				}
				
				if(right_keydown){
					if(stage_move > 0){
						player_strafe += 1;
						if(player_strafe > 3){
							player_strafe = 3;
						}
						player_left += player_strafe;
					}
				}
				if(player_left > 720){
					player_left = 720	
				}
				
				if(up_keydown){
					stage_move -= .5;
					if(stage_move < 0){
						stage_move = 0;	
					}
				}
				if(down_keydown){
					timer_on = true;
					stage_move += .5;
					if(stage_move > 25){
						stage_move = 25;	
					}
				}
			}
			
			distance += stage_move;
			
			$("#player").css({'left':player_left+'px', 'top': (player_top+stage_move * 5) + 'px'});
			$("#stage").css({'margin-top':-stage_top + 'px'});
			
		}
		if(jump_timer > 0){
			jump_timer--;
			style++;
			player_bg_position += 80;
			if(player_bg_position > 240){
				player_bg_position = 240;	
			}
			$("#player").css({'background-position': -player_bg_position + 'px 0'});
		}else{
			jump_on = false;
			player_bg_position -= 80;
			if(player_bg_position < 0){
				player_bg_position = 0;	
			}
			$("#player").css({'background-position': -player_bg_position + 'px 0'});
		}
		if(timer_on){
			time += 20;
		}
		$("#scores").html("Time: " + parseFloat(time/1000).toFixed(2) + "s<br/>Distance: " + parseFloat(distance/100).toFixed(2) + "ft<br/>Style: " + style + "pts" );
		setTimeout("game_start()",20);
		
	}
	function collision_check(){
		return ((player_left+30) > (tree_left-10) && (player_left+30) < (tree_left+70) && (player_top+40) > (tree_top - stage_top) && (player_top+40) < (tree_top - stage_top + 70));
	}
	
</script>

</body>
</html>
