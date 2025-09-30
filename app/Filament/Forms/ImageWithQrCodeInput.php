<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Group;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageWithQrCodeInput extends Group
{
    protected string $imageFieldName = 'image_upload';
    protected string $qrCodeFieldName = 'qr_code_data';


    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            FileUpload::make($this->imageFieldName)
                ->label('Upload Image with QR Code')
                ->helperText('Upload an image containing a QR code to automatically extract the data')
                ->rules(['image'])
                ->nullable()
                ->openable()
                ->optimize('webp')
                ->imageResizeMode('cover')
                ->image()
                ->imageEditor()
                ->disk('public')
                ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                ->live()
                ->afterStateUpdated(function (Get $get, Set $set, $state) {
                    if ($state) {
                        // Use a small delay to ensure file is fully processed
                        $this->readQrCodeFromImage($state, $set);
                    }
                })
                ->saveUploadedFileUsing(function ($file, $get, $set) {
                    // This ensures the file is properly saved before processing
                    $path = $file->store('images/Online/QRCode', 'public');
                    $this->readQrCodeFromImage($path, $set);
                    return $path;
                })
                ->dehydrated(false),

            TextInput::make($this->qrCodeFieldName)
                ->label('Extracted QR Code Data')
                ->helperText('QR code data automatically extracted from uploaded image')
                ->disabled()
                ->dehydrated(false)
                ->columnSpanFull(),
        ])
        ->columns(1)
        ->live();
    }

    protected function readQrCodeFromImage($imagePath, Set $set): void
    {
        try {
            // Debug: Log the image path
            Log::info('ImageWithQrCodeInput - Image path: ' . $imagePath);

            // Handle different path formats
            $fullPath = $this->resolveImagePath($imagePath);

            Log::info('ImageWithQrCodeInput - Full path: ' . $fullPath);
            Log::info('ImageWithQrCodeInput - File exists: ' . (file_exists($fullPath) ? 'Yes' : 'No'));

            // Check if file exists, wait for upload if needed
            if (!file_exists($fullPath)) {
                if (!$this->waitForFileUpload($imagePath)) {
                    $set($this->qrCodeFieldName, 'Error: Image file not found at ' . $fullPath);
                    return;
                }
                $fullPath = $this->resolveImagePath($imagePath);
            }

            // Try to read QR code from the image
            $qrCodeData = $this->extractQrCodeFromImage($fullPath);

            if ($qrCodeData) {
                $set($this->qrCodeFieldName, $qrCodeData);
            } else {
                $set($this->qrCodeFieldName, 'No QR code found in the uploaded image');
            }

        } catch (\Exception $e) {
            // Handle error gracefully
            Log::error('ImageWithQrCodeInput Error: ' . $e->getMessage());
            $set($this->qrCodeFieldName, 'Error reading QR code: ' . $e->getMessage());
        }
    }

    protected function extractQrCodeFromImage($imagePath): ?string
    {
        try {
            // Method 1: Try using Zxing QR Reader (if available)
            if (class_exists('Zxing\QrReader')) {
                try {
                    $qrcodeClass = 'Zxing\QrReader';
                    $qrcode = new $qrcodeClass($imagePath);
                    $text = $qrcode->text();
                    if ($text) {
                        return $text;
                    }
                } catch (\Exception $e) {
                    // Continue to next method
                }
            }

            // Method 2: Try using exec with zbarimg (if available on system)
            if (function_exists('exec')) {
                $output = [];
                $returnCode = 0;
                exec("zbarimg -q --raw {$imagePath} 2>/dev/null", $output, $returnCode);

                if ($returnCode === 0 && !empty($output)) {
                    return implode('', $output);
                }
            }

            // Method 3: Try using exec with zxing (if available on system)
            if (function_exists('exec')) {
                $output = [];
                $returnCode = 0;
                exec("zxing {$imagePath} 2>/dev/null", $output, $returnCode);

                if ($returnCode === 0 && !empty($output)) {
                    return implode('', $output);
                }
            }

            // Method 4: Fallback - return basic image info
            $imageName = basename($imagePath);
            $timestamp = now()->format('Y-m-d H:i:s');
            return "Image: {$imageName} | Uploaded: {$timestamp} | QR Code: Not detected";

        } catch (\Exception $e) {
            return null;
        }
    }

    public function getImageFieldName(): string
    {
        return $this->imageFieldName;
    }

    public function getQrCodeFieldName(): string
    {
        return $this->qrCodeFieldName;
    }

    protected function resolveImagePath($imagePath): string
    {
        // If it's already a full path
        if (file_exists($imagePath)) {
            return $imagePath;
        }

        // If it's a relative path from storage
        if (Storage::disk('public')->exists($imagePath)) {
            return Storage::disk('public')->path($imagePath);
        }

        // Try different path constructions
        $possiblePaths = [
            storage_path('app/public/' . $imagePath),
            public_path('storage/' . $imagePath),
            storage_path('app/' . $imagePath),
            $imagePath
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Return the first possible path for error reporting
        return $possiblePaths[0];
    }

    protected function waitForFileUpload($imagePath, $maxAttempts = 5): bool
    {
        for ($i = 0; $i < $maxAttempts; $i++) {
            $fullPath = $this->resolveImagePath($imagePath);
            if (file_exists($fullPath)) {
                return true;
            }
            sleep(1);
        }
        return false;
    }
}
