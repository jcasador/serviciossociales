<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

class Mo2fDB {
	private $userDetailsTable;
	private $userLoginInfoTable;

	function __construct() {
		global $wpdb;
		$this->userDetailsTable = $wpdb->prefix . 'mo2f_user_details';
		$this->userLoginInfoTable = $wpdb->prefix . 'mo2f_user_login_info';
	}

	function mo_plugin_activate() {
		global $wpdb;
		if ( ! get_option( 'mo2f_dbversion' ) ) {
			update_option( 'mo2f_dbversion', MoWpnsConstants::DB_VERSION );
			$this->generate_tables();
		} else {
			$current_db_version = get_option( 'mo2f_dbversion' );
			if ( $current_db_version < MoWpnsConstants::DB_VERSION ) {

				update_option( 'mo2f_dbversion', MoWpnsConstants::DB_VERSION );
				$this->generate_tables();
			}
			//update the tables based on DB_VERSION.
		}
	}

	function generate_tables() {
		global $wpdb;

		$tableName = $this->userDetailsTable;

		if($wpdb->get_var("show tables like '$tableName'") != $tableName) {

			$sql = "CREATE TABLE IF NOT EXISTS " . $tableName . " (
				`user_id` bigint NOT NULL, 
				`mo2f_OTPOverSMS_config_status` tinyint, 
				`mo2f_miniOrangePushNotification_config_status` tinyint, 
				`mo2f_miniOrangeQRCodeAuthentication_config_status` tinyint, 
				`mo2f_miniOrangeSoftToken_config_status` tinyint, 
				`mo2f_AuthyAuthenticator_config_status` tinyint, 
				`mo2f_EmailVerification_config_status` tinyint, 
				`mo2f_SecurityQuestions_config_status` tinyint, 
				`mo2f_GoogleAuthenticator_config_status` tinyint, 
				`mo2f_OTPOverEmail_config_status` tinyint, 
				`mo2f_OTPOverTelegram_config_status` tinyint, 
				`mo2f_OTPOverWhatsapp_config_status` tinyint, 
				`mobile_registration_status` tinyint, 
				`mo2f_2factor_enable_2fa_byusers` tinyint DEFAULT 1,
				`mo2f_configured_2FA_method` mediumtext NOT NULL , 
				`mo2f_user_phone` mediumtext NOT NULL , 
				`mo2f_user_email` mediumtext NOT NULL,  
				`user_registration_with_miniorange` mediumtext NOT NULL, 
				`mo_2factor_user_registration_status` mediumtext NOT NULL,
				UNIQUE KEY user_id (user_id) );";

			dbDelta( $sql );
		}
		add_site_option( 'cmVtYWluaW5nT1RQ' ,30);
		add_site_option( 'bGltaXRSZWFjaGVk' ,0);
		add_site_option( base64_encode('totalUsersCloud'),0);
		add_site_option(base64_encode('remainingWhatsapptransactions'),30);




		$check_if_column_exists = $this->check_if_column_exists( 'mo2f_user_details', "mo2f_OTPOverEmail_config_status" );
		$check_if_column_exists_tel = $this->check_if_column_exists( 'mo2f_user_details', "mo2f_OTPOverTelegram_config_status" );

		if (  ! $check_if_column_exists  ) {
			$query = "ALTER TABLE `$tableName` ADD COLUMN `mo2f_OTPOverEmail_config_status` tinyint";
			$this->execute_add_column( $query );
			
		}
		if(!$check_if_column_exists_tel)
		{
			$query = "ALTER TABLE " . $tableName . " ADD COLUMN (
			`mo2f_OTPOverTelegram_config_status` tinyint, 
			`mo2f_OTPOverWhatsapp_config_status` tinyint);";
			$this->execute_add_column( $query );	
		}


		$tableName = $this->userLoginInfoTable;

		if($wpdb->get_var("show tables like '$tableName'") != $tableName) {

			  $sql = "CREATE TABLE IF NOT EXISTS "  . $tableName . " (
			`session_id` mediumtext NOT NULL, 
			 `mo2f_login_message` mediumtext NOT NULL , 
			 `mo2f_current_user_id` tinyint NOT NULL , 
			 `mo2f_1stfactor_status` mediumtext NOT NULL , 
			 `mo_2factor_login_status` mediumtext NOT NULL , 
			 `mo2f_transactionId` mediumtext NOT NULL , 
			 `mo_2_factor_kba_questions` longtext NOT NULL , 
			 `mo2f_rba_status` longtext NOT NULL , 
			 `ts_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`session_id`(100)));";
			
			dbDelta( $sql );
		}
		
		$check_if_column_exists = $this->check_if_column_exists( "user_login_info_table", "mo_2factor_login_status" );

		if (  ! $check_if_column_exists  ) {
			$query = "ALTER TABLE `$tableName` ADD COLUMN `mo_2factor_login_status` mediumtext NOT NULL";
			$this->execute_add_column( $query );
			
		}

	}
	function get_current_user_email($id)
	{
		global $wpdb;
		$sql = 'select user_email from wp_users	where ID='.$id.';';
		return $wpdb->get_var($sql);
	}
	function database_table_issue(){

        global $wpdb;
        $tableName = $this->userLoginInfoTable;
		
        if($wpdb->get_var("show tables like '$tableName'") != $tableName) {

            $sql = "CREATE TABLE IF NOT EXISTS "  . $tableName . " (
			`session_id` mediumtext NOT NULL, 
			 `mo2f_login_message` mediumtext NOT NULL , 
			 `mo2f_current_user_id` tinyint NOT NULL , 
			 `mo2f_1stfactor_status` mediumtext NOT NULL , 
			 `mo_2factor_login_status` mediumtext NOT NULL , 
			 `mo2f_transactionId` mediumtext NOT NULL , 
			 `mo_2_factor_kba_questions` longtext NOT NULL , 
			 `mo2f_rba_status` longtext NOT NULL , 
			 `ts_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`session_id`(100)));";
	            dbDelta( $sql );
        }
		
		$check_if_column_exists = $this->check_if_column_exists( "user_login_info_table", "mo_2factor_login_status" );

        if (  ! $check_if_column_exists  ) {
            $query = "ALTER TABLE `$tableName` ADD COLUMN `mo_2factor_login_status` mediumtext NOT NULL";
            $this->execute_add_column( $query );

        }

    }


	function insert_user( $user_id ) {
		global $wpdb;
		$sql = "INSERT INTO $this->userDetailsTable (user_id) VALUES($user_id) ON DUPLICATE KEY UPDATE user_id=$user_id";
		$wpdb->query( $sql );
	}

	function drop_table( $table_name ) {
		global $wpdb;
		$sql = "DROP TABLE $table_name";
		$wpdb->query( $sql );
	}


	function get_user_detail( $column_name, $user_id ) {
		global $wpdb;
		$user_column_detail = $wpdb->get_results( "SELECT " . $column_name . " FROM " . $this->userDetailsTable . " WHERE user_id = " . $user_id . ";" );
		$value              = empty( $user_column_detail ) ? '' : get_object_vars( $user_column_detail[0] );

		return $value == '' ? '' : $value[ $column_name ];
	}

	function delete_user_details( $user_id ) {
		global $wpdb;
		$wpdb->query(
			"DELETE FROM " . $this->userDetailsTable . "
				 WHERE user_id = " . $user_id
		);

		return;
	}
	function get_no_of_2fa_users() {
		global $wpdb;
		$count=$wpdb->query(
		"SELECT * FROM `". $this->userDetailsTable."`WHERE `mo2f_2factor_enable_2fa_byusers`=1 "
		);
		return $count;
	}

	function get_all_user_2fa_methods() {
		global $wpdb;
		$all_methods = [];
		$methods=$wpdb->get_results(
		"SELECT `mo2f_configured_2FA_method` FROM ". $this->userDetailsTable." WHERE `mo2f_2factor_enable_2fa_byusers`=1",ARRAY_A
		);
		foreach($methods as $method){
			array_push($all_methods,$method['mo2f_configured_2FA_method']);
		}
		return implode(',',$all_methods);
	}

	function check_if_table_exists( ) {
		global $wpdb;
		$does_table_exist= $wpdb->query(
			"SHOW TABLES LIKE  '" . $this->userDetailsTable . "';"
		);

		return $does_table_exist;
	}

	function check_if_user_column_exists($user_id){
		global $wpdb;
		$value = $wpdb->query(
			"SELECT * FROM " . $this->userDetailsTable . "
				 WHERE user_id = " . $user_id
		);

		return $value;

	}

	function check_if_column_exists( $table_type, $column_name ){
			
			if($table_type == "user_login_info_table")
				$table =  $this->userLoginInfoTable;
			else if($table_type == "mo2f_user_details")
				$table = $this->userDetailsTable;
			global $wpdb;
			$sql="SHOW COLUMNS FROM " . $table . "
					 LIKE '" . $column_name . "'";
			$value = $wpdb->query($sql);
					 
			return $value;

		}

	function update_user_details( $user_id, $update ) {
		global $wpdb;
		$count = count( $update );
		$sql   = "UPDATE " . $this->userDetailsTable . " SET ";
		$i     = 1;
		foreach ( $update as $key => $value ) {

			$sql .= $key . "='" . $value . "'";
			if ( $i < $count ) {
				$sql .= ' , ';
			}
			$i ++;
		}
		$sql .= " WHERE user_id=" . $user_id . ";";
		$wpdb->query( $sql );

		return;

	}
	
	function insert_user_login_session( $session_id ) {
		global $wpdb;
		$sql = "INSERT INTO $this->userLoginInfoTable (session_id) VALUES('$session_id') ON DUPLICATE KEY UPDATE session_id='$session_id'";

		$wpdb->query( $sql );
        $sql = "DELETE FROM $this->userLoginInfoTable WHERE ts_created < DATE_ADD(NOW(),INTERVAL - 2 MINUTE);";
        $wpdb->query( $sql );
	}

	function save_user_login_details( $session_id, $user_values ) {
		global $wpdb;
		$count = count( $user_values );
		$sql   = "UPDATE " . $this->userLoginInfoTable . " SET ";
		$i     = 1;
		foreach ( $user_values as $key => $value ) {

			$sql .= $key . "='" . $value . "'";
			if ( $i < $count ) {
				$sql .= ' , ';
			}
			$i ++;
		}
		$sql .= " WHERE session_id='" . $session_id . "';";
		$wpdb->query( $sql );

		return;

	}
	
	function execute_add_column ( $query ){
		global $wpdb;
		$wpdb->query( $query );

		return;
	}

	function get_user_login_details( $column_name, $session_id ) {
		global $wpdb;
		$user_column_detail = $wpdb->get_results( "SELECT " . $column_name . " FROM " . $this->userLoginInfoTable . " WHERE session_id = '" . $session_id . "';" );
		$value              = empty( $user_column_detail ) ? '' : get_object_vars( $user_column_detail[0] );

		return $value == '' ? '' : $value[ $column_name ];
	}
	
	function delete_user_login_sessions($session_id ) {
		global $wpdb;
		$wpdb->query(
			"DELETE FROM " . $this->userLoginInfoTable . "
				 WHERE session_id='$session_id';"
		);

		return;
	}
	function check_user_limit_exceeded($user_id){

		global $wpdb;
		$value = $wpdb->query(
            "SELECT meta_key FROM ".$wpdb->base_prefix ."usermeta 
                 WHERE meta_key = 'currentMethod'"
        );

		$user_already_configured = $wpdb->query(
			"SELECT meta_key FROM ".$wpdb->base_prefix ."usermeta 
                 WHERE meta_key = 'currentMethod' and user_id =".$user_id);

		if($value < 3 || $user_already_configured){
        	return false;
        }
        else{
        	return true;
        }
	}
	function check_alluser_limit_exceeded($user_id){

		global $wpdb;
		$value = $wpdb->query(
            "SELECT * FROM ".$this->userDetailsTable
        );
		$user_already_configured = $wpdb->query(
			"SELECT * FROM ".$this->userDetailsTable ." WHERE user_id =".$user_id		);

		if($value < 3 || $user_already_configured){
        	return false;
        }
        else{
        	return true;
        }
	}

	function get_all_onprem_userids(){

		global $wpdb;
		$value = $wpdb->get_results(
            "SELECT * FROM ".$wpdb->base_prefix ."usermeta 
                 WHERE meta_key = 'currentMethod'"
        );


		foreach ($value as $row){

			if(isset($row->user_id)){

				$this->insert_user( $row->user_id);

				$this->update_user_details( $row->user_id,
					array(
						'mo2f_GoogleAuthenticator_config_status' => get_user_meta($row->user_id,'Google Authenticator',true),
						'mo2f_SecurityQuestions_config_status'   => get_user_meta($row->user_id,'Security Questions',true),
						'mo2f_EmailVerification_config_status'   => get_user_meta($row->user_id,'Email Verification',true),
						'mo2f_AuthyAuthenticator_config_status'  =>0,
						'mo2f_user_email'                        => get_user_meta($row->user_id,'email',true),
						'mo2f_user_phone'                        => '',
						'user_registration_with_miniorange'      => '',
						'mobile_registration_status'             => '',
						'mo2f_configured_2FA_method'             => get_user_meta($row->user_id,'currentMethod',true),
						'mo_2factor_user_registration_status'    => 'MO_2_FACTOR_PLUGIN_SETTINGS'
					) );
			}
		}
	}

}
