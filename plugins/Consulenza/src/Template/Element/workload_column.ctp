<td title="<?php echo $jobName; ?>"> 
	<?php 
	$k = 'key_' . $jobId;
	if(isset($rData[$k])) { echo $rData[$k]; } else { echo '0'; }				
	?>
</td>