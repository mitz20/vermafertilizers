<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\Pagination;
//form models
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ProductForm;
use app\models\SearchForm;
use app\models\CreateUserForm;
//db models
use app\models\Stock;
use app\models\User;

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
            Yii::$app->session->set('cart', NULL);
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

    //creates user
    public function actionCreateUser() {
        $mCreateUserForm = new CreateUserForm;
        if (Yii::$app->request->isPost && $mCreateUserForm->load(Yii::$app->request->post()) && $mCreateUserForm->validate()) {
            $user = new User();
            $user->username = $mCreateUserForm->username;
            $user->password = $mCreateUserForm->password;
            $user->email = $mCreateUserForm->email;
            $user->active = 1;
            try {
                ($user->save()) ? (Yii::$app->session->setFlash('success', 'User created successfully.')) : (Yii::$app->session->setFlash('error', 'User cannot be created.'));
            } catch (Exception $ex) {
                Yii::$app->session->setFlash('error', $ex->getMessage());
            }
        }
        return $this->render('createUser', [
                    'model' => $mCreateUserForm,
        ]);
    }

    //adds product with unique product id
    public function actionAddProduct() {
        $mProductForm = new ProductForm();
        if (Yii::$app->request->isPost && $mProductForm->load(Yii::$app->request->post()) && $mProductForm->validate()) {
            $mProductForm->product_id = strtoupper($mProductForm->product_id);
            $product = Stock::find()
                    ->where(['product_id' => $mProductForm->product_id])
                    ->one();
            if ($product) {
                Yii::$app->session->setFlash('error', 'Product already exists.');
                return $this->redirect(Yii::$app->request->referrer);
            }
            $mStock = new Stock();
            $mStock->name = $mProductForm->name;
            $mStock->product_id = $mProductForm->product_id;
            $mStock->price_per_unit = $mProductForm->price;
            $mStock->units = $mProductForm->units;
            $mStock->is_active = 1;
            try {
                ($mStock->save()) ? (Yii::$app->session->setFlash('success', 'Product added successfully.')) : (Yii::$app->session->setFlash('error', 'Product cannot be saved.'));
            } catch (Exception $ex) {
                Yii::$app->session->setFlash('error', $ex->getMessage());
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->render('addProduct', [
                    'model' => $mProductForm,
        ]);
    }

    //listing of products with option to add to cart
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

    //detailed product view
    public function actionViewProductDetails() {
        if (empty(Yii::$app->request->get('__pid'))) {
            Yii::$app->session->setFlash('error', 'Product Id could not be retrieved.');
            return $this->redirect(Yii::$app->request->referrer);
        }
        $product = Stock::find()
                ->where(['product_id' => base64_decode(Yii::$app->request->get('__pid'))])
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

    //updating product details like price, units
    public function actionUpdateProduct() {
        $mProductForm = new ProductForm();
        if (Yii::$app->request->isPost && $mProductForm->load(Yii::$app->request->post()) && $mProductForm->validate()) {
            $stock = Stock::find()
                    ->where(['product_id' => $mProductForm->product_id])
                    ->andWhere(['is_active' => '1'])
                    ->one();
            if ($stock) {
                $stock->name = $mProductForm->name;
                $stock->units = $mProductForm->units;
                $stock->price_per_unit = $mProductForm->price;

                try {
                    ($stock->save()) ? (Yii::$app->session->setFlash('success', 'Product details updated successfully.')) : (Yii::$app->session->setFlash('error', 'Product details cannot be updated.'));
                } catch (Exception $ex) {
                    Yii::$app->session->setFlash('error', $ex->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Product not found in database.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Incorrect or No data available.');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    //adding/removing item to/from cart (session)
    public function actionUpdateCart() {
        if (empty(Yii::$app->request->get('__pid')) || empty(Yii::$app->request->get('action'))) {
            Yii::$app->session->setFlash('error', 'Action cannot be performed. Please try again.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $cartItems = (Yii::$app->session->get('cart'));
        $count = count($cartItems);
        if (Yii::$app->request->get('action') == 'add') {
            //if empty cart or element doesn't exist in cart
            if ((!$count) || ( $count && !in_array(base64_decode(Yii::$app->request->get('__pid')), $cartItems))) {
                $cartItems[] = base64_decode(Yii::$app->request->get('__pid'));
                Yii::$app->session->set('cart', $cartItems);
                Yii::$app->session->setFlash('success', 'Product added to cart.');
            } else {
                Yii::$app->session->setFlash('error', 'Product already exists in cart.');
            }
        } elseif (Yii::$app->request->get('action') == 'remove') {
            //cart should not be empty and product must exist in cart
            if ($count && in_array(base64_decode(Yii::$app->request->get('__pid')), $cartItems)) {
                $key = array_search(base64_decode(Yii::$app->request->get('__pid')), $cartItems);
                unset($cartItems[$key]);
                Yii::$app->session->set('cart', $cartItems);
                Yii::$app->session->setFlash('success', 'Product removed from cart.');
            } else {
                Yii::$app->session->setFlash('error', 'Product does not exist in cart.');
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    //final cart configuration with modification options
    public function actionCart() {
        $productIds = (Yii::$app->session->get('cart')) ? (Yii::$app->session->get('cart')) : [];
        $products = Stock::find()
                ->where(['IN', 'product_id', $productIds])
                ->all();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $finalProductList = [];
            $totalCost = 0;
            foreach ($products as $key => $value) {
                if($value->units >= $post[$value->product_id]){
                    $finalProductList[$key]['product_id'] = $value->product_id;
                    $finalProductList[$key]['name'] = $value->name;
                    $finalProductList[$key]['price_per_unit'] = $value->price_per_unit;
                    $finalProductList[$key]['quantity'] = $post[$value->product_id];
                    $totalCost += ($finalProductList[$key]['quantity'] * $finalProductList[$key]['price_per_unit']);
                }
            }
            Yii::$app->session->set('products', $finalProductList);
            Yii::$app->session->set('cost', $totalCost);
            return $this->render('cartReview', [
                'products' => $finalProductList,
                'totalCost' => $totalCost
            ]);
        }
        return $this->render('cart', [
                    'products' => $products,
        ]);
    }
    
    //update db , generate bill, reset sessions
    public function actionGenerateBill(){
        $finalProducts = Yii::$app->session->get('products');
        if(!$finalProducts){
            Yii::$app->session->setFlash('error', 'Add product to cart first.');
            return $this->redirect(['store/view-stock']);
        }
        $totalCost = 0;
        foreach ($finalProducts as $value) {
            $product = Stock::find()
                    ->where(['product_id' => $value['product_id']])
                    ->one();
            $product->units -= $value['quantity'] ;
            $product->save();
            unset($product);
            $totalCost += ($value['quantity']*$value['price_per_unit']);
        }
        
        if($totalCost == (Yii::$app->session->get('cost'))){
            //generate bill pdf
            //reset session vars
            //redirect back
        }
        echo '<pre>';print_r(Yii::$app->session->get('cost'));die;
    }
    
    

    //to check uniqueness of product_id before product addition through ajax
    public function actionIsPidUnique() {
        $mProductForm = new ProductForm();
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
    
    //to check uniqueness of username before create user through ajax
    public function actionIsUsernameUnique() {
        $mCreateUserForm = new CreateUserForm();
        if (Yii::$app->request->isAjax && $mCreateUserForm->load(Yii::$app->request->post()) && $mCreateUserForm->validate()) {
            $user = User::find()
                    ->where(['username' => $mCreateUserForm->username])
                    ->one();
            ($user == NULL) ? print_r('1') : print_r('0');
            die;
        }
        print_r('1');
        die;
    }

}
