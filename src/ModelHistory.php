<?php

namespace Rukhsar;

use Auth;

use Eloquent;

class ModelHistory extends Eloquent
{

  public $timestamps = false;

  protected $table = 'model_history';

  protected $dates = ['executed_at'];

  protected $fillable = ['user_id','message','executed_at','additional_information','link',];

  protected $casts = ['additional_information' => 'array',];

  public static function boot()
  {
    parent::boot();

    slef::saving(function ($a)
    {
      // For Sentinel Authentication use
      $a->user_id  = Sentinel::check() ? Sentinel::getUser()->id : null;

      // For laravel built in authentication use below
      $a->user_id  = Auth::check() ? Auth::id() : null;

      $a->executed_at = time();

    });
  }

  public function user()
  {
    // updated with your User model path
    return $this->belongsTo('App\User');
  }

  public function contextAvailable()
  {
    return $this->context_type !== null && $this->context_id !== null;
  }

  public function getDisplayContextAttribute()
  {
      if (!$this->contextAvailable())
      {
          return '';
      }

      if ($this->link === null)
      {

          return "{$this->context_type} #{$this->context_id}";
      }

      return "<a href=\"{$this->link}\">{$this->context_type} #{$this->context_id}";
  }

  public function context()
    {
        return $this->morphTo();
    }
}