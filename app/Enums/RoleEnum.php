<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case OWNER = 'owner';
    case MODERATOR = 'moderator';
    case RSVP_COORDINATOR = 'rsvp_coordinator';
    case GUEST = 'guest';
}
