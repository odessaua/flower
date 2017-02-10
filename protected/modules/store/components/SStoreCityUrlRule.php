<?php

//Yii::import('application.modules.pages.models.PageCategory');

/**
 * Обработка городов в URL для главных страниц
 * Class SStoreCityUrlRule
 */
class SStoreCityUrlRule extends CBaseUrlRule
{
	public $connectionID = 'db';
	public $urlSuffix    = '';

    // этот метод не проверял!!!
	public function createUrl($manager,$route,$params,$ampersand)
	{
		if($route==='store/index/city')
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

		foreach($this->getAllPaths() as $city_id => $path)
		{
			if($path !== '' && strpos($pathInfo, $path) === 0)
			{
				$_GET['url'] = $path;
                $_GET['city_id'] = $city_id;

				$params = ltrim(substr($pathInfo,strlen($path)), '/');
				Yii::app()->urlManager->parsePathInfo($params);

				return 'store/index/city';
			}
		}

		return false;
	}

	protected function getAllPaths()
	{
		$allPaths = Yii::app()->cache->get('SStoreCityUrlRule');

		if($allPaths === false)
		{
			$cities = Yii::app()->db->createCommand()
				->from('city')
				->select('id, name')
				->queryAll();

			if(!empty($cities)){
                foreach ($cities as $city) {
                    $allPaths[$city['id']] = CSlug::url_slug($city['name']);
                }

            }

			Yii::app()->cache->set('SStoreCityUrlRule', $allPaths);
		}

		return $allPaths;
	}

}
