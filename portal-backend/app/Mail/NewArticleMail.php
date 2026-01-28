<?php

namespace App\Mail;

use App\Models\Article;
use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewArticleMail extends Mailable
{
    use Queueable, SerializesModels;

    public Article $article;
    public User $member;
    public string $siteName;
    public ?string $logoDataUrl;
    public string $articleUrl;
    public ?string $thumbnailUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Article $article, User $member)
    {
        $this->article = $article;
        $this->member = $member;
        $this->siteName = SiteSetting::get('site_name', 'Portal Berita');
        $this->logoDataUrl = $this->getLogoDataUrl();
        $this->articleUrl = url('/berita/' . $article->slug);
        $this->thumbnailUrl = $this->getThumbnailDataUrl();
    }

    /**
     * Get thumbnail as base64 data URL for email embedding
     * Falls back to absolute URL if conversion fails
     */
    protected function getThumbnailDataUrl(): ?string
    {
        if (!$this->article->thumbnail) {
            return null;
        }

        $thumbnail = $this->article->thumbnail;
        
        // If already absolute URL, try to convert to base64
        if (str_starts_with($thumbnail, 'http')) {
            return $this->convertUrlToDataUrl($thumbnail);
        }

        // Get file path
        $path = ltrim($thumbnail, '/');
        $fullPath = null;
        
        if (str_starts_with($path, 'storage/')) {
            $storagePath = str_replace('storage/', '', $path);
            $fullPath = storage_path('app/public/' . $storagePath);
        } else {
            $fullPath = public_path($path);
        }

        // Try to convert to base64 data URL
        if ($fullPath && file_exists($fullPath)) {
            try {
                $fileContents = file_get_contents($fullPath);
                $mimeType = mime_content_type($fullPath);
                return 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);
            } catch (\Exception $e) {
                \Log::warning('Failed to convert thumbnail to data URL', ['error' => $e->getMessage()]);
            }
        }

        // Fallback to absolute URL
        if (str_starts_with($path, 'storage/')) {
            return url($path);
        }
        return url('storage/' . $path);
    }

    /**
     * Convert external URL to base64 data URL
     */
    protected function convertUrlToDataUrl(string $url): ?string
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'user_agent' => 'Mozilla/5.0'
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);
            
            $fileContents = @file_get_contents($url, false, $context);
            
            if ($fileContents) {
                // Get MIME type from headers or guess from extension
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($fileContents);
                return 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to convert URL to data URL', ['url' => $url, 'error' => $e->getMessage()]);
        }
        
        return $url; // Return original URL as fallback
    }

    /**
     * Convert logo to base64 data URL for email embedding
     */
    protected function getLogoDataUrl(): ?string
    {
        $logoUrl = SiteSetting::get('logo_url');
        
        if (!$logoUrl) {
            return null;
        }

        try {
            $logoPath = ltrim($logoUrl, '/');
            $fullPath = null;
            
            if (str_starts_with($logoPath, 'storage/')) {
                $storagePath = str_replace('storage/', '', $logoPath);
                $fullPath = storage_path('app/public/' . $storagePath);
            } else {
                $fullPath = public_path($logoPath);
            }

            if ($fullPath && file_exists($fullPath)) {
                $fileContents = file_get_contents($fullPath);
                $mimeType = mime_content_type($fullPath);
                return 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to get logo for email', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[' . $this->siteName . '] ' . $this->article->title,
        );
    }

    /**
     * Build the message
     */
    public function build()
    {
        return $this->view('emails.new-article')
            ->with([
                'article' => $this->article,
                'member' => $this->member,
                'siteName' => $this->siteName,
                'logoDataUrl' => $this->logoDataUrl,
                'articleUrl' => $this->articleUrl,
                'thumbnailUrl' => $this->thumbnailUrl,
            ]);
    }
}
