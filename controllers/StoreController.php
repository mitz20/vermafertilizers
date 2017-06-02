<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Stock;
use app\models\AddProductForm;

class StoreController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }

    public function actionAddProduct() {
        $mAddProduct = new AddProductForm();
        if ($mAddProduct->load(Yii::$app->request->post()) && $mAddProduct->validate()) {
            $product = Stock::find()
                    ->where(['product_id' => $mAddProduct->product_id . '1'])
                    ->one();
            if (!$product) {
                Yii::$app->session->setFlash('error', 'Product already exists.');
                return $this->goBack();
            }
            $mStock = new Stock();
            $mStock->name = $mAddProduct->name;
            $mStock->product_id = $mAddProduct->product_id;
            $mStock->price_per_unit = $mAddProduct->price;
            $mStock->units = $mAddProduct->units;
            $mStock->is_sold = 1;
            try {
                $mStock->save(false);
            } catch (Exception $ex) {
                Yii::$app->session->setFlash('error', $ex->getMessage());
                return $this->goBack();
            }
            Yii::$app->session->setFlash('success', 'Item added successfully.');
        }
        return $this->render('addProduct', [
                    'model' => $mAddProduct,
        ]);
    }

    public function actionViewStock() {
        $modelStock = new Stock;
        return $this->render('viewStock');
    }

}
