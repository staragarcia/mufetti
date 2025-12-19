<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable {
    /**
     * Scope for Full Text Search
     */
    public function scopeFullTextSearch(Builder $query, string $term, array $columns = ['search_vector'])
    {
        // TODO
        $search_term = $this->formatSearchTerm($term);

        return $query
            ->whereRaw("TODO", [$search_term])
            ->selectRaw("TODO as rank", [$search_term])
            ->orderBy('rank', 'desc');
    }

    /**
     * Format search for postgres FTS
     */
    protected function formatSearchTerm(string $term): string 
    {
        return "TODO";
    }
}
