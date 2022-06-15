<?php

namespace App\Models;

enum ModelType: string
{
    case permission = 'permission';
    case role = 'role';
    case user = 'user';
    case invitation = 'invitation';
    case division = 'division';
    case member = 'member';
    case project = 'project';
}
