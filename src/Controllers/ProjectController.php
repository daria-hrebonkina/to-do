<?php

namespace Controllers;


use core\Components\Auth;
use core\Controller;
use core\View;
use Models\Project;

class ProjectController extends Controller
{
    public function indexAction()
    {
        $projects = Project::findAll(['user_id' => Auth::getUser()->id]);
        return View::renderTemplate('Index/index.html');
    }

    public function createProjectAction()
    {
        $request = $this->request;
        $project = Project::create($request);

    }
}