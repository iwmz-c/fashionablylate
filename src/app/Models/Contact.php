<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'last_name',
        'first_name',
        'gender',
        'email',
        'tel',
        'address',
        'building',
        'detail',
    ];

    public function getGenderTextAttribute() {
        return match($this->gender) {
            1 => '男性',
            2 => '女性',
            3 => 'その他',
        };
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function scopeSearch($query, $keyword) {
        if (!empty($keyword)) {
            return $query->where(function($q) use ($keyword) {
                $q->where('last_name', 'like', "%{$keyword}%")
                ->orWhere('first_name', 'like', "%{$keyword}%")
                ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ["%{$keyword}%"])
                ->orWhere('email', 'like', "%{$keyword}%");
            });
        }
        return $query;
    }

    public function scopeGender($query, $gender) {
        if (!empty($gender)) {
            return $query->where('gender',$gender);
        }
        return $query;
    }

    public function scopeCategory($query, $category_id) {
        if (!empty($category_id)) {
            return $query->where('category_id', $category_id);
        }
        return $query;
    }

    public function scopeCreatedDate($query, $date) {
        if($date) {
            return $query->whereDate('created_at', $date);
        }
        return $query;
    }

}