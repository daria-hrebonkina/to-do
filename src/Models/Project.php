<?php

namespace Models;


use core\Model;

class Project extends Model
{
    protected static $fillable = ['title', 'project_id', 'priority', 'date', 'completed'];
}