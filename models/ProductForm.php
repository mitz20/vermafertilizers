<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ProductForm extends Model
{
    public $id;
    public $name;
    public $product_id;
    public $units;
    public $price;
    public $other;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'units', 'price', 'product_id'], 'required'],
            [['name', 'units', 'price', 'product_id'], 'trim'],
            [['units'],'match', 'pattern' => '/^[0-9]+$/', 'message' => 'Only numbers are allowed.'],
            [['price'],'match', 'pattern' => '/^[0-9 .]+$/', 'message' => 'Only numbers are allowed.'],
            [['product_id'],'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Only alphanumeric characters are allowed.'],
            [['units','price'], 'string', 'max'=>'9'],
            [['name'], 'string', 'max'=>'20'],
            [['product_id'], 'string', 'max'=>'20'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'units' => 'No. of units',
            'price' => 'Price in rupees',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
//    public function contact($email)
//    {
//        if ($this->validate()) {
//            Yii::$app->mailer->compose()
//                ->setTo($email)
//                ->setFrom([$this->email => $this->name])
//                ->setSubject($this->subject)
//                ->setTextBody($this->body)
//                ->send();
//
//            return true;
//        }
//        return false;
//    }
}
