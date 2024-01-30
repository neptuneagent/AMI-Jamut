<?php

// app/helpers.php

if (!function_exists('beautifyDateTime')) {
    /**
     * Beautify the date and time.
     *
     * @param string $dateTime
     * @return string
     */
    function beautifyDateTime($dateTime)
    {
        return \Carbon\Carbon::parse($dateTime)->format('F j, Y h:i A');
    }
}

if (!function_exists('getStatusBadgeClass')) {
    /**
     * Get the appropriate badge class based on the status.
     *
     * @param string $status
     * @return string
     */
    function getStatusBadgeClass($status)
    {
        $badgeClasses = [
            'waiting' => 'bg-warning',
            'completed' => 'bg-secondary',
            'audited' => 'bg-info',
            'done' => 'bg-success',
        ];

        return $badgeClasses[$status] ?? 'bg-secondary';
    }
}
