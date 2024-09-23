<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Define the search scope
    public function scopeSearch($query, $request)
    {
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }
        return $query;
    }

    // Define the paginator scope
    public function scopePaginator($query, $request)
    {
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        return $query->paginate($perPage);
    }
}