 <tr>
	<td><?=$rData['cognome'] . ' ' . $rData['nome']?></td>
	<td title="RIGHE CONTABILI"><?=$rData['righe']?></td>
	<?php foreach($jobList as $job) {
		echo $this->element('workload_column',['rData'=>$rData['load'], 'jobId'=> $job['id'], 'jobName'=> $job['name']]);
	} ?>
 </tr>