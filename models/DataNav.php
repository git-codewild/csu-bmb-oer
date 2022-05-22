<?php

namespace codewild\csubmboer\models;

use codewild\csubmboer\core\exception\DbException;

class DataNav extends \codewild\csubmboer\core\db\BaseDbModel
{

    static public function tableName(): string
    {
        return 'data_navs';
    }

    static public function primaryKey(): string|array
    {
        return ['dataFileId', 'scriptId'];
    }

    public string $dataFileId = DataFile::DEFAULT_UUID;
    public string $scriptId = '';
    public int $n = 0;

    public DataFile|bool $file;

    public function __construct(?string $dataFileId = null)
    {
        if (!is_null($dataFileId)){
            $this->dataFileId = $dataFileId;
        }
        $this->file = DataFile::findOne(['id' => $this->dataFileId]);
    }

    public static function attributes(): array
    {
        return ['dataFileId', 'scriptId', 'n'];
    }

    public function delete()
    {
        $shared = DataNav::findMany(['dataFileId' => $this->dataFileId]);

        if (count($shared) === 1 && $this->dataFileId !== DataFile::DEFAULT_UUID) {
            try {
                $this->file->delete();
            }
            catch (\PDOException $e){
                throw new DbException($e->getMessage());
            }
        }
        $siblings = DataNav::findMany(['scriptId' => $this->scriptId]);
        foreach ($siblings as $sibling){
            if ($sibling->n > $this->n) {
                $sibling->n -= 1;
                $sibling->update(['n']);
            }
        }
        return parent::delete();
    }

    public static function create($dataFileId, $scriptId){
        $nav = new self($dataFileId);
        $nav->scriptId = $scriptId;
        $existingNavs = self::findMany(['scriptId' => $scriptId]);
        $nav->n = count($existingNavs) + 1;
        return $nav->save();
    }


}
