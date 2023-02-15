<?php
namespace Mpx\MpMassUpload\Model;

class Import extends AbstractMassUpload
{
    /**
     * Data To Map Japan Key
     *
     * @var string[]
     */
    protected $mapJp = [
        "商品カテゴリー" => "category",
        "商品名" => "name",
        "概要" => "description",
        "キャッチコピー" => "short_description",
        "ステータス" => "status",
        "商品番号" => "sku",
        "単価" => "price",
        "特別価格" => "special_price",
        "特別価格開始日" => "special_from_date",
        "特別価格終了日" => "special_to_date",
        "在庫数" => "stock",
        "在庫の有無" => "is_in_stock",
        "メタタイトル" => "meta_title",
        "メタキーワード" => "meta_keyword",
        "メタ概要" => "meta_description",
        "商品画像" => "images"
    ];

    /**
     * @inheritdoc
     */
    public function replaceJapArrKeyToSavingFormat($data)
    {
        foreach ($data['product'] as $key => $field) {
            foreach ($this->mapJp as $keyMap => $fieldJp) {
                if ($key == $keyMap) {
                    if (isset($data['product'][$keyMap])) {
                        $data['product'][$fieldJp] = $data['product'][$keyMap];
                        unset($data['product'][$keyMap]);
                    }
                }
            }
        }
        return $data;
    }
}
