<?php

Yii::import('application.modules.accounting1c.components.C1ExternalFinder');
Yii::import('application.modules.accounting1c.components.C1ProductImage');
Yii::import('application.modules.accounting1c.components.C1AbstractImport');
Yii::import('application.modules.store.models.StoreCategory');
Yii::import('application.modules.store.models.StoreProduct');
Yii::import('application.modules.store.models.StoreAttribute');
Yii::import('application.modules.store.models.StoreTypeAttribute');
Yii::import('application.modules.store.models.StoreAttributeOption');
Yii::import('ext.SlugHelper.SlugHelper');

/**
 * Imports products from XML file
 */
class C1ProductsImport extends C1AbstractImport
{

	/**
	 * ID of the StoreType model to apply to new attributes and products
	 */
	const DEFAULT_TYPE=1;

	/**
	 * @var string
	 */
	protected $xml;

	/**
	 * @var StoreCategory
	 */
	protected $_rootCategory;

	/**
	 * @static
	 * @param $mode
	 */
	public static function processRequest($mode)
	{
		$method='command'.ucfirst($mode);
		$import=new self;
		if(method_exists($import, $method))
			$import->$method();
	}

	public function __construct()
	{
		$this->checkTempDirectory();
	}

	public function checkTempDirectory()
	{
		$path = Yii::getPathOfAlias($this->tempDirectory);
		if(!file_exists($path))
			mkdir($path);
	}

	/**
	 * Save file
	 */
	public function commandFile()
	{
		$fileName=Yii::app()->request->getQuery('filename');
		$result=file_put_contents($this->buildPathToTempFile($fileName), file_get_contents('php://input'));
		if($result!==false)
			echo "success\n";
	}

	/**
	 * Import
	 */
	public function commandImport()
	{
		$this->xml=$this->getXml(Yii::app()->request->getQuery('filename'));
		if(!$this->xml)
			return false;

		// Import categories
		if(isset($this->xml->{'Классификатор'}->{'Группы'}))
			$this->importCategories($this->xml->{'Классификатор'}->{'Группы'});

		// Import properties
		if(isset($this->xml->{'Классификатор'}->{'Свойства'}))
			$this->importProperties();

		// Import products
		if(isset($this->xml->{'Каталог'}->{'Товары'}))
			$this->importProducts();

		// Import prices
		if(isset($this->xml->{'ПакетПредложений'}->{'Предложения'}))
			$this->importPrices();

		echo "success\n";
	}

	/**
	 * Import catalog products
	 */
	public function importProducts()
	{
		foreach($this->xml->{'Каталог'}->{'Товары'}->{'Товар'} as $product)
		{
			$createExId = false;
			$model      = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_PRODUCT, $product->{'Ид'});

			if(!$model)
			{
				$model = new StoreProduct;
				$model->type_id   = self::DEFAULT_TYPE;
				$model->price     = 0;
				$model->is_active = 1;
				$createExId=true;
			}

			$model->name = $product->{'Наименование'};
			$model->sku  = $product->{'Артикул'};
			$model->save(false);

			// Create external id
			if($createExId===true)
				$this->createExternalId(C1ExternalFinder::OBJECT_TYPE_PRODUCT, $model->id, $product->{'Ид'});

			// Set category
			$categoryId = $this->loadCategoryByExternalId($product->{'Группы'}->{'Ид'});
			$model->setCategories(array($categoryId), $categoryId);

			// Set image
			$image = C1ProductImage::create($this->buildPathToTempFile($product->{'Картинка'}));
			if($image && !$model->mainImage)
				$model->addImage($image);

			// Process properties
			if(isset($product->{'ЗначенияСвойств'}->{'ЗначенияСвойства'}))
			{
				$attrsData=array();
				foreach($product->{'ЗначенияСвойств'}->{'ЗначенияСвойства'} as $attribute)
				{
					$attributeModel=C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE, $attribute->{'Ид'});
					if($attributeModel && $attribute->{'Значение'} != '')
					{
						$cr = new CDbCriteria;
						$cr->with = 'option_translate';
						$cr->compare('option_translate.value', $attribute->{'Значение'});
						$option = StoreAttributeOption::model()->find($cr);

						if(!$option)
							$option = $this->addOptionToAttribute($attributeModel->id, $attribute->{'Значение'});
						$attrsData[$attributeModel->name]=$option->id;
					}
				}

				if(!empty($attrsData))
				{
					$model->setEavAttributes($attrsData, true);
				}
			}
		}
	}

	/**
	 * Import catalog prices
	 */
	public function importPrices()
	{
		foreach($this->xml->{'ПакетПредложений'}->{'Предложения'}->{'Предложение'} as $offer)
		{
			$product=C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_PRODUCT, $offer->{'Ид'});

			if($product)
			{
				$product->price=$offer->{'Цены'}->{'Цена'}->{'ЦенаЗаЕдиницу'};
				$product->quantity=$offer->{'Количество'};
				$product->save(false);
			}
		}
	}

	/**
	 * @param $attribute_id
	 * @param $value
	 * @return StoreAttributeOption
	 */
	public function addOptionToAttribute($attribute_id, $value)
	{
		// Add option
		$option = new StoreAttributeOption;
		$option->attribute_id = $attribute_id;
		$option->value = $value;
		$option->save();
		return $option;
	}

	/**
	 * Import product properties
	 */
	public function importProperties()
	{
		foreach($this->xml->{'Классификатор'}->{'Свойства'}->{'Свойство'} as $attribute)
		{
			$model = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE, $attribute->{'Ид'});

			if($attribute->{'ЭтоФильтр'}=='false')
				$useInFilter=false;
			else
				$useInFilter=true;

			if(!$model)
			{
				// Create new attribute
				$model = new StoreAttribute;
				$model->name  = SlugHelper::run($attribute->{'Наименование'});
				$model->name  = str_replace('-','_',$model->name);
				$model->title = $attribute->{'Наименование'};
				$model->type  = StoreAttribute::TYPE_DROPDOWN;
				$model->use_in_filter    = $useInFilter;
				$model->display_on_front = true;

				if($model->save())
				{
					// Add to type
					$typeAttribute = new StoreTypeAttribute;
					$typeAttribute->type_id      = self::DEFAULT_TYPE;
					$typeAttribute->attribute_id = $model->id;
					$typeAttribute->save();

					$this->createExternalId(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE, $model->id, $attribute->{'Ид'});
				}
			}

			// Update attributes
			$model->name = SlugHelper::run($attribute->{'Наименование'});
			$model->use_in_filter = $useInFilter;
			$model->save();
		}
	}

	/**
	 * @param $data
	 * @param null|StoreCategory $parent
	 */
	public function importCategories($data, $parent=null)
	{
		foreach($data->{'Группа'} as $category)
		{
			// Find category by external id
			$model=C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_CATEGORY, $category->{'Ид'});

			if(!$model)
			{
				$model=new StoreCategory;
				$model->name=$category->{'Наименование'};
				$model->appendTo($this->getRootCategory());
				$this->createExternalId(C1ExternalFinder::OBJECT_TYPE_CATEGORY, $model->id, $category->{'Ид'});
			}

			if($parent===null)
				$model->moveAsLast($this->getRootCategory());
			else
				$model->moveAsLast($parent);

			$model->saveNode();

			// Process subcategories
			if(isset($category->{'Группы'}))
				$this->importCategories($category->{'Группы'}, $model);
		}
	}

	/**
	 * Parses xml file from temp dir.
	 *
	 * @param $xmlFileName
	 * @return bool|object
	 */
	public function getXml($xmlFileName)
	{
		$xmlFileName=str_replace('../','',$xmlFileName);
		$fullPath=Yii::getPathOfAlias($this->tempDirectory).DIRECTORY_SEPARATOR.$xmlFileName;
		if(file_exists($fullPath) && is_file($fullPath))
			return simplexml_load_file($fullPath);
		else
			return false;
	}

	/**
	 * @return StoreCategory
	 */
	public function getRootCategory()
	{
		if($this->_rootCategory)
			return $this->_rootCategory;
		$this->_rootCategory=StoreCategory::model()->findByPk(1);
		return $this->_rootCategory;
	}

	protected $_categoryCache = array();
	public function loadCategoryByExternalId($id)
	{
		$id = (string) $id;
		if(isset($this->_categoryCache[$id]))
			return $this->_categoryCache[$id];

		$this->_categoryCache[$id] = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_CATEGORY, $id, false);
		return $this->_categoryCache[$id];
	}

	/**
	 * @param $type
	 * @param $id
	 * @param $externalId
	 */
	public function createExternalId($type, $id, $externalId)
	{
		Yii::app()->db->createCommand()->insert('accounting1c', array(
			'object_type' => $type,
			'object_id'   => $id,
			'external_id' => $externalId
		));
	}

}
