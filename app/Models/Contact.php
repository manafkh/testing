<?php

namespace App\Models;



use App\Rules\Phone;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact
 * @package App\Models
 * @version October 12, 2019, 2:15 pm UTC
 *
 * @property string first_name
 * @property string last_name
 * @property string email
 * @property string phone
 * @property integer user_id
 */
class Contact extends Model
{
    use SoftDeletes ;


    public $table = 'contacts';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'prov',
        'section_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'user_id',
        'prov'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'phone' => 'required|regex:/(09)[0-9]{8}/',
    ];


    public function user(){
        return $this->belongsTo("App\User");
    }
    public function section(){
        return $this->belongsTo("App\Section");
    }

    
}
