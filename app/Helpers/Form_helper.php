<?php 
	
	$validation = \Config\Services::validation(); 
	use \App\Models\CommonForAllModel; //Custom

	function display_error($validation, $field){
		if (isset($validation)) {
			if ($validation->hasError($field)) { return $validation->getError($field); }
			else { return false; }
		}
	}

	function get_pic_by_id($fld, $tbl, $login_usr_id) { 
		if (isset($login_usr_id)) {
			$commonForAllModel = new CommonForAllModel(); //Customized 
			$profile_pic = $commonForAllModel->get_pic_by_id($fld, $tbl, $login_usr_id);
			if ($profile_pic) { return $profile_pic; }
			else { return false; }
		}
	} //function - Closed


	if (!function_exists('getTopBar')) {
	    function getTopBar() {
	        $session = session();

	        $roles = [
	            'admin_session_uid'      => 'Admin/top_bar',
	            'accountant_session_uid' => 'Patients/top_bar',
	            'doctor_session_uid'     => 'Doctor/top_bar',
	            'frontdesk_session_uid'  => 'Frontdesk/top_bar',
	            'bldbnk_session_uid'     => 'Blood_Bank/top_bar',
	        ];

	        foreach ($roles as $sessionKey => $viewPath) {
	            $value = $session->get($sessionKey);
	            if (!empty($value)) { return view($viewPath); }
	        }

	        // Default if no session key is found
	        return view('Home/nav_bar');
	    }
	} //function - Closed

?>