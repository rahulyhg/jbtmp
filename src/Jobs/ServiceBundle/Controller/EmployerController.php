<?php

namespace Jobs\ServiceBundle\Controller;

use Jobs\ServiceBundle\Controller\MainController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Jobs\ServiceBundle\Entity\Jobs;
use Jobs\ServiceBundle\Entity\JobsRepository;
use Jobs\ServiceBundle\Models\ErrorCode;

class EmployerController extends MainController
{
    
    public function postAction(Request $request)
    {
        $result = 0;
        $msg = '';
        $code = 200;
        try {
            $this->init($request);
            $jobId = guid();
            $created = tstobts(time());
            $jobs = new Jobs();
            $jobs->setJobId($jobId);
            $jobs->setUserId($this->userId);
            $jobs->setTitle($request->request->get('title'));
            $position = $request->request->get('position');
            $position_type = $position[0]['id'];
            $jobs->setPositionType($position_type);
            $jobs->setApplicationMethod($request->request->get('apply'));
            $jobs->setApplicationEmail($request->request->get('email'));
            $jobs->setApplicationEmailCc($request->request->get('CCemail'));
            $jobs->setApplicationUrl($request->request->get('url'));
            $jobs->setCity($request->request->get('city'));
            $jobs->setState($request->request->get('state'));
            $jobs->setCountry($request->request->get('country'));
            $jobs->setAreaCode($request->request->get('areaCode'));
            $jobs->setZipcode($request->request->get('postalCode'));
            $jobs->setSkills($request->request->get('skills'));
            $jobs->setDescription($request->request->get('description'));
            $jobs->setShowName($this->setCheckbox($request->request->get('contact_name')));
            $jobs->setShowAddress1($this->setCheckbox($request->request->get('contact_address1')));
            $jobs->setShowAddress2($this->setCheckbox($request->request->get('contact_address2')));
            $jobs->setShowCity($this->setCheckbox($request->request->get('contact_city')));
            $jobs->setShowState($this->setCheckbox($request->request->get('contact_state')));
            $jobs->setShowEmail($this->setCheckbox($request->request->get('contact_email')));
            $jobs->setShowPhone($this->setCheckbox($request->request->get('contact_phone')));
            $jobs->setShowZipcode($this->setCheckbox($request->request->get('contact_zip')));
            $jobs->setJobCreatedDt($created);
            $jobs->setJobDeleted(0);
            $jobs->setJobDeletedDt(0);
            $jobs->setJobModifiedDt($created);
            $jobs->setJobStatus(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($jobs);
            $em->flush();
            $msg = 'Success';
            $result = 1;
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $result = 0;
            $code = $e->getCode();
        }
        $arr = array('success' => $result, 'message' => $msg, 'code' => $code, 'post' => $_POST);
        $json = json_encode($arr);
        return new Response($json);
    }
}