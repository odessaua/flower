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
			'popular' => $this->getMainPage(9),
			'mainContent'    => Page::model()->findByPK(15),
			'comments'=>$comments
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
    protected function getMainPage($limit)
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

}
