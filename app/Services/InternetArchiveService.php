<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InternetArchiveService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('internetarchive.base_url'), '/');
    }

    public function search(string $query, ?int $year = null): array
    {
        try {
            $searchQuery = 'title:("' . addslashes($query) . '") AND mediatype:(movies)';

            if ($year) {
                $searchQuery .= " AND year:$year";
            }

            $response = Http::timeout(30)->get($this->baseUrl . '/advancedsearch.php', [
                'q' => $searchQuery,
                'output' => 'json',
                'rows' => 50,
            ]);

            if (! $response->successful()) {
                return [];
            }

            $docs = $response->json('response.docs', []);

            return array_map(fn ($doc) => [
                'identifier' => $doc['identifier'] ?? null,
                'title' => $doc['title'] ?? null,
                'description' => $doc['description'] ?? null,
                'year' => $doc['year'] ?? null,
                'creator' => $doc['creator'] ?? null,
                'mediatype' => $doc['mediatype'] ?? null,
                'licenseurl' => $doc['licenseurl'] ?? null,
                'rights' => $doc['rights'] ?? null,
            ], $docs);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getMetadata(string $identifier): array
    {
        try {
            $response = Http::timeout(30)->get($this->baseUrl . '/metadata/' . rawurlencode($identifier));

            if (! $response->successful()) {
                return [];
            }

            $metadata = $response->json('metadata', []);

            return [
                'identifier' => $metadata['identifier'] ?? $identifier,
                'title' => $metadata['title'] ?? null,
                'description' => $metadata['description'] ?? null,
                'creator' => $metadata['creator'] ?? null,
                'year' => $metadata['year'] ?? null,
                'date' => $metadata['date'] ?? null,
                'licenseurl' => $metadata['licenseurl'] ?? null,
                'rights' => $metadata['rights'] ?? null,
                'mediatype' => $metadata['mediatype'] ?? null,
                'collection' => $metadata['collection'] ?? null,
            ];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getFiles(string $identifier): array
    {
        try {
            $response = Http::timeout(30)->get($this->baseUrl . '/metadata/' . rawurlencode($identifier));

            if (! $response->successful()) {
                return [];
            }

            $files = $response->json('files', []);

            return array_map(fn ($file) => [
                'name' => $file['name'] ?? null,
                'source' => $file['source'] ?? null,
                'format' => $file['format'] ?? null,
                'size' => $file['size'] ?? null,
                'mtime' => $file['mtime'] ?? null,
                'md5' => $file['md5'] ?? null,
                'crc32' => $file['crc32'] ?? null,
                'sha1' => $file['sha1'] ?? null,
            ], $files);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function findPlayableVideo(string $identifier): ?array
    {
        $files = $this->getFiles($identifier);

        if (empty($files)) {
            return null;
        }

        $playableExtensions = ['mp4', 'webm', 'ogg', 'ogv'];
        $blockedExtensions = ['zip', 'rar', 'pdf', 'jpg', 'jpeg', 'png', 'txt', 'xml', 'json'];

        foreach ($files as $file) {
            $name = $file['name'] ?? null;

            if (! $name) {
                continue;
            }

            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            if (in_array($extension, $blockedExtensions, true)) {
                continue;
            }

            if (in_array($extension, $playableExtensions, true)) {
                return [
                    'name' => $name,
                    'format' => $extension,
                    'size' => $file['size'] ?? null,
                    'url' => $this->baseUrl . '/download/' . rawurlencode($identifier) . '/' . rawurlencode($name),
                ];
            }
        }

        return null;
    }
}
