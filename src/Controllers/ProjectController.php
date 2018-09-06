<?php

namespace Controllers;


use core\Components\Auth;
use core\Controller;
use core\View;
use Models\Project;
use Models\Task;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    public function indexAction()
    {
        $projects = Project::findAll(['user_id' => Auth::getUser()->id]);
        return View::renderTemplate('Index/index.html', compact('projects'));
    }

    public function createAction()
    {
        $request = $this->request->request->all();
        $project = Project::create($request);
        if($project) {
            return new Response($project, 200);
        }
    }

    public function updateAction()
    {
        $project = Project::findOne((int) $this->request->get('id'));
        if($this->request->isMethod('post')) {
            $request = $this->request->request->all();
            $project->update($request);

            return $this->redirect('/tasks?project_id=' . $project->id);
        }

        return View::renderTemplate('Project/update.html', ['project' => $project->toArray()]);
    }

    public function deleteAction()
    {
        $project = Project::findOne((int)$this->request->get('id'));
        $tasks = Task::findAll([['project_id', '=', $project->id], ['completed', '=', '0']]);
        if(count($tasks) == 0) {
            $project->delete();
        }

        return $this->redirect('/tasks');
    }
}