<?php namespace App\Controllers;
/** 
 * Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
 * @Description: The code of the released Hospital software, does NOT lie under
 * GLP (General Public License) But it has proprietary copyrights. The purpose of the
 * Informing for public that, the Hospital web based mobile responsible application 
 * its associated
 * different roles are protected by the mentioned copyrights. *
 * @Version: Miracle Hospital - 1.0
 * @Author: Neoark Software
 * @Address: Plot #8, Street #1, Ganga Sahay Colony (Near Govt Senior Secondary
 * School), Mandoli (Industrial Area) North East Delhi - 110093 (India)
 * @Email: sales@neoarksoftware.com | support@neoarksoftware.com
 * @website: www.neoarks.com
 * @Phone: +91-880-090-0164
 * Date: 21st August, 2023 
 */

use \App\Models\ImportDataModel;

use \App\Models\CommonForAllModel; //Custom

class ImportData extends BaseController {
	public $session;
	public $email;
	
	public $pager;
	public $importDataModel;
	public $commonForAllModel;

	
	public function __construct(){
		$throttler = \Config\Services::throttler();
		helper(['form', 'Patient', 'array', 'text']);
		$this->session     = \Config\Services::session(); 
		$this->email       = \Config\Services::email();
		$this->importDataModel   = new ImportDataModel();
		
		$this->pager       = \Config\Services::pager();
		$this->commonForAllModel = new CommonForAllModel();
	} //Constructor - Closed


	/**************** Import Data: START **********************/

   /* @param: Render Upload Ward (csv) form
	* @desc: 
	* @use: Admin
	* @return:
	* @date: September 20th, 2025
	* @modify
	* @author: Neoarks Team
	* @copyrights: Neoark Software Pvt Ltd
	*/
	public function browse_wards() {
        return view('Admin/ManageAssets/upload_wards');
    }

   /* @param: Import Ward csv data into table
	* @desc: 
	* @use: Admin
	* @return:
	* @date: September 20th, 2025
	* @modify
	* @author: Neoarks Team
	* @copyrights: Neoark Software Pvt Ltd
	*/
	public function import_wards() {
	    $this->csv_file = $this->request->getFile('csv_file');

	    if (!$this->csv_file->isValid()) {
	        return redirect()->back()->with('error', 'Invalid file');
	    }

	    if ($this->csv_file->getExtension() !== 'csv') {
	        return redirect()->back()->with('error', 'Only CSV files are allowed');
	    }

	    // Move uploaded CSV to writable/uploads
	    $this->new_name_csvfile = $this->csv_file->getRandomName();
	    $this->csv_file->move(WRITEPATH . 'uploads', $this->new_name_csvfile);
	    $this->csv_file_path = WRITEPATH . 'uploads/' . $this->new_name_csvfile;

	    // Call model method
	    //$wardModel = new \App\Models\WardModel();
	    $this->result = $this->importDataModel->import_wards('hospital_wards', $this->csv_file_path);

	    if ($this->result) {
	        return redirect()->back()->with('success', 'CSV data imported successfully!');
	    } 
	    else {
	        return redirect()->back()->with('error', 'CSV import failed.');
	    }
	} //function - Closed

	/* @param: Render Upload Ward (csv) form
	* @desc: 
	* @use: Admin
	* @return:
	* @date: September 20th, 2025
	* @modify
	* @author: Neoarks Team
	* @copyrights: Neoark Software Pvt Ltd
	*/
	public function browse_beds() {
        return view('Admin/ManageAssets/upload_beds');
    }

   /* @param: Import Ward csv data into table
	* @desc: 
	* @use: Admin
	* @return:
	* @date: September 20th, 2025
	* @modify
	* @author: Neoarks Team
	* @copyrights: Neoark Software Pvt Ltd
	*/
	public function import_beds() {
	    $this->csv_file = $this->request->getFile('csv_file');

	    if (!$this->csv_file->isValid()) {
	        return redirect()->back()->with('error', 'Invalid file');
	    }

	    if ($this->csv_file->getExtension() !== 'csv') {
	        return redirect()->back()->with('error', 'Only CSV files are allowed');
	    }

	    // Move uploaded CSV to writable/uploads
	    $this->new_name_csvfile = $this->csv_file->getRandomName();
	    $this->csv_file->move(WRITEPATH . 'uploads', $this->new_name_csvfile);
	    $this->csv_file_path = WRITEPATH . 'uploads/' . $this->new_name_csvfile;

	    // Call model method
	    //$wardModel = new \App\Models\WardModel();
	    $this->result = $this->importDataModel->import_wards('hospital_beds', $this->csv_file_path);

	    if ($this->result) {
	        return redirect()->back()->with('success', 'CSV data imported successfully!');
	    } 
	    else {
	        return redirect()->back()->with('error', 'CSV import failed.');
	    }
	} //function - Closed

    //**************** Import Data: END**********************//


} //class - Closed