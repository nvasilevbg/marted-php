<?php
function icon(string $name, string $cls = 'icon'): string {
    $p = [
        'pin'=>'<path d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z" stroke="currentColor" stroke-width="2"/><path d="M12 12.2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>',
        'clock'=>'<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'phone'=>'<path d="M7 5 5.7 6.3c-.8.8-.8 2.1-.3 3.1 1.9 4.2 5 7.3 9.2 9.2 1 .5 2.3.5 3.1-.3L19 17l-3.4-3.4-1.8 1.8c-2.1-1-3.3-2.2-4.3-4.3l1.8-1.8L7 5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
        'mail'=>'<rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="2"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
        'shield'=>'<path d="M12 3 20 6v5c0 5-3.2 8.5-8 10-4.8-1.5-8-5-8-10V6l8-3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="m8.5 12 2.2 2.2 4.8-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'calendar'=>'<rect x="4" y="5" width="16" height="15" rx="2" stroke="currentColor" stroke-width="2"/><path d="M8 3v4M16 3v4M4 10h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'arrow'=>'<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'check'=>'<path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>',
        'menu'=>'<path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'drill'=>'<path d="M3 7h12v5H3zM15 8h3l3 2-3 2h-3" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M7 12v7h4v-7M5 19h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'demolition'=>'<path d="M6 19 19 6M5 6h5v5H5zM14 13h5v5h-5z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M4 14h4M16 4v4M10 16l-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'truck'=>'<path d="M3 7h12v10H3zM15 11h4l2 3v3h-6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M7 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM18 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" stroke="currentColor" stroke-width="2"/>',
        'box'=>'<path d="m12 3 8 4-8 4-8-4 8-4ZM4 7v10l8 4 8-4V7" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 11v10" stroke="currentColor" stroke-width="2"/>',
        'measure'=>'<path d="M6 4h12v16H6z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 8h3M9 12h5M9 16h3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'users'=>'<path d="M16 19c0-2.2-1.8-4-4-4H8c-2.2 0-4 1.8-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM20 19c0-1.7-.9-3.2-2.3-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'facebook'=>'<path d="M14 8h2V5h-2c-2.7 0-4 1.6-4 4v2H8v3h2v7h3v-7h2.5l.5-3h-3V9c0-.7.3-1 1-1Z" fill="currentColor"/>',
        'instagram'=>'<rect x="4" y="4" width="16" height="16" rx="5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3.4" stroke="currentColor" stroke-width="2"/><circle cx="16.7" cy="7.4" r="1" fill="currentColor"/>',
    ];
    $inner = $p[$name] ?? '';
    return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="'.$cls.'" aria-hidden="true">'.$inner.'</svg>';
}