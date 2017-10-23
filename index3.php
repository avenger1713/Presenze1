<?php 

function draw_calendar($Employee,$month,$year) {
	
	if (!$link =  mysqli_connect("xxxx", "xxxx", "xxxx","xxxx")) {
		echo 'Could not connect to mysql';
		exit;
	}
 
	$sql = 'SELECT * FROM MGP_Utenti WHERE ID="'.$Employee.'" AND disable=0';
	$result = mysqli_query($link, $sql);

	$DayInMonth = date ("t", mktime (0,0,0,$month,01,$year));
	
	$WeekDay = array( 
        '0' => 'Domenica',
        '1' => 'Lunedi',
        '2' => 'Martedi',
        '3' => 'Mercoledi',
        '4' => 'Giovedi',
        '5' => 'Venerdi',
        '6' => 'Sabato',
    ); 
	
	
	if (mysqli_num_rows($result)==0) {
		echo "errore richiesta";
	} else {
		$row = mysqli_fetch_assoc($result);
		
		$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
		$calendar.= '<tr class="calendar-row First">';
		$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		$calendar.= '<td class="calendar-day-np" colspan="7" style="text-align:center;font-size:1.5em;background-color:#00000"><b>'.$month.'-'.$year.'</b></td>';
		$calendar.= '</tr>';
		$calendar.= '<tr class="calendar-row First">';
		$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		$calendar.= '<td class="calendar-day-np" colspan="7" style="text-align:center;font-size:1.5em;background-color:#00000"><b>'.$row['Cognome'].' '.$row['Nome'].'</b></td>';
		$calendar.= '</tr>';
		$calendar.= '<tr class="calendar-row">';
		$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		$calendar.= '<td class="calendar-day-np"><b>Totale:</b></td>';
		$calendar.= '</tr>';
		
		for ($i=1; $i<= $DayInMonth; $i++) {
			unset($orari);
			$day = $i;
			$Giorno = $WeekDay[date ("w", mktime (0,0,0,$month,$day,$year))];
			if ($Giorno == 'Domenica') {
				$calendar.= '<tr class="calendar-row" style="background-color:#e1e1e1">';
			} else {
				$calendar.= '<tr class="calendar-row">';
			}
			$calendar.= '<td class="calendar-day-np">'.$Giorno.'</td>';
			$calendar.= '<td class="calendar-day-np">'.$year.'-'.$month.'-'.$day.'</td>';
			
			$sql1 = 'SELECT * FROM MGP_Presenze WHERE ID_Utente="'.$row['ID'].'" AND DATE(Ora) = "'.$year.'-'.$month.'-'.$day.'" ORDER BY Ora ASC ';
			$result1 = mysqli_query($link, $sql1);
			$NumTimbrature = mysqli_num_rows($result1);
			if (mysqli_num_rows($result1)<>0) {
				while ($row1 = mysqli_fetch_assoc($result1)) {
					$orari[]= $row1['Ora'];
					$note[]= $row1['Note'];
				}
										
				$datetime0 = new DateTime($orari[0]);
				$datetime1 = new DateTime($orari[1]);
				$interval1 = $datetime0->diff($datetime1);
				$ore1 = $interval1->format('%h');
				$minuti1 = $interval1->format('%i');
				
				$datetime0 = new DateTime($orari[3]);
				$datetime1 = new DateTime($orari[2]);
				$interval1 = $datetime1->diff($datetime0);
				$ore2 = $interval1->format('%h');
				$minuti2 = $minuti + $interval1->format('%i');
				
				$datetime0 = new DateTime($orari[5]);
				$datetime1 = new DateTime($orari[4]);
				$interval1 = $datetime1->diff($datetime0);
				$ore3 = $interval1->format('%h');
				$minuti3 = $minuti + $interval1->format('%i');

				$ore = $ore1 + $ore2 + $ore3;
				$minuti = $minuti1 + $minuti2 + $minuti3; 
				
				if (mysqli_num_rows($result1) & 1) { 
					$calendar.= '<td class="calendar-day-np"> timbrature discordanti</td>';
				} else {
					$calendar.= '<td class="calendar-day-np">'.$ore.':'.$minuti.'</td>';
				}
				$calendar.= '<td class="calendar-day-np">'.$row1['Note'].'</td>';

			}
		
		}
		
/*
		while ($row = mysqli_fetch_assoc($result)) {
			unset($orari);
			$calendar.= '<tr class="calendar-row">';
			
			$sql1 = 'SELECT * FROM MGP_Presenze WHERE ID_Utente="'.$row['ID'].'" AND DATE(Ora) = "'.$year.'-'.$month.'" ORDER BY Ora ASC ';
			$result1 = mysqli_query($link, $sql1);
			
			if (mysqli_num_rows($result1)<>0) {
				while ($row1 = mysqli_fetch_assoc($result1)) {
					$orari[]= $row1['Ora'];
				}
										
				$datetime0 = new DateTime($orari[0]);
				$datetime1 = new DateTime($orari[1]);
				$interval1 = $datetime0->diff($datetime1);
				$ore1 = $interval1->format('%h');
				$minuti1 = $interval1->format('%i');
				
				$datetime0 = new DateTime($orari[3]);
				$datetime1 = new DateTime($orari[2]);
				$interval1 = $datetime1->diff($datetime0);
				$ore2 = $interval1->format('%h');
				$minuti2 = $minuti + $interval1->format('%i');
				
				$datetime0 = new DateTime($orari[5]);
				$datetime1 = new DateTime($orari[4]);
				$interval1 = $datetime1->diff($datetime0);
				$ore3 = $interval1->format('%h');
				$minuti3 = $minuti + $interval1->format('%i');

				$ore = $ore1 + $ore2 + $ore3;
				$minuti = $minuti1 + $minuti2 + $minuti3; 
				
				$calendar.= '<td class="calendar-day-np">'.$ore.':'.$minuti.'</td>';

			}
		}
		$calendar.= '</tr>'; 
	}
		$calendar.= '</table>';*/
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
	echo draw_calendar($_GET['dip'],$data[0],$data[1]);
?>
</body>
</html>