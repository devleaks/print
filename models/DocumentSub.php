<?
namespace app\models;

class DocumentSub extends Document
{
    const TYPE = 'sub';

    public static function find()
    {
        return new DocumentQuery(get_called_class(), ['document_type' => self::TYPE]);
    }

    public function beforeSave($insert)
    {
        $this->type = self::TYPE;
        return parent::beforeSave($insert);
    }
}