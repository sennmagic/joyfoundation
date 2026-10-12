<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\EnquiryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $email
 * @property string $topic
 * @property string $message
 * @property bool $is_read
 */
class Enquiry extends Model
{
    /** @use HasFactory<EnquiryFactory> */
    use HasFactory;

    public const TOPICS = [
        'general' => 'General enquiry',
        'volunteering' => 'Volunteering',
        'partnership' => 'Partnership',
        'support' => 'Supporting our work',
    ];

    protected $fillable = ['name', 'email', 'topic', 'message', 'is_read'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['is_read' => 'boolean'];
    }
}
