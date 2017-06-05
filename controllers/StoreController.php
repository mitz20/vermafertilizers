<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Stock;
use app\models\ProductForm;
use app\models\SearchForm;

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
        $mAddProduct = new ProductForm();
        if ($mAddProduct->load(Yii::$app->request->post()) && $mAddProduct->validate()) {
            $mAddProduct->product_id = strtoupper($mAddProduct->product_id);
            $product = Stock::find()
                    ->where(['product_id' => $mAddProduct->product_id])
                    ->one();
            if ($product) {
                Yii::$app->session->setFlash('error', 'Product already exists.');
                return $this->refresh();
            }
            $mStock = new Stock();
            $mStock->name = $mAddProduct->name;
            $mStock->product_id = $mAddProduct->product_id;
            $mStock->price_per_unit = $mAddProduct->price;
            $mStock->units = $mAddProduct->units;
            $mStock->is_active = 1;
            try {
                if (!$mStock->save(false)) {
                    Yii::$app->session->setFlash('error', 'Product cannot be saved.');
                    return $this->refresh();
                }
            } catch (Exception $ex) {
                Yii::$app->session->setFlash('error', $ex->getMessage());
                return $this->refresh();
            }
            Yii::$app->session->setFlash('success', 'Product added successfully.');
        }
        return $this->render('addProduct', [
                    'model' => $mAddProduct,
        ]);
    }

    public function actionViewStock() {
        $search = (Yii::$app->request->get('search')) ? (Yii::$app->request->get('search')) : '';
        $mSearchForm = new SearchForm();
        if (Yii::$app->request->post() && $mSearchForm->load(Yii::$app->request->post()) && $mSearchForm->validate()) {
            $search = $mSearchForm->search;
        }
        $query = Stock::find()
                ->where(['like', 'product_id', $search])
                ->orWhere(['like', 'name', $search])
                ->andWhere(['is_active' => '1']);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 2,
            'params' => [
                'search' => $search,
                'page' => Yii::$app->request->get('page'),
            ],
        ]);
        $models = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

        return $this->render('viewStock', [
                    'models' => $models,
                    'pages' => $pages,
                    'search' => $mSearchForm,
        ]);
    }

    public function actionViewProductDetails($baseEncodedPid = '') {
        if (empty(Yii::$app->request->get('__id'))) {
            Yii::$app->session->setFlash('error', 'Product Id could not be retrieved.');
            return $this->redirect(Yii::$app->request->referrer);
        }
        $product = Stock::find()
                ->where(['product_id' => base64_decode(Yii::$app->request->get('__id'))])
                ->andWhere(['is_active' => '1'])
                ->one();

        if ($product == NULL) {
            Yii::$app->session->setFlash('error', 'Product not found.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $mProductForm = new ProductForm();
        $mProductForm->name = $product->name;
        $mProductForm->product_id = $product->product_id;
        $mProductForm->price = $product->price_per_unit;
        $mProductForm->units = $product->units;

        return $this->render('viewProduct', [
                    'model' => $mProductForm,
        ]);
    }

    public function actionUpdateProduct() {
        if (Yii::$app->request->isPost) {
            $mProductForm = new ProductForm();
            if ($mProductForm->load(Yii::$app->request->post()) && $mProductForm->validate()) {
                $stock = Stock::find()
                        ->where(['product_id' => $mProductForm->product_id])
                        ->andWhere(['is_active' => '1'])
                        ->one();
                if($stock){
                    $stock->name = $mProductForm->name;
                    $stock->units = $mProductForm->units;
                    $stock->price_per_unit = $mProductForm->price;
                    
                    try{
                        if(!$stock->save()){
                            Yii::$app->session->setFlash('error', 'Product details cannot be updated.');
                        }else{
                            Yii::$app->session->setFlash('success', 'Product details updated successfully.');
                        }
                    } catch (Exception $ex) {
                        Yii::$app->session->setFlash('error', $ex->getMessage());
                    }
                }else{
                    Yii::$app->session->setFlash('error', 'Product not found in database.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Problem while validating form.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'No data to update.');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionIsPidUnique() {
        $mAddProductForm = new ProductForm();
        if (Yii::$app->request->isAjax && $mProductForm->load(Yii::$app->request->post()) && $mProductForm->validate()) {
            $product = Stock::find()
                    ->where(['product_id' => $mProductForm->product_id])
                    ->one();
            ($product == NULL) ? print_r('1') : print_r('0');
            die;
        }
        print_r('1');
        die;
    }

}
