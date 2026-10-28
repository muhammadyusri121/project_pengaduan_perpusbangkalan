<?php

namespace App\Services;

use App\Models\Complaint;
use Illuminate\Support\Str;

class TrackingCodeService
{
    /**
     * Generate kode tracking unik (8 karakter, uppercase alphanumeric)
     * Format: PBK-XXXXXX (PBK = Perpus Bangkalan)
     */
    public static function generate(): string
    {
        do {
            $code = 'PBK-' . strtoupper(Str::random(6));
        } while (Complaint::where('tracking_code', $code)->exists());

        return $code;
    }
}
