<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

class GoogleDriveService
{
    /**
     * @return array{id: string, webViewLink: string|null}
     */
    public function uploadVideo(
        string $fullFilePath,
        string $filename,
        ?string $mimeType = null
    ): array {
        $client = new Client();
        $client->setAuthConfig($this->resolveCredentialsPath());
        $client->setScopes([Drive::DRIVE_FILE]);
        $client->setAccessType('offline');

        $drive = new Drive($client);
        $folderId = env('GOOGLE_DRIVE_FOLDER_ID');

        $metadata = new DriveFile([
            'name' => $filename,
            'parents' => $folderId ? [$folderId] : [],
        ]);

        $uploadedFile = $drive->files->create($metadata, [
            'data' => file_get_contents($fullFilePath),
            'mimeType' => $mimeType ?: 'video/webm',
            'uploadType' => 'multipart',
            'fields' => 'id,webViewLink',
        ]);

        if (filter_var(env('GOOGLE_DRIVE_MAKE_PUBLIC', false), FILTER_VALIDATE_BOOL)) {
            $drive->permissions->create($uploadedFile->id, new Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]));
        }

        return [
            'id' => $uploadedFile->id,
            'webViewLink' => $uploadedFile->webViewLink,
        ];
    }

    public function deleteFile(string $fileId): void
    {
        $client = new Client();
        $client->setAuthConfig($this->resolveCredentialsPath());
        $client->setScopes([Drive::DRIVE_FILE]);
        $client->setAccessType('offline');

        $drive = new Drive($client);
        $drive->files->delete($fileId);
    }

    private function resolveCredentialsPath(): string
    {
        $configuredPath = env('GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON');

        if (! $configuredPath) {
            return base_path('storage/app/google-drive-service-account.json');
        }

        if (str_starts_with($configuredPath, '/')) {
            return $configuredPath;
        }

        return base_path($configuredPath);
    }
}
