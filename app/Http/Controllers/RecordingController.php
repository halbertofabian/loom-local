<?php

namespace App\Http\Controllers;

use App\Models\Recording;
use App\Services\GoogleDriveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class RecordingController extends Controller
{
    public function index()
    {
        return view('recorder', [
            'recordings' => Recording::query()->latest()->limit(10)->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'video' => ['required', 'file', 'mimetypes:video/webm,video/mp4,video/quicktime', 'max:512000'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
        ]);

        $file = $validated['video'];
        $path = $file->store('recordings', 'local');

        $recording = Recording::query()->create([
            'share_token' => $this->generateUniqueShareToken(),
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $file->hashName(),
            'disk' => 'local',
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'duration_seconds' => $validated['duration_seconds'] ?? null,
        ]);

        return response()->json([
            'message' => 'Grabacion guardada correctamente.',
            'recording' => $recording,
            'view_url' => route('recordings.show', ['token' => $recording->share_token]),
            'download_url' => route('recordings.download', $recording),
            'upload_drive_url' => route('recordings.upload-drive', $recording),
            'delete_url' => route('recordings.destroy', $recording),
        ]);
    }

    public function show(string $token)
    {
        $recording = Recording::query()->where('share_token', $token)->firstOrFail();
        $fullPath = Storage::disk($recording->disk)->path($recording->path);

        abort_unless(is_file($fullPath), 404);

        return response()->file($fullPath, [
            'Content-Type' => $recording->mime_type ?: 'video/webm',
            'Content-Disposition' => 'inline; filename="'.$recording->stored_name.'"',
        ]);
    }

    public function download(Recording $recording)
    {
        $fullPath = Storage::disk($recording->disk)->path($recording->path);

        abort_unless(is_file($fullPath), 404);

        return response()->download(
            $fullPath,
            $recording->original_name ?: $recording->stored_name
        );
    }

    public function uploadToDrive(
        Recording $recording,
        GoogleDriveService $googleDriveService
    ): JsonResponse {
        $credentialsPath = env('GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON');
        $resolvedPath = $credentialsPath
            ? (str_starts_with($credentialsPath, '/') ? $credentialsPath : base_path($credentialsPath))
            : base_path('storage/app/google-drive-service-account.json');

        if (! is_file($resolvedPath)) {
            return response()->json([
                'message' => 'No se encontro el JSON de cuenta de servicio de Google Drive.',
            ], 422);
        }

        $fullPath = Storage::disk($recording->disk)->path($recording->path);

        if (! is_file($fullPath)) {
            return response()->json([
                'message' => 'El archivo de video no existe en el servidor.',
            ], 404);
        }

        try {
            $upload = $googleDriveService->uploadVideo(
                $fullPath,
                $recording->original_name ?: $recording->stored_name,
                $recording->mime_type
            );

            $recording->update([
                'google_drive_file_id' => $upload['id'],
                'google_drive_web_view_link' => $upload['webViewLink'],
            ]);

            return response()->json([
                'message' => 'Video subido a Google Drive.',
                'google_drive_file_id' => $upload['id'],
                'google_drive_web_view_link' => $upload['webViewLink'],
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error subiendo a Google Drive: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy(
        Recording $recording,
        GoogleDriveService $googleDriveService
    ): JsonResponse {
        $errors = [];

        if ($recording->google_drive_file_id) {
            try {
                $googleDriveService->deleteFile($recording->google_drive_file_id);
            } catch (Throwable $e) {
                $errors[] = 'No se pudo eliminar de Google Drive: '.$e->getMessage();
            }
        }

        try {
            Storage::disk($recording->disk)->delete($recording->path);
        } catch (Throwable $e) {
            $errors[] = 'No se pudo eliminar del almacenamiento local: '.$e->getMessage();
        }

        try {
            $recording->delete();
        } catch (Throwable $e) {
            $errors[] = 'No se pudo eliminar el registro de base de datos: '.$e->getMessage();
        }

        if ($errors !== []) {
            return response()->json([
                'message' => 'Eliminacion incompleta.',
                'errors' => $errors,
            ], 500);
        }

        return response()->json([
            'message' => 'Video eliminado por completo.',
        ]);
    }

    private function generateUniqueShareToken(): string
    {
        do {
            $token = Str::random(48);
        } while (Recording::query()->where('share_token', $token)->exists());

        return $token;
    }
}
