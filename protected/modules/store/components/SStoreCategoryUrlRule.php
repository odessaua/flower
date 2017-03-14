<?php

Yii::import('application.modules.pages.models.PageCategory');

class SStoreCategoryUrlRule extends CBaseUrlRule
{
	public $connectionID = 'db';
	public $urlSuffix    = '';

	public function createUrl($manager,$route,$params,$ampersand)
	{
		if($route==='store/category/view')
		{
			$url=trim($params['url'],'/');
			unset($params['url']);

			$parts=array();
			if(!empty($params))
			{
				foreach ($params as $key=>$val)
					$parts[]=$key.'/'.$val;

				$url .= '/'.implode('/', $parts);
			}

			return $url.$this->urlSuffix;
		}
		return false;
	}

	public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
	{
		if(empty($pathInfo))
			return false;

		if($this->urlSuffix)
			$pathInfo = strtr($pathInfo, array($this->urlSuffix=>''));

		foreach($this->getAllPaths() as $path)
		{
            // is product
            if(
                ($path !== '')
                && (strpos($pathInfo, 'page/') === false)
                && (strpos($pathInfo, '.html') !== false)
            ){
                $ex_path = explode('/', $pathInfo);
                $_GET['url'] = str_replace('.html', '', end($ex_path));
                return 'store/frontProduct/view';//http://flowers3.loc/product/bouquet-the-trembling-heart.html
            }

            // is category
			if($path !== '' && strpos($pathInfo, $path) === 0)
			{
				$_GET['url'] = $path;

				$params = ltrim(substr($pathInfo,strlen($path)), '/');
				Yii::app()->urlManager->parsePathInfo($params);

				return 'store/category/view';
			}
		}

		return false;
	}

	protected function getAllPaths()
	{
		$allPaths = Yii::app()->cache->get('SStoreCategoryUrlRule');

		if($allPaths === false)
		{
			$allPaths = Yii::app()->db->createCommand()
				->from('StoreCategory')
				->select('full_path')
				->queryColumn();

			// Sort paths by length.
			usort($allPaths, function($a, $b) {
				return strlen($b) - strlen($a);
			});

			Yii::app()->cache->set('SStoreCategoryUrlRule', $allPaths);
		}

		return $allPaths;
	}

}
