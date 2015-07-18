	<div id="footer"><!--footer start-->
		
	</div><!--header end-->

<script type="text/javascript" src="<?=C('__JS__')?>jquery.js" ></script>		
<script type="text/javascript">
	$(function(){

		var Config = {
			'headerHeight': 	$('#header').outerHeight(true),
			'menuWidth':  	    $('.menu').outerWidth(true),
			'footerHeight':  	$('#footer').outerHeight(true),
			'bodyWidth':  	    $('#header').outerWidth(true),
			'bodyHeight':    	$(document).outerHeight(true),
			'containerHeight':  $(document).outerHeight(true) - $('#header').outerHeight(true) - $('#footer').outerHeight(true)
		}
		$('#container').css({'height': Config.containerHeight - 20, 'width': Config.bodyWidth - 20});
		$('.content').css({'width': $('#container').outerWidth() - Config.menuWidth - 1});

	});
</script>
</body>
</html>