<?php

namespace App\Enums;

enum PermissionEnum: string
{
    // Dashboard
    case VIEW_DASHBOARD = 'view_dashboard';

    // Fotos
    case MANAGE_PHOTOS = 'manage_photos'; // Aprobar/Rechazar
    case UPLOAD_PHOTOS = 'upload_photos'; // (Para invitados)

    // Invitados / RSVP
    case VIEW_GUESTS = 'view_guests';       // Ver la lista (Solo lectura)
    case MANAGE_GUESTS = 'manage_guests';   // Crear/Borrar invitados
    case MANAGE_RSVP = 'manage_rsvp';       // Cambiar estado de asistencia (Confirmar por teléfono)

    // Configuración
    case CONFIGURE_EVENT = 'configure_event'; // Cambiar fecha, colores, etc.
}
