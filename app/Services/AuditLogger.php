<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    /**
     * Log an administrative or system action.
     *
     * @param string $action       e.g., 'create', 'update', 'delete', 'approve', 'reject'
     * @param string $subjectType  e.g., 'user', 'household_member', 'admin', 'document', 'property'
     * @param mixed  $subjectId    The ID of the target record
     * @param array  $details      Optional payload of metadata
     * @return void
     */
    public static function log($action, $subjectType, $subjectId, array $details = [])
    {
        $admin = Auth::guard('admin')->user();
        $operatorName = $admin ? $admin->name : 'System';
        $operatorEmail = $admin ? $admin->email : 'system@navkarservices.com';

        $timestamp = date('Y-m-d H:i:s T');
        
        $logMessage = sprintf(
            "[%s] OPERATOR: %s (%s) | ACTION: %s | SUBJECT: %s (ID: %s) | DETAILS: %s\n",
            $timestamp,
            $operatorName,
            $operatorEmail,
            strtoupper($action),
            strtoupper($subjectType),
            $subjectId,
            json_encode($details)
        );

        try {
            $logPath = storage_path('logs/audit.log');
            
            // Create directory if not exists
            $dir = dirname($logPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            file_put_contents($logPath, $logMessage, FILE_APPEND);
        } catch (\Exception $e) {
            // Silence exceptions to prevent stopping request execution if logging fails
        }
    }
}
