<?php


namespace system\web;


/**
 * Class UploadFile
 * Обертка файлов из _FILES загруженных через форму HTML
 *
 * @package system\web
 */
class UploadFile
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $tmpName;

    /**
     * @var int
     */
    public int $error;

    /**
     * @var int
     */
    public int $size;

    /**
     * UploadImage constructor.
     * @param $name
     * @param $type
     * @param $tmpName
     * @param $error
     * @param $size
     */
    public function __construct($name, $type, $tmpName, $error, $size)
    {
        $this->name = $name;
        $this->type = $type;
        $this->tmpName = $tmpName;
        $this->error = $error;
        $this->size = $size;
    }

    /**
     * Сохраняет файл в $path
     *
     * @param string $path
     * @return bool
     */
    public function save(string $path): bool
    {
        if ($this->error == UPLOAD_ERR_OK) {
            return move_uploaded_file($this->tmpName, $path);
        }
        return false;
    }

    /**
     * Возвращает расширение файла
     *
     * @return string
     */
    public function getExtension(): string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION) ?? '';
    }
}