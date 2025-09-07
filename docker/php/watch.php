<?php

declare(strict_types=1);

/**
 * Trigger Wayfinder when changes are made to controller or route files.
 */

// Initial scan
message("Initializing...");
$timestamps = scan();
$interval = 1; // Polling interval in seconds
$enabled = false;

// Watch loop
message("Watching for controller or route updates...");
while (true) {
    if (!$enabled) {
        return;
    }

    $changes = false;

    // Clear PHP's file stat cache to ensure fresh results
    clearstatcache(true);

    // Get current state of directory
    $files = scan();

    // Check for new or modified files
    foreach ($files as $path => $mtime) {
        if (!isset($timestamps[$path])) {
            message("New file: {$path}");
            $changes = true;
        } elseif ($timestamps[$path] !== $mtime) {
            message("File changed: {$path}");
            $changes = true;
        }
    }

    // Check for deleted files
    foreach ($timestamps as $path => $mtime) {
        if (!isset($files[$path])) {
            message("File deleted: {$path}");
            $changes = true;
        }
    }

    // Update our list of file timestamps
    $timestamps = $files;

    // Update Wayfinder if changes have been detected
    if ($changes) {
        runWayfinder();
    }

    // Memory cleanup
    unset($files);
    gc_collect_cycles();

    // Wait before next check
    sleep($interval);
}

// Log messages to STDOUT with a timestamp
function message(string $message)
{
    fwrite(STDOUT, "  " . date('Y-m-d H:i:s') . " MONITOR: {$message}\n");
    fflush(STDOUT);
}

// Run the Wayfinder artisan command with exec()
function runWayfinder()
{
    exec('php artisan wayfinder:generate --with-form --skip-actions', $output, $returnCode);
    message('TypeScript route definitions updated');

    if ($returnCode !== 0) {
        message("Task failed with code {$returnCode}");
        message(json_encode($output));
    }
}

// Read file modification timestamps from the file system
function scan(array $directory = ['app/Http', 'routes'])
{
    $files = [];

    foreach ($directory as $dir) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir)
        );

        foreach ($iterator as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }
            $files[$file->getPathname()] = $file->getMTime();
        }

        // Free the iterator;
        unset($iterator);
    }

    return $files;
}
