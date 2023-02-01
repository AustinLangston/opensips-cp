<?php
/*
 * Copyright (C) 2011 OpenSIPS Project
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for 
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once("../../../common/cfg_comm.php");
require_once("../../../common/forms.php");
require("../../../../config/boxes.global.inc.php");
require_once("functions.js");
 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
	$current_tool=$_SESSION['current_tool'];
	$sql = "select * from ".$table." where id=?";
	$stm = $link->prepare($sql);

	if ($stm === false) {
	  	die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	$stm->execute( array($id) );
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
        $index_row=0;
?> 

<form action="<?=$page_name?>?action=modify_params&db_id=<?=$id?>" method="post">
<?php csrfguard_generate(); ?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
 <td colspan="3" height="10" class="mainTitle">Edit configuration</td>

 </tr>
  <?php
	foreach ($_SESSION['db_config'] as $i => $configuration) {
		if ($i == $id) 
			$selected_config = $configuration;
	}
	
	form_generate_input_text("Configuration name", "Name of the configuration",
	 "config_name", "n", $selected_config['config_name'], 64, null);
	form_generate_input_text("DB host", "Database host", "db_host", "n",
	 $selected_config['db_host'], 64, null);
	form_generate_input_text("DB port", "Database port", "db_port", "y",
	 $selected_config['db_port'], 64, '^([0-9]\+)$');
	form_generate_input_text("DB user", "Database user", "db_user", "n",
		$selected_config['db_user'], 64, null);
	form_generate_input_text("DB password", "Database password", "db_pass",
		"y", $selected_config['db_pass'], 64, null);
	form_generate_input_text("DB name", "Database name", "db_name",
	 "n", $selected_config["db_name"], 64, null);
	
	
	
if (!$_SESSION['read_only']) {
?>
  <tr>
   <td colspan="3">
    <table cellspacing=20>
      <tr>
        <td class="dataRecord" align="right" width="50%"><input type="submit" name="save" value="Save" class="formButton"></td>
		<td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
      </tr>
    </table>
  </tr>
<?php
 }
?>
  </table>

<script> form_init_status(); </script>
</form>

