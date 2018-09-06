<?php

namespace Controllers;


use core\Components\Auth;
use core\Controller;
use core\View;
use Models\Project;
use Models\Task;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function indexAction()
    {
        $filter = $this->getFilterConditions();
        $conditions = [];
        $conditions = array_merge($conditions, $filter['uncompleted']);
        $requestDate = $this->request->get('date');
        $dateConditions = $filter['today'];
        if($requestDate && array_key_exists($requestDate, $filter)) {
            $dateConditions = $filter[$requestDate];
        }
        $projectId = $this->request->get('project_id');
        $projects = Project::findAll(['user_id' => Auth::getUser()->id]);
        if($projectId) {
            $conditions = array_merge($conditions, ['project_id' => $projectId]);
        } else {
            $conditions = array_merge($dateConditions, $conditions);
        }
        $tasks = Task::findAll($conditions, ['by' => 'priority', 'directions' => 'desc']);
        return View::renderTemplate('Index/index.html', compact('tasks', 'projects', 'projectId'));
    }

    public function createAction()
    {
        $request = $this->request->request->all();
        $task = Task::create($request);
        if($task) {
            return new Response($task, 200);
        }
    }

    public function updateAction()
    {
        $task = Task::findOne((int) $this->request->get('id'));
        $projects = Project::findAll(['user_id' => Auth::getUser()->id]);
        if($this->request->isMethod('post')) {
            $request = $this->request->request->all();
            $task->update($request);
            return $this->redirect('/tasks?project_id=' . $this->request->get('project_id'));
        }

        return View::renderTemplate('Task/update.html', ['task' => $task->toArray(), 'projects' => $projects]);
    }

    public function deleteAction()
    {
        $task = Task::findOne((int)$this->request->get('id'));
        $task->delete();
        return $this->redirect('/tasks');
    }

    private function getFilterConditions()
    {
        return [
            'today' => [
                ['date' , '>=', (new \DateTime())->format('Y-m-d 00:00:00')],
                ['date' , '<', (new \DateTime())->add(\DateInterval::createFromDateString('+1 day'))->format('Y-m-d 00:00:00')]
            ],
            'upcoming' => [
                ['date' , '>', (new \DateTime())->add(\DateInterval::createFromDateString('+1 day'))->format('Y-m-d 00:00:00')],
                ['date' , '<=', (new \DateTime())->add(\DateInterval::createFromDateString('+1 week'))->format('Y-m-d 23:59:59')]
            ],
            'uncompleted' => [
                ['completed',  '=', '0']
            ]
        ];
    }

    public function archiveAction()
    {
        $tasks = Task::findAll(['completed', '=', '0']);
        return View::renderTemplate('Index/archive.html', compact('tasks'));
    }

}