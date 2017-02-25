</div>
<div id="footer">Copyright <?php echo date("Y", time()); ?> MB</div>
	</body>
</html>
<?php if(isset($database)) { $db->closeConnection(); }?>