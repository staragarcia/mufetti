<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable {
    /**
     * Scope for Full Text Search
     */
    public function scopeFullTextSearch(Builder $query, string $term)
    {
        $search_term = $this->formatSearchTerm($term);

        return $query
            ->whereRaw("search_vector @@ to_tsquery('english', ?)", [$search_term])
            ->selectRaw("*, ts_rank(search_vector, to_tsquery('english', ?)) as rank", [$search_term])
            ->orderBy('rank', 'desc');
    }

    /**
     * Format search for postgres FTS
     */
    protected function formatSearchTerm(string $term): string 
    {
        // clean special characters and split by spaces
        $words = preg_split('/\s+/', trim($term));
        $words = array_filter($words);
        
        // adding :* for prefix matching and better search system
        $words = array_map(function($word) {

            // remove non-word characters except hyphens
            $word = preg_replace('/[^\w\s-]/', '', $word);

            return $word . ':*';
        }, $words);
        
        // add AND operator for make search better
        return implode(' & ', $words);
    }
}
