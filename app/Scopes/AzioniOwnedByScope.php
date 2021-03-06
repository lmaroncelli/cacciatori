<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class AzioniOwnedByScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {   
        if (Auth::check()) 
          {
          if(Auth::user()->hasRole('cacciatore'))
            {
            $squadre_cacciatore_capo_ids = Auth::user()->cacciatore->squadreACapo->pluck('id');
            $builder->where('tblAzioniCaccia.user_id', '=', Auth::id())->orWhereIn('tblAzioniCaccia.squadra_id',$squadre_cacciatore_capo_ids);
            }
          }
    }
}