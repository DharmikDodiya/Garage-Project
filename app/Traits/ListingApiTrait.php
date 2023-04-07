<?php

namespace App\Traits;

trait ListingApiTrait
{

    /**
    * list validation
    */
    public function ListingValidation()
    {
        $this->validate(request(), [
        'page'          => 'integer|nullable',
        'perPage'       => 'integer|nullable',
        'search'        => 'nullable',
    ]);
        return true;
    }

    public function filterSearchPagination($query, $searchable_fields)
    {
        /**
         * Search with searchable fields
         */
        if (request()->search) {
            $search = request()->search;
            $query  = $query->where(function ($q) use ($search, $searchable_fields) {
                /* adding searchable fields to orwhere condition */
                foreach ($searchable_fields as $searchable_field) {
                    $q->orWhere($searchable_field, 'like', '%'.$search.'%');
                    $q->orWhere($searchable_field,$search);
                }
            });
        }

        /* Pagination */
        $count          = $query->count();
        if (request()->page || request()->perPage) {
            $page       = request()->page;
            $perPage    = request()->perPage ?? 10;
            $query      = $query->skip($perPage * ($page - 1))->take($perPage);
        }
        return ['query' => $query, 'count' => $count];
    }
}



?>