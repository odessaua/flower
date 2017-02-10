<?php

Yii::import('application.modules.pages.models.Page');

/**
 * Store start page controller
 */
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
class IndexController extends Controller
{

	/**
	 * Display start page
	 */
	public function actionIndex()
	{
		$comments = Yii::app()->db->createCommand()
		    ->select('name, text,created')
		    ->from('Comments')
		    ->limit(3)
		    ->order('created DESC')
		    ->queryAll();
		    // var_dump( Page::model()->findByPK(15));
		$this->render('index', array(
//			'popular' => $this->getPopular(9),
			'popular' => $this->getMainPage(),
			'mainContent'    => Page::model()->findByPK(15),
			'comments'=>$comments,
            'city_seo' => $this->getCitySeo(),
		));
	}

	/**
	 * Renders products list to display on the start page
	 */
	
	public function actionRenderProductsBlock()
	{
		$scope = Yii::app()->request->getQuery('scope');
		switch($scope)
		{
			case 'newest':
				$this->renderBlock($this->getNewest(4));
				break;

			case 'added_to_cart':
				$this->renderBlock($this->getByAddedToCart(4));
				break;
		}
	}

	/**
	 * @param $products
	 */
	protected function renderBlock($products)
	{
		foreach($products as $p)
			$this->renderPartial('_product',array('data'=>$p));
	}

	/**
	 * @param $limit
	 * @return array
	 */
	protected function getPopular($limit)
	{
		return StoreProduct::model()
			->active()
			->byViews()
			->findAll(array('limit'=>$limit));
	}

    /**
     * @param $limit
     * @return array
     */
    protected function getMainPage($limit = 0)
    {
        return StoreProduct::model()
            ->active()
            ->mainPage()
            ->findAll(array('limit'=>$limit));
    }

	/**
	 * @param $limit
	 * @return array
	 */
	protected function getByAddedToCart($limit)
	{
		return StoreProduct::model()
			->active()
			->byAddedToCart()
			->findAll(array('limit'=>$limit));
	}

	/**
	 * @param $limit
	 * @return array
	 */
	protected function getNewest($limit)
	{
		return StoreProduct::model()
			->active()
			->newest()
			->findAll(array('limit'=>$limit));
	}

    public function actionAllcities()
    {
        $lang= Yii::app()->language;
        if($lang == 'ua')
            $lang = 'uk';

        $langArray = SSystemLanguage::model()->findByAttributes(array('code'=>$lang));
        $cities = Yii::app()->db->createCommand()
            ->select('c.name as ename, ct.name,ct.object_id,c.id,ct.language_id')
            ->from('city c')
            ->join('cityTranslate ct', 'c.id=ct.object_id')
            ->where('ct.language_id=:id', array(':id'=>$langArray->id))
            ->order('ct.name, id desc')
            ->queryAll();
        $this->render('all_cities', array('cities' => $cities));
    }

    /**
     * Обработка выбранного города для доставки
     */
    public function actionCity()
    {
        if(!empty($_GET['city_id'])){
            $lang= Yii::app()->language;
            if($lang == 'ua')
                $lang = 'uk';

            $langArray = SSystemLanguage::model()->findByAttributes(array('code'=>$lang));
            $city_translations = CityTranslate::model()->findAllByAttributes(array('object_id' => $_GET['city_id']));
            $city_name = '';
            // название города с переводом
            if(!empty($city_translations)){
                foreach ($city_translations as $city_t) {
                    if($city_t->language_id == $langArray->id){
                        $city_name = $city_t->name;
                        break;
                    }
                }
            }
            // название города без перевода
            if(empty($city_name)){
                $city = City::model()->findByPk($_GET['city_id']);
                if(!empty($city->name)){
                    $city_name = $city->name;
                }
            }
            // сохраняем в сессию
            if(!empty($city_name)){
                Yii::app()->session['_city'] = $city_name;
            }
        }
//        var_dump('actionCity', $_GET, Yii::app()->session['_city'], $city_name);
        $this->actionIndex();
    }

}
