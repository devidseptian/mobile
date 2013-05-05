<!DOCTYPE html>
<html lang="en">
	<head>
		<title>mobile</title>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.css" />  
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.js"></script>
		
	</head>
<style type="text/css">
.ui-icon-devid{
background:green;
border:1p solid black;
}
</style>
<body>
	<div data-role="page">
		<div data-role="header">
			<h1><?php bloginfo('title');?></h1>
		</div>
		
		<div data-role="content" data-theme="a" data-counttheme="e">
			<ul data-role="listview">
			<?php if(have_posts()): while(have_posts()) : the_post();?>
			<li>
			<?php the_post_thumbnail(); ?> 
				<h2>
					<a href="<?php the_permalink();?>" data-transition="flip"><?php the_title(); ?></a>
					<span class="ui-li-count"><?php comments_number(0, 1, '%');?></span>
				</h2>
				<article><?php echo the_excerpt(); ?></article>
			</li>
			
			<?php endwhile; endif; ?>
			</ul>
		</div>
		<div data-role="footer">
		<nav data-role="navbar">
			<ul>
				<li><a href="#" data-icon="gear">setting</a></li>
				<li><a href="#" data-icon="arrow-r">page</a></li>
				<li><a href="#" data-icon="star">Comment</a></li>
			</ul>
		</nav>
			<h4>By Devid Ganteng Baka yaroo</h4>
		</div>
	</div>
	
		
	
</body>
</html>