<?php

namespace Models;


use core\Model;

class Project extends Model
{
    protected static $fillable = ['id', 'title', 'user_id', 'color'];

    public function getTasksAmount()
    {
        var_dump(1); die;
        return count(Task::findAll(['project_id' => $this->id]));
    }
}