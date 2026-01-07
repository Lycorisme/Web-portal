<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\ArticleLike;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleInteractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user (usually admin)
        $user = User::first();
        
        if (!$user) {
            $this->command->warn('No users found. Please create a user first.');
            return;
        }

        // Get some articles
        $articles = Article::take(3)->get();

        if ($articles->isEmpty()) {
            $this->command->warn('No articles found. Please create some articles first.');
            return;
        }

        foreach ($articles as $article) {
            // Add likes
            ArticleLike::firstOrCreate([
                'article_id' => $article->id,
                'user_id' => $user->id,
            ]);

            // Add sample comments
            $comments = [
                [
                    'text' => 'Artikel yang sangat informatif! Terima kasih sudah berbagi.',
                    'status' => 'visible',
                ],
                [
                    'text' => 'Saya setuju dengan poin-poin yang disampaikan. Sangat bermanfaat.',
                    'status' => 'visible',
                ],
                [
                    'text' => 'Kapan akan ada update artikel tentang topik serupa?',
                    'status' => 'visible',
                ],
            ];

            foreach ($comments as $commentData) {
                $comment = ArticleComment::create([
                    'article_id' => $article->id,
                    'user_id' => $user->id,
                    'comment_text' => $commentData['text'],
                    'status' => $commentData['status'],
                    'is_admin_reply' => false,
                    'ip_address' => '127.0.0.1',
                ]);

                // Add admin reply to first comment
                if ($commentData['text'] === 'Artikel yang sangat informatif! Terima kasih sudah berbagi.') {
                    ArticleComment::create([
                        'article_id' => $article->id,
                        'user_id' => $user->id,
                        'parent_id' => $comment->id,
                        'comment_text' => 'Terima kasih atas komentarnya! Senang Anda menyukai artikelnya.',
                        'status' => 'visible',
                        'is_admin_reply' => true,
                        'ip_address' => '127.0.0.1',
                    ]);
                }
            }

            // Add a spam comment for testing
            ArticleComment::create([
                'article_id' => $article->id,
                'user_id' => $user->id,
                'comment_text' => 'Kunjungi situs slot gacor kami untuk bonus besar!',
                'status' => 'spam', // Should be auto-detected as spam
                'is_admin_reply' => false,
                'ip_address' => '192.168.1.100',
            ]);
        }

        $this->command->info('Article interactions seeded successfully!');
    }
}
