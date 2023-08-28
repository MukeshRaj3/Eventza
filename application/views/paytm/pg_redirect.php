
<html>
<head>
<title>Merchant Check Out Page</title>
</head>
<body>
	<center><h1>Please do not refresh this page...</h1></center>
		<form method="post" action="<?php echo PAYTM_TXN_URL; ?>" name="f1">
		<table border="1">
			<tbody>
			<?php
			foreach($list as $name => $value) {
				echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
			}
			?>
			/
			<input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">
			<input type="hidden" name="PaymentId" value="<?php echo $PaymentId ?>">
			<input type="hidden" name="txn_id" value="<?php echo $txn_id ?>">
			</tbody>
		</table>
		<script type="text/javascript">
			document.f1.submit();
		</script>
	</form>
</body>
</html>