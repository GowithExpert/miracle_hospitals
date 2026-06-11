<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class MedicalAccountantAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->has('accountant_session_uid')) {
            return redirect()
                ->to(site_url('Accountant_login/accountant_login'))
                ->with('error', 'Please login to continue.');
        }

        // Optional: Extra safety checks
        if (
            !$session->get('accountant_session_uid') ||
            !$session->get('accountant_session_id')
        ) {
            $session->setTempdata('error', 'Medical Accountant ID is missing!', 3);
            return redirect()->to(site_url('Accountant_login/accountant_login'));
        }

        // Otherwise allow request to proceed
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing needed here
    }
}
