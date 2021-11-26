
<div class="mo_wpns_tab">
	<button class="tablinks" onclick="openTabbackup(event, 'setting_backup')" id="backup_set">Manual Backup</button>
    <button class="tablinks" onclick="openTabbackup(event, 'schdule_view')" id="schdule">Scheduled Backup</button>
    <button class="tablinks" onclick="openTabbackup(event, 'report_view')" id="report">Report</button>
  
</div>

<div class="tabcontent" id="setting_backup">
	<div class="mo_wpns_divided_layout">
		<table style="width: 100%;">
			<tr>
				<td style="width:100%;vertical-align:top;" id="configurationForm2">
					<?php include_once $mo2f_dirName . 'controllers'.DIRECTORY_SEPARATOR.'backup'.DIRECTORY_SEPARATOR.'backup_controller.php'; ?>
			</tr>
		</table>
	</div>
</div>
<div class="tabcontent" id="schdule_view">
	<div class="mo_wpns_divided_layout">
		<table style="width: 100%;">
			<tr>
				<td style="width:100%;vertical-align:top;" id="configurationForm3">
					<?php include_once $mo2f_dirName . 'controllers'.DIRECTORY_SEPARATOR.'backup'.DIRECTORY_SEPARATOR.'backup_schdule.php'; ?>
			</tr>
		</table>
	</div>
</div>
<div class="tabcontent" id="report_view">
	<div class="mo_wpns_divided_layout">
		<table style="width: 100%;">
			<tr>
				<td style="width:100%;vertical-align:top;" id="configurationForm4">
					<?php include_once $mo2f_dirName . 'controllers'.DIRECTORY_SEPARATOR.'backup'.DIRECTORY_SEPARATOR.'backup_created_report.php'; ?>
			</tr>
		</table>
	</div>
</div>



<script>
	document.getElementById("setting_backup").style.display = "block";
	document.getElementById("schdule_view").style.display = "none";
	document.getElementById("report_view").style.display = "none";
	

	document.getElementById("backup_set").className += " active";
	function openTabbackup(evt, tabname){
		var i, tablinks, tabcontent;
		tabcontent = document.getElementsByClassName("tabcontent");
  			for (i = 0; i < tabcontent.length; i++) {
    		tabcontent[i].style.display = "none";
  		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById(tabname).style.display = "block";
		localStorage.setItem("last_tab", tabname);
  		evt.currentTarget.className += " active";
	}
	var tab = localStorage.getItem("last_tab");	
	
	if(tab == "setting_backup"){
		document.getElementById("backup_set").click();
	}
	else if(tab == "schdule_view"){
        document.getElementById("schdule").click();
	}
	else if(tab == "report_view"){
        document.getElementById("report").click();
	}
	
	else{
		document.getElementById("backup_set").click();
	}
</script>
