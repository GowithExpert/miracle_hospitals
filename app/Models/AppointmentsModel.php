<?php

namespace App\Models;

/** 
 * Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
 * @Description: The code of the released Hospital software, does NOT lie under
 * GLP (General Public License) But it has proprietary copyrights. The purpose of the
 * Informing for public that, the Hospital web based mobile responsible application its associated
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

use CodeIgniter\Model;

class AppointmentsModel extends Model
{
    protected $table = 'booked_doctor_appointment
    ';
    protected $primaryKey = 'id';

    // Add your model methods here
    public function getAppointmentsByDateRange($fromDate, $toDate)
{
    return $this->where('appointment_date >=', $fromDate)
                ->where('appointment_date <=', $toDate)
                ->findAll();
}
public function getAppointmentsByDoctor($doctorName)
{
    return $this->where('doctor_name', $doctorName)
                ->findAll();
}
public function getAppointmentsByDepartment($department)
{
    return $this->where('department', $department)
                ->findAll();
                $filteredAppointments = $appointmentsModel->getAppointmentsByDepartment($filter);
}


}
