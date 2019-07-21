<?php
/**
 * Created by PhpStorm.
 * Project: world-patriotovsky
 * User: Anton Neverov <neverov12@gmail.com>
 * Date: 22.07.2019
 * Time: 1:21
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'galleries';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

}