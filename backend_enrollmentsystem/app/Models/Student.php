<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['student_number', 'first_name', 'last_name', 'email'];

    /**
     * Generate the next sequential 6-digit student ID (000001, 000002, ..., 142944).
     */
    public static function generateNextNumber(): string
    {
        $start = (int) config('enrollment.student_id_start', 1);
        $max = 0;

        foreach (DB::table('students')->pluck('student_number') as $num) {
            $n = (int) preg_replace('/\D/', '', (string) $num);
            if ($n > $max) {
                $max = $n;
            }
        }

        $next = max($start, $max + 1);

        if ($next > 999999) {
            throw new \RuntimeException('Student ID limit reached (999999).');
        }

        return sprintf('%06d', $next);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')->withTimestamps();
    }
}
