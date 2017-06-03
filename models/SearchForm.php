<?php

namespace app\models;

use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SearchForm extends Model
{
    public $search;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['product_id'],'match', 'pattern' => '/^[a-zA-Z0-9]+$/'],
            ['search', 'string', 'length' => [1, 30]],
        ];
    }
    
}
