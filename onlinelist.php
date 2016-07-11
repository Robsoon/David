<?php require_once 'engine/init.php'; include 'layout/overall/header.php'; ?>

<h1>Players online:</h1>
<?php
$OnlineList = online_list();
$OnlineListPerFlag = online_list_per_flag();

if ($OnlineList) {
	?>
	<div class="row"> 
		<div class="col-md-12">
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
							echo '<td style="text-align: center;"><img src="\flags\\' . $account_data['flag'] . '.png"></td>';
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
		</div>
	</div>
	<h1>Online per country:</h1>
	<div class="row"> 
		<div class="col-md-6">
			<table id="Flags" class="table table-striped table-hover" style="width: 30%;">
				<tr class="yellow">
					<th style="width: 5%;"></th>
					<th style="width: 25%;">Online:</th>
				</tr>
				<?php
					$totalPlayers = 0;
					foreach ($OnlineListPerFlag as $value) {
						$totalPlayers += $value['pais'];
						echo "<tr>";
						if ($config['country_flags'] === true  && strlen(trim($value['flag'])) > 0)
							echo '<td style="text-align: center;"> <img src="\flags\\' . $value['flag'] . '.png"> </td>';
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
					if ($totalPlayers > 1)
						echo "	<td style="text-align: center;">".$totalPlayers." players online </td>";
					else 
						echo "	<td style="text-align: center;">".$totalPlayers." player online </td>";
					echo "</tr>";
				?>
			</table>
		</div>
		<div class="col-md-6">
		</div>
	</div>
	
	<?php
} else {
	echo 'Nobody is online.';
}
?>
<?php include 'layout/overall/footer.php'; ?>
