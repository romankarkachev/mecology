<?php

namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class FthcdcImport extends Model
{
    /**
     * @var integer год, на который действуют загружаемые нормативы
     */
    public $year;

    /**
     * @var UploadedFile
     */
    public $importFile;

    public function rules()
    {
        return [
            ['year', 'required'],
            [['importFile'], 'file', 'skipOnEmpty' => false, 'extensions' => ['xls', 'xlsx'], 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'year' => 'Год',
            'importFile' => 'Файл',
        ];
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function upload($filename)
    {
        if ($this->validate()) {
            $upl_dir = \Yii::getAlias('@uploads');
            if (!file_exists($upl_dir) && !is_dir($upl_dir)) mkdir($upl_dir, 0755);

            $this->importFile->saveAs($filename);
            return true;
        } else {
            return false;
        }
    }
}
