<?php

namespace Modules\Contacts\Interfaces;

interface FileUploaderServiceInterface
{
    public function upload(object $file, string $filePath = 'uploads/'): string;

    public function uploadMultiple(?array $files, string $filesPath = 'uploads/'): ?string;

    public function uploadThumbnail(object $file, ?string $filePath = 'uploads/', ?int $width = 250, ?int $height = 250): string;
}
