<?php require_once 'engine/init.php'; include 'layout/overall/header.php'; ?>

<h1>Who is online?</h1>
<?php
$OnlineList = online_list();
$OnlineListPerFlag = online_list_per_flag();

if ($OnlineList) {
	?>
	 
	<table id="Flags" class="table table-striped table-hover">
		<tr class="yellow">
			<th></th>
			<th>Online:</th>
		</tr>
		<?php
			$totalPlayers = 0;
			foreach ($OnlineListPerFlag as $value) {
				$totalPlayers += $value['pais'];
				echo "<tr>";
				if ($config['country_flags'] === true  && strlen(trim($value['flag'])) > 0)
					echo "	<td> <img src='\flags\\'" . $value['flag'].".png> </td>";
				else 
					echo "<td> </td>";
				if ($value['pais'] > 1)
					echo "  <td>".$value['pais']." players </td>";
				else 
					echo "  <td>".$value['pais']." player </td>";
				echo "</tr>";
			}
			// totalPlayers
			echo "<tr>";
			echo "	<td><b>Total:</b></td>";
			echo "	<td>".$totalPlayers." </td>";
			echo "</tr>";
		
		?>
	</table>
	
	
	<table id="onlinelistTable" class="table table-striped table-hover">
		<tr class="yellow">
			<?php
				if ($config['country_flags'])
					echo '<th></th>';  
			?>
			<th>Name:</th>
			<th>Guild:</th>
			<th>Resets:</th>
			<th>Level:</th>
			<th>Vocation:</th>
		</tr>
			<?php
			foreach ($OnlineList as $value) {
				$account_data = user_znote_account_data($value['account_id'], 'flag');
				$url = url("characterprofile.php?name=". $value['name']);
				$resets = preg_replace("/[^0-9]/","",$value['description']);
				
				echo '<tr class="special" onclick="javascript:window.location.href=\'' . $url . '\'">';
				if ($config['country_flags'] === true  && strlen(trim($account_data['flag'])) > 0)
					echo '<td><img src="\flags\\' . $account_data['flag'] . '.png"></td>';
				else 
					echo '<td> </td>';
				echo '<td><a href="characterprofile.php?name='. $value['name'] .'">'. $value['name'] .'</a></td>';
				if (!empty($value['gname'])) echo '<td><a href="guilds.php?name='. $value['gname'] .'">'. $value['gname'] .'</a></td>'; else echo '<td></td>'; 
				echo '<td>'.$resets.'</td>';
				echo '<td>'. $value['level'] .'</td>';
				echo '<td>'. vocation_id_to_name($value['vocation']) .'</td>';
				echo '</tr>';
			}
			?>
	</table>

	<?php
} else {
	echo 'Nobody is online.';
}
?>
<?php include 'layout/overall/footer.php'; ?>
