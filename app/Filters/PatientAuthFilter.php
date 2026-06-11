<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class PatientAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->has('patient_session_uid')) {
            return redirect()
                ->to(site_url('Patients_login/login'))
                ->with('error', 'Please login to continue.');
        }

        // Optional: Extra safety checks
        if (
            !$session->get('patient_session_uid') ||
            !$session->get('patient_session_id')
        ) {
            $session->setTempdata('error', 'Patient ID is missing!', 3);
            return redirect()->to(site_url('Patients_login/login'));
        }

        // Otherwise allow request to proceed
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing needed here
    }
}
