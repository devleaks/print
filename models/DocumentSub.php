<?
namespace app\models;

class DocumentSub extends Document
{
    const TYPE = 'sub';

    public static function find()
    {
        return new DocumentQuery(get_called_class(), ['type' => self::TYPE]);
    }

    public function beforeSave($insert)
    {
        $this->document_type = self::TYPE;
        return parent::beforeSave($insert);
    }
}