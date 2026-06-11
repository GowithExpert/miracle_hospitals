<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class DoctorAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->has('doctor_session_uid')) {
            return redirect()
                ->to(site_url('Doctor_login/doctor_login'))
                ->with('error', 'Please login to continue.');
        }

        // Optional: Extra safety checks
        if (
            !$session->get('doctor_session_uid') ||
            !$session->get('doctor_session_id')
        ) {
            $session->setTempdata('error', 'Doctor ID is missing!', 3);
            return redirect()->to(site_url('Doctor_login/doctor_login'));
        }
        // Otherwise allow request to proceed
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing needed here
    }
}
