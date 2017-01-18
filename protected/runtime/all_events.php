<?php 

class Accounting1cModuleEvents
{

	/**
	 * @return array
	 */
	public function getEvents()
	{
		return array(
			array('StoreCategory', 'onAfterDelete', array($this, 'deleteExternalCategory')),
			array('StoreAttribute', 'onAfterDelete', array($this, 'deleteExternalAttribute')),
			array('StoreProduct', 'onAfterDelete', array($this, 'deleteExternalProduct')),
		);
	}

	/**
	 * @param $event
	 */
	public function deleteExternalCategory($event)
	{
		Yii::import('application.modules.accounting1c.components.C1ExternalFinder');
		$this->deleteRecord($event->sender, C1ExternalFinder::OBJECT_TYPE_CATEGORY);
	}

	/**
	 * @param $event
	 */
	public function deleteExternalAttribute($event)
	{
		Yii::import('application.modules.accounting1c.components.C1ExternalFinder');
		$this->deleteRecord($event->sender, C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE);
	}

	/**
	 * @param $event
	 */
	public function deleteExternalProduct($event)
	{
		Yii::import('application.modules.accounting1c.components.C1ExternalFinder');
		$this->deleteRecord($event->sender, C1ExternalFinder::OBJECT_TYPE_PRODUCT);
	}

	/**
	 * @param CActiveRecord $model
	 * @param $type
	 */
	protected function deleteRecord(CActiveRecord $model, $type)
	{
		Yii::app()->db->createCommand()->delete('accounting1c', 'object_id=:object_id AND object_type=:object_type',array(
			':object_id'  =>$model->getPrimaryKey(),
			':object_type'=>$type,
		));
	}
}


Yii::import('application.modules.store.models.StoreManufacturer');
Yii::import('application.modules.store.models.StoreCategory');

/**
 * Global events
 */
class DiscountsModuleEvents
{

	/**
	 * @return array of events to subscribe module
	 */
	public function getEvents()
	{
		return array(
			array('StoreManufacturer', 'onAfterDelete', array($this, 'deleteManufacturer')),
			array('StoreCategory', 'onAfterDelete', array($this, 'deleteCategory')),
		);
	}

	/**
	 * @param $event CEvent
	 */
	public function deleteManufacturer($event)
	{
		Yii::app()->db->createCommand()->delete('DiscountManufacturer', 'manufacturer_id=:id', array(':id'=>$event->sender->getPrimaryKey()));
	}

	/**
	 * @param $event CEvent
	 */
	public function deleteCategory($event)
	{
		Yii::app()->db->createCommand()->delete('DiscountCategory', 'category_id=:id', array(':id'=>$event->sender->getPrimaryKey()));
	}

}


Yii::import('application.modules.logger.models.ActionLog');

/**
 * LoggerModule events
 */
class LoggerModuleEvents
{

	/**
	 * @var array|null
	 */
	public $logClasses=null;

	/**
	 * @var array
	 */
	public $events = array('onBeforeSave', 'onAfterDelete');

	/**
	 * @var array cache saved objects to prevent double logging
	 */
	protected $processedObjects = array();

	/**
	 * Set classes to log
	 */
	public function __construct()
	{
		if($this->logClasses===null)
			$this->logClasses = ActionLog::getLogClasses();
	}

	/**
	 * @return array
	 */
	public function getEvents()
	{
		$result = array();

		if(!Yii::app()->user->isGuest)
		{
			foreach($this->logClasses as $class=>$data)
			{
				foreach($this->events as $event)
				{
					$method = 'processEvent';
					if($event==='onAfterDelete')
						$method='processDeleteEvent';

					array_push($result, array($class, $event, array($this, $method)));
				}
			}
		}
		return $result;
	}

	/**
	 * @param $event CEvent
	 * @return boolean
	 */
	public function processEvent($event)
	{
		if(Yii::app()->controller instanceof SAdminController === false)
			return true;

		$event->sender->isNewRecord ? $eventName=ActionLog::ACTION_CREATE:$eventName=ActionLog::ACTION_UPDATE;
		$this->saveEvent($event->sender, $eventName);

		return true;
	}

	/**
	 * @param $event CEvent
	 * @return boolean
	 */
	public function processDeleteEvent($event)
	{
		if(Yii::app()->controller instanceof SAdminController === false)
			return true;

		$this->saveEvent($event->sender, ActionLog::ACTION_DELETE);

		return true;
	}

	/**
	 * @param $model
	 * @param $event string event name. e.g: create/update/delete
	 */
	protected function saveEvent($model, $event)
	{
		if(in_array(spl_object_hash($model),$this->processedObjects))
			return;

		$className = get_class($model);
		$modelTitleAttr = $this->logClasses[$className]['title_attribute'];

		$log = new ActionLog;
		$log->username = Yii::app()->user->username;
		$log->event = $event;
		$log->model_name = $className;
		$log->model_title = $model->$modelTitleAttr;
		$log->datetime = date('Y-m-d H:i:s');
		$log->save();

		array_push($this->processedObjects, spl_object_hash($model));
	}
}


/**
 * Global events
 */
class PagesModuleEvents
{
    public function getEvents()
    {
        return array(
            array('SSystemLanguage', 'onAfterSave', array($this, 'insertTranslations')),
            array('SSystemLanguage', 'onAfterDelete', array($this, 'deleteTranslations')),
        );
    }

    /**
     * `On after create new language` event.
     * Create default translation for each page object.
     * @param $event
     */
    public function insertTranslations($event)
    {
        Yii::import('application.modules.pages.models.Page');

        if(!$event->sender->isNewRecord)
            return;

        // Find all pages on default language and
        // make copy on new lang.
        $pages = Page::model()
            ->language(Yii::app()->languageManager->default->id)
            ->findAll();

        if($pages)
        {
            foreach($pages as $p)
                $p->createTranslation($event->sender->getPrimaryKey());
        }

        // Categories
        $categories = PageCategory::model()
            ->language(Yii::app()->languageManager->default->id)
            ->findAll();

        if($categories)
        {
            foreach($categories as $c)
                $c->createTranslation($event->sender->getPrimaryKey());
        }
    }

    /**
     * Delete page translations after deleting language
     * @param $event
     */
    public function deleteTranslations($event)
    {
        // Delete page translations
        Yii::import('application.modules.pages.models.PageTranslate');

        $pages = PageTranslate::model()->findAll(array(
            'condition'=>'language_id=:lang_id',
            'params'=>array(':lang_id'=>$event->sender->getPrimaryKey())
        ));

        if($pages)
        {
            foreach($pages as $p)
                $p->delete();
        }

        // Delete categories translations
        Yii::import('application.modules.pages.models.PageCategoryTranslate');

        $categories = PageCategoryTranslate::model()->findAll(array(
            'condition'=>'language_id=:lang_id',
            'params'=>array(':lang_id'=>$event->sender->getPrimaryKey())
        ));

        if($categories)
        {
            foreach($categories as $c)
                $c->delete();
        }
    }

}


Yii::import('application.modules.store.models.*');

/**
 * Global events
 */
class StoreModuleEvents
{

	/**
	 * @var array
	 */
	public $classes = array(
		'StoreProduct',
		'StoreCategory',
		'StoreAttribute',
		'StoreManufacturer',
		'StoreDeliveryMethod',
	);

	/**
	 * @return array of events to subscribe module
	 */
	public function getEvents()
	{
		return array(
			array('SSystemLanguage', 'onAfterSave', array($this, 'insertTranslations')),
			array('SSystemLanguage', 'onAfterDelete', array($this, 'deleteTranslations')),
		);
	}

	/**
	 * `On after create new language` event.
	 * Create default translation for each product object.
	 * @param $event
	 */
	public function insertTranslations($event)
	{
		if($event->sender->isNewRecord)
		{
			foreach($this->classes as $class)
				$this->_insert($class, $event);
		}
	}

	/**
	 * @param $class
	 * @param $event
	 */
	public function _insert($class, $event)
	{
		$objects = $class::model()
			->language(Yii::app()->languageManager->default->id)
			->findAll();

		if($objects)
		{
			foreach($objects as $obj)
				$obj->createTranslation($event->sender->getPrimaryKey());
		}
	}

	/**
	 * Delete product translations after deleting language
	 * @param $event
	 */
	public function deleteTranslations($event)
	{
		foreach($this->classes as $class)
			$this->_delete($class.'Translate', $event);
	}

	/**
	 * @param $class
	 * @param $event
	 */
	private function _delete($class, $event)
	{
		$objects = $class::model()->findAll(array(
			'condition'=>'language_id=:lang_id',
			'params'=>array(':lang_id'=>$event->sender->getPrimaryKey())
		));

		if($objects)
		{
			foreach($objects as $obj)
				$obj->delete();
		}
	}

}
