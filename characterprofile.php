<?php require_once 'engine/init.php'; include 'layout/overall/header.php';
 
if ($config['log_ip']) 
{
	znote_visitor_insert_detailed_data(4);
}

if (isset($_GET['name']) === true && empty($_GET['name']) === false) 
{
	$name = getValue($_GET['name']);
	$user_id = user_character_exist($name);
	
	if ($user_id !== false) 
	{	
		
		if ($config['TFSVersion'] == 'TFS_10') 
		{
			
			$profile_data = user_character_data($user_id, 'account_id', 'name', 'level', 'vocation', 'health', 'healthmax', 'experience', 'mana', 'manamax', 'sex', 'lastlogin', 'description');
			$profile_data['online'] = user_is_online_10($user_id);
			
			if ($config['Ach']) 
			{
				$achievementPoints = mysql_select_single("SELECT SUM(`value`) AS `sum` FROM `player_storage` WHERE `key` LIKE '30___' AND `player_id`=(int)$user_id");
			}
			
		} 
		else 
		{
			$profile_data = user_character_data($user_id, 'name', 'account_id', 'level', 'vocation', 'health', 'healthmax', 'experience', 'mana', 'manamax', 'lastlogin', 'online', 'sex', 'description');
		}
		
		$profile_znote_data = user_znote_character_data($user_id, 'created', 'hide_char', 'comment');
		$account_data = user_znote_account_data($profile_data['account_id'], 'flag');
		
		$guild_exist = false;
		
		if (get_character_guild_rank($user_id) > 0) 
		{
			$guild_exist = true;
			$guild = get_player_guild_data($user_id);
			$guild_name = get_guild_name($guild['guild_id']);
		}
		
		?>
		
		<!-- PROFILE MARKUP HERE-->
		
		<!-- Profile name -->
		<h1><font class="profile_font" name="profile_font_header">Perfil: <?php echo $profile_data['name']; ?></font></h1>
			<ul class="unstyled">

<?php
/*/
/   Player character profile EQ shower
/   Based on code from CorneX
/   Written to Znote AAC by Znote.
/   Should work on all TFS versions.
/   Znote AAC 1.4+
/*/

// Item image server
$imageServer = $config['shop']['imageServer'];
$imageType = $config['shop']['imageType'];
if (count($imageType) < 3) $imageType = 'gif';
// Fetch player equipped data
$PEQD = mysql_select_multi("SELECT `player_id`, `pid`, `itemtype`, `count` FROM `player_items` WHERE `player_id`='$user_id' AND `pid`<'11'");
// If player have equipped items
if ($PEQD !== false) {
    // PEQD = Player EQ Data
    $PEQ = array(
        1 => false,
        2 => false,
        3 => false,
        4 => false,
        5 => false,
        6 => false,
        7 => false,
        8 => false,
        9 => false,
        10 => false,
    );
    // Fill player equipments array with fetched data results (PEQD)
    foreach ($PEQD as $EQ) $PEQ[$EQ['pid']] = "http://{$imageServer}/".$EQ['itemtype'].".{$imageType}";
    ?>
    <!-- Fix item positions CSS -->
    <style type="text/css">
    /* CSS by CorneX */
    .signBgrnd {
        background-image:url('eq/outfit.png');
        background-repeat:no-repeat;
        position:relative;
        float: right;
        margin:10px;
        padding:10px;
        height:153px;
        width:118px;
    }
    /* Weapon */
    .signBgrnd .wep {
        position: absolute;
        top: 56px;
        left: 6px;
        width: 32px;
        height: 32px;
    }
    .signBgrnd .wep img {
        background-image:url('eq/bg.png');
        max-width: 100%;
    }
    /* Armor */
    .signBgrnd .arm {
        position: absolute;
        top: 41px;
        left: 43px;
        width: 32px;
        height: 32px;
    }
    .signBgrnd .arm img {
        background-image:url('eq/bg.png');
        max-width: 100%;
    }
    /* Helmet */
    .signBgrnd .helm {
        position: absolute;
        top: 5px;
        left: 43px;
        width: 32px;
        height: 32px;
    }
    .signBgrnd .helm img {
        background-image:url('eq/bg.png');
        max-width: 100%;
    }
    /* legs */
    .signBgrnd .legs {
        position: absolute;
        top: 79px;
        left: 43px;
        width: 32px;
        height: 32px;
    }
    .signBgrnd .legs img {
        background-image:url('eq/bg.png');
        max-width: 100%;
    }
    /* boots */
    .signBgrnd .boots {
        position: absolute;
        top: 116px;
        left: 43px;
        width: 32px;
        height: 32px;
    }
    .signBgrnd .boots img {
        background-image:url('eq/bg.png');
        max-width: 100%;
    }
    /* ring */
    .signBgrnd .ring {
        position: absolute;
        top: 93px;
        left: 6px;
        width: 32px;
        height: 32px;
    }
    .signBgrnd .ring img {
        background-image:url('eq/bg.png');
        max-width: 100%;
    }
    /* amulet */
    .signBgrnd .amulet {
        position: absolute;
        top: 20px;
        left: 7px;
        width: 32px;
        height: 32px;
    }
    .signBgrnd .amulet img {
        background-image:url('eq/bg.png');
        max-width: 100%;
    }
    /* backpack */
    .signBgrnd .backpack {
        position: absolute;
        top: 20px;
        left: 80px;
        width: 32px;
        height: 32px;
    }
    .signBgrnd .backpack img {
        background-image:url('eq/bg.png');
        max-width: 100%;
    }
    /* shield */
    .signBgrnd .shield {
        position: absolute;
        top: 56px;
        left: 80px;
        width: 32px;
        height: 32px;
    }
    .signBgrnd .shield img {
        background-image:url('eq/bg.png');
        max-width: 100%;
    }
    /* arrow */
    .signBgrnd .arrow {
       position: absolute;
       top: 93px;
       left: 80px;
       width: 32px;
       height: 32px;
    }
    .signBgrnd .arrow img {
        background-image:url('eq/bg.png');
        max-width: 100%;
    }
    </style>
    <!-- Render HTML -->
    <div class="signBgrnd">
        <div class="helm">
            <?php
            if ($PEQ[1] != false) {
                ?>
                <img src="<?php echo $PEQ[1]; ?>" alt="Image of player helmet">
                <?php
            }
            ?>
        </div>
        <div class="amulet">
            <?php
            if ($PEQ[2] != false) {
                ?>
                <img src="<?php echo $PEQ[2]; ?>" alt="Image of player amulet">
                <?php
            }
            ?>
        </div>
        <div class="wep">
            <?php
            if ($PEQ[6] != false) {
                ?>
                <img src="<?php echo $PEQ[6]; ?>" alt="Image of player left hand">
                <?php
            }
            ?>
        </div>
        <div class="ring">
            <?php
            if ($PEQ[9] != false) {
                ?>
                <img src="<?php echo $PEQ[9]; ?>" alt="Image of player ring">
                <?php
            }
            ?>
        </div>
        <div class="arm">
            <?php
            if ($PEQ[4] != false) {
                ?>
                <img src="<?php echo $PEQ[4]; ?>" alt="Image of player armor">
                <?php
            }
            ?>
        </div>
        <div class="legs">
            <?php
            if ($PEQ[7] != false) {
                ?>
                <img src="<?php echo $PEQ[7]; ?>" alt="Image of player legs">
                <?php
            }
            ?>
        </div>
        <div class="boots">
            <?php
            if ($PEQ[8] != false) {
                ?>
                <img src="<?php echo $PEQ[8]; ?>" alt="Image of player boots">
                <?php
            }
            ?>
        </div>
        <div class="backpack">
            <?php
            if ($PEQ[3] != false) {
                ?>
                <img src="<?php echo $PEQ[3]; ?>" alt="Image of player backpack">
                <?php
            }
            ?>
        </div>
        <div class="shield">
            <?php
            if ($PEQ[5] != false) {
                ?>
                <img src="<?php echo $PEQ[5]; ?>" alt="Image of player shield">
                <?php
            }
            ?>
        </div>
        <div class="arrow">
            <?php
            if ($PEQ[10] != false) {
                ?>
                <img src="<?php echo $PEQ[10]; ?>" alt="Image of player arrow">
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}
?>    <!-- END EQ SHOWER -->

<?php

/*/
/   Znote AAC 1.4+ detailed character info (HP, MP, lvL, Exp, skills)
/   HTML code based on code from Gesior
/*/
$tableWidth = 540;
if ($config['TFSVersion'] != 'TFS_10') {
    $playerData = mysql_select_multi("SELECT `value` FROM `player_skills` WHERE `player_id`='$user_id' LIMIT 7;");
    $playerData['fist'] = $playerData[0]['value']; unset($playerData[0]);
    $playerData['club'] = $playerData[1]['value']; unset($playerData[1]);
    $playerData['sword'] = $playerData[2]['value']; unset($playerData[2]);
    $playerData['axe'] = $playerData[3]['value']; unset($playerData[3]);
    $playerData['dist'] = $playerData[4]['value']; unset($playerData[4]);
    $playerData['shield'] = $playerData[5]['value']; unset($playerData[5]);
    $playerData['fish'] = $playerData[6]['value']; unset($playerData[6]);

    $player = mysql_select_single("SELECT `health`, `healthmax`, `mana`, `manamax`, `experience`, `maglevel`, `level` FROM `players` WHERE `id`='$user_id' LIMIT 1;");
    $playerData['magic'] = $player['maglevel'];
    $playerData['exp'] = array(
        'now' => $player['experience'],
        'next' => (int)(level_to_experience($player['level']+1) - $player['experience']),
        'percent' => (int)(($player['experience'] - level_to_experience($player['level'])) / (level_to_experience($player['level']+1) - $player['experience']) * 100)
    );
    $playerData['health'] = array(
        'now' => $player['health'],
        'max' => $player['healthmax'],
        'percent' => (int)($player['health'] / $player['healthmax'] * 100),
    );
    $playerData['mana'] = array(
        'now' => $player['mana'],
        'max' => $player['manamax'],
        'percent' => (int)($player['mana'] / $player['manamax'] * 100),
    );
} else {
    $player = mysql_select_single("SELECT `health`, `healthmax`, `mana`, `manamax`, `experience`, `skill_fist`, `skill_club`, `skill_sword`, `skill_axe`, `skill_dist`, `skill_shielding`, `skill_fishing`, `maglevel`, `level` FROM `players` WHERE `id`='$user_id' LIMIT 1;");
    $playerData = array(
        'fist' => $player['skill_fist'],
        'club' => $player['skill_club'],
        'sword' => $player['skill_sword'],
        'axe' => $player['skill_axe'],
        'dist' => $player['skill_dist'],
        'shield' => $player['skill_shielding'],
        'fish' => $player['skill_fishing'],
        'magic' => $player['maglevel'],
        'exp' => array(
            'now' => $player['experience'],
            'next' => (int)(level_to_experience($player['level']+1) - $player['experience']),
            'percent' => (int)(($player['experience'] - level_to_experience($player['level'])) / (level_to_experience($player['level']+1) - $player['experience']) * 100)
        ),
        'health' => array(
            'now' => $player['health'],
            'max' => $player['healthmax'],
            'percent' => (int)($player['health'] / $player['healthmax'] * 100),
        ),
        'mana' => array(
            'now' => $player['mana'],
            'max' => $player['manamax'],
            'percent' => (int)($player['mana'] / $player['manamax'] * 100),
        )
    );
}
// Incase they have more health/mana than they should due to equipment bonus etc
if ($playerData['exp']['percent'] > 100) $playerData['exp']['percent'] = 100;
if ($playerData['health']['percent'] > 100) $playerData['health']['percent'] = 100;
if ($playerData['mana']['percent'] > 100) $playerData['mana']['percent'] = 100;
//data_dump($playerData, false, "Player Data");
?>
<!-- PLAYER SKILLS TABLE -->
<table cellspacing="1" cellpadding="4" style="width: <?php echo $tableWidth; ?>px;">
    <tr class="yellow">
        <th>Fist</th>
        <th>Club</th>
        <th>Sword</th>
        <th>Axe</th>
        <th>Dist</th>
        <th>Shield</th>
        <th>Fish</th>
        <th>Magic</th>
    </tr>
    <tr>
        <td bgcolor="#D4C0A1" align="left">
            <?php echo $playerData['fist']; ?>
        </td>
        <td bgcolor="#D4C0A1" align="left">
            <?php echo $playerData['club']; ?>
        </td>
        <td bgcolor="#D4C0A1" align="left">
            <?php echo $playerData['sword']; ?>
        </td>
        <td bgcolor="#D4C0A1" align="left">
            <?php echo $playerData['axe']; ?>
        </td>
        <td bgcolor="#D4C0A1" align="left">
            <?php echo $playerData['dist']; ?>
        </td>
        <td bgcolor="#D4C0A1" align="left">
            <?php echo $playerData['shield']; ?>
        </td>
        <td bgcolor="#D4C0A1" align="left">
            <?php echo $playerData['fish']; ?>
        </td>
        <td bgcolor="#D4C0A1" align="left">
            <?php echo $playerData['magic']; ?>
        </td>
    </tr>
</table>
<!-- PLAYER INFO TABLE -->
<table cellspacing="1" cellpadding="4" style="width: <?php echo $tableWidth; ?>px;">
    <tr>
        <td bgcolor="#F1E0C6" align="left" width="20%">
            <b>Player HP:</b>
        </td>
        <td bgcolor="#F1E0C6" align="left">
            <?php echo $playerData['health']['now'].'/'.$playerData['health']['max']; ?>
            <div style="width: 100%; height: 3px; border: 1px solid #000;">
                <div style="background: red; width: <?php echo $playerData['health']['percent']; ?>%; height: 3px;">
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td bgcolor="#D4C0A1" align="left">
            <b>Player MP:</b>
        </td>
        <td bgcolor="#D4C0A1" align="left">
            <?php echo $playerData['mana']['now'].'/'.$playerData['mana']['max']; ?>
            <div style="width: 100%; height: 3px; border: 1px solid #000;">
                <div style="background: blue; width: <?php echo $playerData['mana']['percent']; ?>%; height: 3px;">
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td bgcolor="#D4C0A1" align="left">
            <b>Player XP:</b>
        </td>
        <td bgcolor="#D4C0A1" align="left">
            <?php echo number_format($playerData['exp']['now'], 0, "", " "); ?> Experience.
        </td>
    </tr>
    <tr>
        <td bgcolor="#F1E0C6" align="left">
            <b>To Next Lvl:</b>
        </td>
        <td bgcolor="#F1E0C6" align="left">
            Need <b><?php echo number_format($playerData['exp']['next'], 0, "", " "); ?> experience (<?php echo 100-$playerData['exp']['percent']; ?>%)</b> to Level <b><?php echo $player['level']+1; ?></b>.
            <div title="99.320604545 %" style="width: 100%; height: 3px; border: 1px solid #000;">
                <div style="background: red; width: <?php echo $playerData['exp']['percent']; ?>%; height: 3px;"></div>
            </div>
        </td>
    </tr>
</table>
<!-- END detailed character info -->

				<?php 
				if ($config['country_flags'])
				{ ?>
					<!-- Player country data -->
					<li><font class="profile_font" name="profile_font_country">Country: <?php echo '<img src="\flags\\' . $account_data['flag'] . '.png">'; ?></font></li><?php
				} ?>

				<!-- Player male / female -->
				
				
				<li>
				

					<font class="profile_font" name="profile_font_level">Sex: 
						<?php 
						if ($profile_data['sex'] == 1) 
						{
							echo 'Male';
						} 
						else 
						{
							echo 'Female';
						}
						?>
					</font>
				</li>
				
				<!-- Player level -->
				<li>
					<font class="profile_font" name="profile_font_level">Level: 
						<?php 
						echo $profile_data['level']; 
						?>
					</font>
				</li>
				
				<!-- Resets -->
				<li>
					<font class="profile_font" name="profile_font_level">Resets: 
						<?php						
							echo strlen($profile_data['description']) > 0 ? preg_replace("/[^0-9]/","",$profile_data['description']) 
																		  : "0"; 
						?>
					</font>
				</li>
				
				
				<!-- Player vocation -->
				<li><font class="profile_font" name="profile_font_vocation">Vocation: <?php echo vocation_id_to_name($profile_data['vocation']); ?></font></li>
				
				<!-- Player guild -->
				<?php 
				if ($guild_exist) 
				{
				?>
				<li>
					<font class="profile_font" name="profile_font_vocation">
						<b><?php echo $guild['rank_name']; ?> </b> of <a href="guilds.php?name=<?php echo $guild_name; ?>"><?php echo $guild_name; ?></a>
					</font>
				</li>
				<?php
				}
				?>
				<!-- Player last login -->
				<li>
					<font class="profile_font" name="profile_font_lastlogin">Last Login: 
					<?php
					if ($profile_data['lastlogin'] != 0) 
					{
						echo getClock($profile_data['lastlogin'], true, true);
					} 
					else 
					{
						echo 'Never.';
					}
					?>
					</font>
				</li>
				
				<!-- Achievement start -->
				<?php 
				if ($config['Ach']) 
				{ 
					foreach ($achievementPoints as $achievement) 
					{
						//if player doesn't have any achievement points it won't echo the line below.
						if ($achievement > 0)
						{
							echo '<li>Achievement Points: ' . $achievement . '</li>'; 
						}
					}
				}
				?>
				<!-- Achievement end -->
				
				<!-- Display house start -->
				<?php
				if ($config['TFSVersion'] !== 'TFS_02') 
				{
					$townid = ($config['TFSVersion'] === 'TFS_03') ? 'town' : 'town_id';
					$houses = mysql_select_multi("SELECT `id`, `owner`, `name`, `$townid` AS `town_id` FROM `houses` WHERE `owner` = $user_id;");
					
					if ($houses) 
					{
						$playerlist = array();
						foreach ($houses as $h) 
						{
							if ($h['owner'] > 0)
							{
								$playerlist[] = $h['owner'];
							}
								
							if ($profile_data['id'] = $h['owner']) 
							{
							?>
								<li>House: <?php echo $h['name']; ?>, <?php 
									foreach ($config['towns'] as $key => $value) 
									{
										if ($key == $h['town_id']) 
										{
											echo $value;
										}
									}
							 ?>
								</li>
							<?php
							}
						}
					}
				}
				?>
				<!-- Display house end -->
				
				<!-- Display player status -->
				<li><font class="profile_font" name="profile_font_status">Status:</font> <?php
				if ($config['TFSVersion'] == 'TFS_10') 
				{
					if ($profile_data['online']) 
					{
						echo '<font class="profile_font" name="profile_font_online" color="green"><b>ONLINE</b></font>';
					} 
					else 
					{
						echo '<font class="profile_font" name="profile_font_online" color="red"><b>OFFLINE</b></font>';
					}
				} 
				else 
				{
					if ($profile_data['online']) 
					{
						echo '<font class="profile_font" name="profile_font_online" color="green"><b>ONLINE</b></font>';
					} 
					else 
					{
						echo '<font class="profile_font" name="profile_font_online" color="red"><b>OFFLINE</b></font>';
					}
				}
				?>
				</li>
				<!-- Display player status end -->
				
				<!-- Player created -->
				<li><font class="profile_font" name="profile_font_created">Created: <?php echo getClock($profile_znote_data['created'], true); ?></font></li>
				
				<!-- Player Comment -->
				<?php
				//if player doesnt have set a comment dont show it.
				if (!empty($profile_znote_data['comment']))
				{ ?>
					<li>
						<font class="profile_font" name="profile_font_comment">Comment:</font><br>
						<textarea name="profile_comment_textarea" cols="70" rows="10" readonly="readonly" class="span12"><?php echo $profile_znote_data['comment']; ?></textarea>
					</li>
				<?php
				}
				?>
				
				<!-- Achievements start -->
				<?php if ($config['Ach']) 
				{ ?>			
					<h3 class="header-ok">Achievements</h3>
					<div id="accordion">
						<h3>Show/hide player achievements</h3>
						<div>
							<table class="table table-striped table-bordered">
								<tbody>
									<style>
										#secondD {
											margin-left:0px;
										}
									</style>
									<?php
									foreach ($config['achievements'] as $key => $achiv) 
									{
										$uery = mysql_select_single("SELECT `player_id`, `value`, `key` FROM `player_storage` WHERE `player_id`='$user_id' AND `key`='$key' LIMIT 1;");
										if (!empty($uery) || $uery !== false) 
										{
											foreach ($uery as $luery) 
											{
												if ($luery == $key) 
												{
													if (!array_key_exists($key, $achiv)) 
													{
														echo '<tr><td width="17%">' .$achiv[0]. '</td><td>' .$achiv[1]. '</td>';
														
														if (!isset($achiv['secret'])) 
														{
															echo '<td><img id="secondD" src="http://img04.imgland.net/PuMz0mVqSG.gif"></td>';
														}
														
														echo '<td>'. $achiv['points'] .'</td>';
														echo '<tr>';
													}
												}
											}
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div><br>
				<?php
				} 
				?>
				<!-- Achievements end -->
				
				<!-- DEATH LIST -->
				<li>
					<b>Death List:</b><br>
					<?php
					if ($config['TFSVersion'] == 'TFS_02') 
					{
						$array = user_fetch_deathlist($user_id);
						if ($array) 
						{
						?>
							<ul>
							<?php
							// Design and present the list
							foreach ($array as $value) 
							{ ?>
								<li>
								<?php
								$value['time'] = getClock($value['time'], true);
								
								if ($value['is_player'] == 1) 
								{
									$value['killed_by'] = 'player: <a href="characterprofile.php?name='. $value['killed_by'] .'">'. $value['killed_by'] .'</a>';
								} 
								else 
								{
									$value['killed_by'] = 'monster: '. $value['killed_by'] .'.';
								}
								
								echo '['. $value['time'] .'] Killed at level '. $value['level'] .' by '. $value['killed_by']; ?>
								</li>
							<?php
							}
							?>
							</ul>
							<?php
						} 
						else 
						{
							echo '<b><font color="green">Esse jogador nunca morreu.</font></b>';
						}
					} 
					else if ($config['TFSVersion'] == 'TFS_10') 
					{
						$deaths = mysql_select_multi("SELECT 
							`player_id`, `time`, `level`, `killed_by`, `is_player`, 
							`mostdamage_by`, `mostdamage_is_player`, `unjustified`, `mostdamage_unjustified` 
							FROM `player_deaths` 
							WHERE `player_id`=$user_id ORDER BY `time` DESC LIMIT 10;");

						if ($deaths)
						{ 
							foreach ($deaths as $d) 
							{
								?>
								<li>
									<?php echo "<b>".getClock($d['time'], true, true)."</b>";
									$lasthit = ($d['is_player']) ? "<a href='characterprofile.php?name=".$d['killed_by']."'>".$d['killed_by']."</a>" : $d['killed_by'];
									echo ": Killed at level ".$d['level']." by $lasthit";
									if ($d['unjustified']) 
									{echo " <font color='red' style='font-style: italic;'>(unjustified)</font>";}
								
									$mostdmg = ($d['mostdamage_by'] !== $d['killed_by']) ? true : false;
									
									if ($mostdmg) 
									{
										$mostdmg = ($d['mostdamage_is_player']) ? "<a href='characterprofile.php?name=".$d['mostdamage_by']."'>".$d['mostdamage_by']."</a>" : $d['mostdamage_by'];
										echo "<br>and by $mostdmg.";
										
										if ($d['mostdamage_unjustified']) 
										{ echo " <font color='red' style='font-style: italic;'>(unjustified)</font>"; }
									} 
									else 
									{ echo " <b>(soloed)</b>"; }
									?>
								</li>
								<?php
							}
						}
						else 
						{
							echo '<b><font color="green">Esse jogador nunca morreu.</font></b>'; 
						}
					} 
					else if ($config['TFSVersion'] == 'TFS_03') 
					{
						//mysql_select_single("SELECT * FROM players WHERE name='TEST DEBUG';");
						$array = user_fetch_deathlist03($user_id);
						
						if ($array) 
						{?>
							<ul>
								<?php
								// Design and present the list
								foreach ($array as $value) 
								{ ?>
									<li>
									<?php
									$value[3] = user_get_killer_id(user_get_kid($value['id']));
									
									if ($value[3] !== false && $value[3] >= 1) 
									{
										$namedata = user_character_data((int)$value[3], 'name');
										
										if ($namedata !== false) 
										{
											$value[3] = $namedata['name'];
											$value[3] = 'player: <a href="characterprofile.php?name='. $value[3] .'">'. $value[3] .'</a>';
										} 
										else 
										{
											$value[3] = 'deleted player.';
										}
									} 
									else 
									{
										$value[3] = user_get_killer_m_name(user_get_kid($value['id']));
										
										if ($value[3] === false) 
										{ $value[3] = 'deleted player.'; }
									}
									
									echo '['. getClock($value['date'], true) .'] Killed at level '. $value['level'] .' by '. $value[3];
									echo '</li>';
								}
								?>
							</ul>
							<?php
						} 
						else { echo '<b><font color="green">This player never died.</font></b>'; }
					}
					?>
				</li>
				<!-- END DEATH LIST -->
				
				<!-- QUEST PROGRESSION -->
				<?php
				$totalquests = 0;
				$completedquests = 0;
				$firstrun = 1;
				
				if ($config['EnableQuests'] == true) 
				{
					$sqlquests =  mysql_select_multi("SELECT `player_id`, `key`, `value` FROM player_storage WHERE `player_id` = $user_id");
					foreach ($config['quests'] as $cquest) 
					{
						$totalquests = $totalquests + 1;
						foreach ($sqlquests as $dbquest) 
						{
							if ($cquest[0] == $dbquest['key'] && $cquest[1] == $dbquest['value']) 
							{
								$completedquests = $completedquests + 1;
							}
						}
						if ($cquest[3] == 1) 
						{
							if ($completedquests != 0) 
							{
								if ($firstrun == 1) 
								{
								?>
									<li>
										<b> Quest progression </b>
										<table id="characterprofileQuest" class="table table-striped table-hover">
											<tr class="yellow">
												<th>Quest:</th>
												<th>progression:</th>
											</tr>
								<?php
								$firstrun = 0;
								}
								$completed = $completedquests / $totalquests * 100;
								?>
								<tr>
									<td><?php echo $cquest[2]; ?></td>
									<td id="progress">
										<span id="percent"><?php echo round($completed); ?>%</span>
										<div id="bar" style="width: '.$completed.'%"></div>
									</td>
								</tr>
								<?php
							}
							
							$completedquests = 0;
							$totalquests = 0;
							
						}
					}
				}
				
				if ($firstrun == 0) 
				{ ?>
					</table></li>
				<?php
				}
				?>
				<!-- END QUEST PROGRESSION -->
				
				<!-- CHARACTER LIST -->
				<?php
				if (user_character_hide($profile_data['name']) != 1 && user_character_list_count(user_character_account_id($name)) > 1) 
				{
				?>
					<li>
						<b>Other visible characters in this account:</b><br>
						<?php
						$characters = user_character_list(user_character_account_id($profile_data['name']));
						// characters: [0] = name, [1] = level, [2] = vocation, [3] = town_id, [4] = lastlogin, [5] = online
						if ($characters && count($characters) > 0) 
						{
							?>
							<table id="characterprofileTable" class="table table-striped table-hover">
								<tr class="yellow">
									<th>Name:</th>
									<th>Level:</th>
									<th>Vocation:</th>
									<th>Last Login:</th>
									<th>Status:</th>
								</tr>
								
								<?php
								// Design and present the list
								foreach ($characters as $char) 
								{
									if ($char['name'] != $profile_data['name']) 
									{
										if (hide_char_to_name(user_character_hide($char['name'])) != 'hidden') 
										{ ?>
											<tr>
												<td><a href="characterprofile.php?name=<?php echo $char['name']; ?>"><?php echo $char['name']; ?></a></td>
												<td><?php echo (int)$char['level']; ?></td>
												<td><?php echo $char['vocation']; ?></td>
												<td><?php echo $char['lastlogin']; ?></td>
												<td><?php echo $char['online']; ?></td>
											</tr>
										<?php
										}
									}
								}
							?>
							</table>
							<?php
						} 
						else 
						{
							echo '<b><font color="green">Esse jogar nunca morreu.</font></b>';
						}
						?>
					</li>
				<?php
				}
				?>
				<!-- END CHARACTER LIST -->
				
				<li>
					<font class="profile_font" name="profile_font_share_url">Address: 
					<a href="
						<?php 
						if ($config['htwrite']) 
						{ 
							echo "http://" . $_SERVER['HTTP_HOST']."/" . $profile_data['name']; 
						}
						else 
						{ 
							echo "http://" . $_SERVER['HTTP_HOST'] . "/characterprofile.php?name=" . $profile_data['name']; 
						}	
						?>">
						<?php
						if ($config['htwrite']) 
						{ 
							echo "http://".$_SERVER['HTTP_HOST']."/". $profile_data['name']; 
						}
						else 
						{ 
							echo "http://".$_SERVER['HTTP_HOST']."/characterprofile.php?name=". $profile_data['name']; 
						}
						?>
				</a></font></li>
			</ul>
		<!-- END PROFILE MARKUP HERE-->
		
		<?php
	} 
	else 
	{
		echo htmlentities(strip_tags($name, ENT_QUOTES)) . ' does not exist.';
	}
} 
else 
{
	header('Location: index.php');
}

include 'layout/overall/footer.php'; ?>
