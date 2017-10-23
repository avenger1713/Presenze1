<?php 

function draw_calendar($day,$month,$year) {
	
	if (!$link =  mysqli_connect("212.25.183.222", "usr_gest_pres", "Ul3yg9?5","admin_gest_presenze")) {
		echo 'Could not connect to mysql';
		exit;
	}
 
	$sql = 'SELECT * FROM MGP_Utenti WHERE disable=0';
	$result = mysqli_query($link, $sql);

	if (mysqli_num_rows($result)==0) {
		echo "errore richiesta";
	} else {
		$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
		$calendar.= '<tr class="calendar-row First">';
		$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		$calendar.= '<td class="calendar-day-np" colspan="7" style="text-align:center;font-size:1.5em;background-color:#00000"><b>'.$day.'-'.$month.'-'.$year.'</b></td>';
		$calendar.= '</tr>';
		$calendar.= '<tr class="calendar-row">';
		$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		$calendar.= '<td class="calendar-day-np"><b>E</b></td>';
		$calendar.= '<td class="calendar-day-np"><b>U</b></td>';
		$calendar.= '<td class="calendar-day-np"><b>E</b></td>';
		$calendar.= '<td class="calendar-day-np"><b>U</b></td>';
		$calendar.= '<td class="calendar-day-np"><b>E</b></td>';
		$calendar.= '<td class="calendar-day-np"><b>U</b></td>';
		$calendar.= '<td class="calendar-day-np"><b>Totale:</b></td>';
		$calendar.= '</tr>';
		
		unset($orari);
		while ($row = mysqli_fetch_assoc($result)) {
				unset($orari);
				$calendar.= '<tr class="calendar-row">';
				$calendar.= '<td class="calendar-day-np">'.$row['Nome'].' '.$row['Cognome'].'</td>';
				$sql1 = 'SELECT * FROM MGP_Presenze WHERE ID_Utente="'.$row['ID'].'" AND DATE(Ora) = "'.$year.'-'.$month.'-'.$day.'" ORDER BY Ora ASC LIMIT 6';
					$result1 = mysqli_query($link, $sql1);
					if (mysqli_num_rows($result1)<>0) {
						while ($row1 = mysqli_fetch_assoc($result1)) {
							$orari[]= $row1['Ora'];
							$calendar.= '<td class="calendar-day-np">&nbsp;'.$row1['Ora'];
							if ($row1['Note']) { $calendar.= '<br/>('.$row1['Note'].')'; }
							$calendar.= '</td>';
						}
						switch (mysqli_num_rows($result1)) {
							case 0:
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
							break;
							case 1:
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
							break;
							case 2:
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
							break;
							case 3:
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
							break;
							case 4:
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
							break;
							case 5:
								$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
							break;
						}
						
						$datetime0 = new DateTime($orari[0]);
						$datetime1 = new DateTime($orari[1]);
						$interval1 = $datetime0->diff($datetime1);
						$tempo1 = $interval1->format('%h:%i');
						//$ore1 = $interval1->format('%h');
						//$minuti1 = $interval1->format('%i');
						
						$datetime0 = new DateTime($orari[3]);
						$datetime1 = new DateTime($orari[2]);
						$interval1 = $datetime1->diff($datetime0);
						$tempo2 = $interval1->format('%h:%i');
						//$ore2 = $interval1->format('%h');
						//$minuti2 = $minuti + $interval1->format('%i');
						
						$Temp = $tempo1->add($tempo2);
						
						$datetime0 = new DateTime($orari[5]);
						$datetime1 = new DateTime($orari[4]);
						$interval1 = $datetime1->diff($datetime0);
						$tempo3 = $interval1->format('%h:%i');
						//$ore3 = $interval1->format('%h');
						//$minuti3 = $minuti + $interval1->format('%i');
						
						$TempTot = $Temp->add($tempo3);
						
						//$tempo = $tempo1 + $tempo2 + $tempo3;
						//$ore = $ore1 + $ore2 + $ore3;
						//$minuti = $minuti1 + $minuti2 + $minuti3; 
						
						
						if (mysqli_num_rows($result1) & 1) { 
							$calendar.= '<td class="calendar-day-np"> timbrature discordanti</td>';
						} else  { 
							$calendar.= '<td class="calendar-day-np">'.$TempTot.'</td>';
							//$calendar.= '<td class="calendar-day-np">'.$tempo.'</td>';
						}
					}
				$calendar.= '</tr>';
		}
		$calendar.= '</table>';
	}
	
		return $calendar;

	
}


?>
 
<style>
table.calendar { background-color:#f1f1f1;width:100%;border-style: 1px solid #fff; }
.First { border-style: 1px solid #fff; }
td {}
tr {}
</style>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
</head>
<body>
<?php 
	$data = explode('-',$_GET['data']);
	echo draw_calendar($data[0],$data[1],$data[2]);
?>
</body>
</html>