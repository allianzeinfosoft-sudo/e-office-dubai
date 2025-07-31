<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DBBackupController extends Controller
{
    public function index()
    {
        $data['meta_title'] = 'Database Backup';
        $data['backups'] = collect(Storage::files('backups'))->sortDesc();
        return view('system.db_backup.index', $data);
    }

    public function generate(){
        $fileName = 'backup-' . now()->format('d-m-Y-H-i-s') . '.sql';
        $filePath = storage_path("app/backups/{$fileName}");

        $dbHost = env('DB_HOST');
        $dbPort = env('DB_PORT');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $mysqldumpPath = '/opt/lampp/bin/mysqldump';
        $process = new Process([
            $mysqldumpPath,
            '--user=' . $dbUser,
            '--password=' . $dbPass,
            '--host=' . $dbHost,
            '--port=' . $dbPort,
            $dbName
        ]);

        $process->run();

        // Check for success
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Save dump output to file
        file_put_contents($filePath, $process->getOutput());

        return response()->json([
            'success' => true,
            'message' => 'Backup created successfully.'
        ]);
    }

    public function download($file)
    {
        $path = storage_path("app/backups/{$file}");

        if (File::exists($path)) {
            return response()->download($path);
        }

        return back()->with('error', 'File not found.');
    }

    public function delete($file)
    {
        Storage::delete("backups/{$file}");
        return back()->with('success', 'Backup deleted.');
    }
}
