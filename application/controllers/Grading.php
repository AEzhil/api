<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Grading extends MY_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // validate user requests
        //$this->validate_token();
        
        // Controller intializations
        $this->lang->load('grading');
        $this->load->model('sc_grading', 'grading');
    }
    
    public function listGrades_get() {
        $listGrades = $this->grading->getGrades();
        $this->set_response($listGrades, REST_Controller::HTTP_OK);
    }

    public function listSubjects_get() {
        $listSubjects = $this->grading->getSubjects();
        $this->set_response($listSubjects, REST_Controller::HTTP_OK);
    }

    public function listClasses_get () {
        $listClasses = $this->grading->getClasses();
        $this->set_response($listClasses, REST_Controller::HTTP_OK);
    }
    
    public function getUserGrades_get() {
        $subjectId = $this->get('subjectId');
        if(is_null($subjectId) || !is_numeric($subjectId)) {
            $this->set_response($this->lang->line('subject_error'), REST_Controller::HTTP_EXPECTATION_FAILED);
        } else {
            $grades = $this->grading->getGrades();
            $subject = $this->grading->getSubjectById($subjectId);
            $userGrades = $this->grading->getUserGrades($this->user->id, $subjectId);
            $response = array(
                'grades' => $grades,
                'subject' => $subject,
                'userGrades' => $userGrades
            );
            $this->set_response($response, REST_Controller::HTTP_OK);
        }
    }
    
    public function saveUserGrades_post() {
        $subjectDetails = $this->post();
        if(is_null($subjectDetails['subjectId']) || !is_numeric($subjectDetails['subjectId'])) {
            $this->set_response($this->lang->line('subject_error'), REST_Controller::HTTP_EXPECTATION_FAILED);
        } else {
            $result = $this->grading->saveUserGrades($this->user->id, $subjectDetails);
            $this->set_response($this->lang->line('grades_saved'), REST_Controller::HTTP_OK);
        }
    }
    

    public function manageUsergrades_post() {
        // To add User Grades
        $post_data = $this->post();
        $this->grading->manage_usergrades($post_data);
    }

}