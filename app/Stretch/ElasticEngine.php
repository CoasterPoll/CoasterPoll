<?php

/* Based on ErickTamayo/laravel-scout-elastic. */

namespace ChaseH\Stretch;

use Elasticsearch\Client;
use Illuminate\Support\Collection;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;

class ElasticEngine extends Engine {
    protected $elastic;

    public function __construct(Client $client) {
        $this->elastic = $client;
    }

    public function update($models) {
        $params['body'] = [];

        $models->each(function($model) use (&$params) {
            $params['body'][] = [
                'update' => [
                    '_id' => $model->getKey(),
                    '_index' => $model->searchableAs(),
                    '_type' => $model->searchableAsType(),
                ]
            ];
            $params['body'][] = [
                'doc' => $model->toSearchableArray(),
                'doc_as_upsert' => true,
            ];
        });

        $this->elastic->bulk($params);
    }

    public function delete($models) {
        $params['body'] = [];

        $models->each(function($model) use ($params) {
            $params['body'][] = [
                'delete' => [
                    '_id' => $model->getKey(),
                    '_index' => $model->searchableAs(),
                    '_type' => $model->searchableAsType(),
                ]
            ];
        });

        $this->elastic->bulk($params);
    }

    public function search(Builder $builder) {
        return $this->performSearch($builder, array_filter([
            'numericFilters' => $this->filters($builder),
            'size' => $builder->limit,
        ]));
    }

    public function paginate(Builder $builder, $perPage, $page) {
        $result = $this->performSearch($builder, [
            'numericFilters' => $this->filters($builder),
            'from' => (($page * $perPage) - $perPage),
            'size' => $perPage,
        ]);

        $result['nbPages'] = $result['hits']['total']/$perPage;

        return $result;
    }

    protected function performSearch(Builder $builder, array $options = []) {
        $params = [
            'index' => $builder->model->searchableAs(),
            'type' => $builder->index ?: $builder->model->searchableAsType(),
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [['query_string' => ['query' => "*{$builder->query}*"]]]
                    ]
                ]
            ]
        ];

        if($sort = $this->sort($builder)) {
            $params['body']['sort'] = $sort;
        }

        if(isset($options['from'])) {
            $params['body']['from'] = $options['from'];
        }

        if(isset($options['size'])) {
            $params['body']['size'] = $options['size'];
        }

        if(isset($options['numericFilters']) && count($options['numericFilters'])) {
            $params['body']['query']['bool']['must'] = array_merge($params['body']['query']['bool']['must'], $options['numericFilters']);
        }

        if($builder->callback) {
            return call_user_func($builder->callback,
                $this->elastic,
                $builder->query,
                $params
            );
        }

        return $this->elastic->search($params);
    }

    public function filters(Builder $builder) {
        return collect($builder->wheres)->map(function($value, $key) {
            if(is_array($value)) {
                return ['terms' => [$key => $value]];
            }

            return ['match_phrase' => [$key => $value]];
        })->values()->all();
    }

    public function mapIds($results) {
        return collect($results['hits']['hits'])->pluck('_id')->values();
    }

    public function map($results, $model) {
        if(count($results['hits']['total']) === 0) {
            return Collection::make();
        }

        $keys = collect($results['hits']['hits'])->pluck('_id')->values()->all();

        $models = $model->whereIn($model->getKeyName(), $keys)->get()->keyBy($model->getKeyName());

        return collect($results['hits']['hits'])->map(function ($hit) use ($model, $models) {
            return isset($models[$hit['_id']]) ? $models[$hit['_id']] : null;
        });
    }

    public function getTotalCount($results) {
        return $results['hits']['total'];
    }

    public function sort($builder) {
        if(count($builder->orders) == 0) {
            return null;
        }

        return collect($builder->orders)->map(function($order) {
            return [$order['column'] => $order['direction']];
        })->toArray();
    }
}