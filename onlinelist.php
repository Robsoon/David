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
			foreach ($OnlineListPerFlag as $value) {
				echo "<tr>";
				echo "	<td> <img src='\flags\\'" . $value['flag'].".png> </td>";
				echo "  <td>".$value['pais']."</td>";
				echo "</tr>";
			}
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
				if ($config['country_flags'])
					echo '<td><img src="\flags\\' . $account_data['flag'] . '.png"></td>';			
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
