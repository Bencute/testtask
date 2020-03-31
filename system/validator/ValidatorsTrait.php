<?php


namespace system\validator;


use Sys;
use system\exception\ValidatorException;
use system\web\UploadFile;

/**
 * Trait ValidatorsTrait
 * @package system\validator
 */
trait ValidatorsTrait
{
    /**
     * Массив сообщений об ошибка валидации для применения в js скрипте
     *
     * @var array
     */
    public array $validatorsErrorMessages = [
        'vRequire'  => ['invalidErrorFormRequire'],
        'vEmail'    => ['invalidFormfieldEmail'],
        'vLength'   => ['max' => 'invalidFormfieldLengthMax', 'min' => 'invalidFormfieldLengthMin'],
        'vIn'       => ['invalidFormfieldIn'],
        'vMimeType' => ['ext' => 'invalidFormfieldFileExtension', 'mime' => 'invalidFormfieldFileMimeType'],
        'vUnique'   => ['invalidFormfieldExist'],
        'vCompare'  => ['invalidFormfieldCompare'],
        'vDate'     => ['invalidFormfieldDateFormat'],
    ];

    /**
     * @param string $attribute
     * @param mixed $value
     * @param array $params
     * @return ValidatorResponse
     */
    public function vRequire(string $attribute, $value, array $params = []): ValidatorResponse
    {
        if (is_null($value) || $value == '')
            return new ValidatorResponse(false, 'invalidErrorFormRequire');

        return new ValidatorResponse(true);
    }

    /**
     * @param string $attribute
     * @param $value
     * @param array $params
     * @return ValidatorResponse
     */
    public function vEmail(string $attribute, $value, array $params = []): ValidatorResponse
    {
        if (!is_null($value) && $value != '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false)
            return new ValidatorResponse(false, 'invalidFormfieldEmail');

        return new ValidatorResponse(true);
    }

    /**
     * @param string $attribute
     * @param $value
     * @param array $params
     * @return ValidatorResponse
     */
    public function vLength(string $attribute, $value, array $params = []): ValidatorResponse
    {
        $maxLen = $params['max'] ?? null;
        $minLen = $params['min'] ?? null;

        if (!is_null($maxLen) || !is_null($minLen)) {
            $len = mb_strlen($value);
            if (!is_null($maxLen) && $len > $maxLen)
                return new ValidatorResponse(false, Sys::mId('app', 'invalidFormfieldLengthMax', ['max' => $maxLen]));
            if (!is_null($minLen) && $len < $minLen)
                return new ValidatorResponse(false, Sys::mId('app', 'invalidFormfieldLengthMin', ['min' => $minLen]));
        }

        return new ValidatorResponse(true);
    }

    /**
     * @param string $attribute
     * @param $value
     * @param array $params
     * @return ValidatorResponse
     */
    public function vIn(string $attribute, $value, array $params = []): ValidatorResponse
    {
        $values = $params['values'] ?? [];

        if (!in_array($value, $values))
            return new ValidatorResponse(false, Sys::mId('app', 'invalidFormfieldIn'));

        return new ValidatorResponse(true);
    }

    /**
     * @param string $attribute
     * @param UploadFile $file
     * @param array $params
     * @return ValidatorResponse
     */
    public function vMimeType(string $attribute, UploadFile $file, array $params = []): ValidatorResponse
    {
        $extensions = $params['extensions'] ?? [];
        $mimeTypes = $params['mimeTypes'] ?? [];

        $validExtension = false;
        $fileExtension = $file->getExtension();
        foreach ($extensions as $extension) {
            if (!is_null($fileExtension) && strcasecmp($extension, $fileExtension) === 0) {
                $validExtension = true;
                break;
            }
        }

        if (!empty($extensions) && !$validExtension) {
            return new ValidatorResponse(
                false,
                Sys::mId(
                    'app',
                    'invalidFormfieldFileExtension',
                    ['filename' => $file->name, 'availableExt' => implode(', ', $extensions)]
                )
            );
        }

        $validMimeType = false;
        $fileMimeType = mime_content_type($file->tmpName);
        foreach ($mimeTypes as $mimeType) {
            if (strpos($mimeType, '*')) {
                $pattern = $mimeType;
                $pattern = str_replace('*', '[-\w.+]+', $pattern);
                $pattern = str_replace('/', '\/', $pattern);
                $pattern = '/^' . $pattern . '$/';

                // TODO check return false if error preg_match
                if (preg_match($pattern, $fileMimeType)) {
                    $validMimeType = true;
                    break;
                }
            } else {
                if (strcasecmp($mimeType, $fileMimeType) === 0) {
                    $validMimeType = true;
                    break;
                }
            }
        }

        if (!empty($mimeTypes) && !$validMimeType) {
            return new ValidatorResponse(
                false,
                Sys::mId(
                    'app',
                    'invalidFormfieldFileMimeType',
                    ['filename' => $file->name, 'availableMimeTypes' => implode(', ', $mimeTypes)]
                )
            );
        }

        return new ValidatorResponse(true);
    }

    /**
     * @param string $attribute
     * @param UploadFile $file
     * @param array $params
     * @return ValidatorResponse
     */
    public function vFileSize(string $attribute, UploadFile $file, array $params = []): ValidatorResponse
    {
        $maxFileSize = $params['maxFileSize'] ?? null;
        $fileSize = $file->size;
        if (!is_null($maxFileSize) && $fileSize > $maxFileSize) {
            return new ValidatorResponse(
                false,
                Sys::mId(
                    'app',
                    'invalidFormfieldFileMaxFileSize',
                    ['filename' => $file->name,
                     'currentFileSizeFormat' => number_format($fileSize, 0, ',', ' '),
                     'currentFileSize' => $fileSize,
                     'maxFileSizeFormat' => number_format($maxFileSize, 0, ',', ' '),
                     'maxFileSize' => $maxFileSize,
                    ]
                )
            );
        }

        return new ValidatorResponse(true);
    }

    /**
     * @param string $attribute
     * @param $value
     * @param array $params
     * @throws ValidatorException
     * @return ValidatorResponse
     */
    public function vUnique(string $attribute, $value, array $params = []): ValidatorResponse
    {
        if (!isset($params['model']))
            throw new ValidatorException(__FUNCTION__ . ' is require a model parameter');

        $message = $params['message'] ?? 'invalidFormfieldExist';

        $model = $params['model'];
        $modelAttribute = $params['attribute'] ?? $attribute;

        if ($model::exist([$modelAttribute => $value]))
            return new ValidatorResponse(false, Sys::mId('app', $message));

        return new ValidatorResponse(true);
    }

    /**
     * @param string $attribute
     * @param $value
     * @param array $params
     * @return ValidatorResponse
     */
    public function vDate(string $attribute, $value, array $params = []): ValidatorResponse
    {
        $message = $params['message'] ?? 'invalidFormfieldDateFormat';

        if (!empty($value) && !preg_match('/^\d\d\d\d-\d\d-\d\d$/', $value))
            return new ValidatorResponse(false, Sys::mId('app', $message));

        $maxDate = $params['maxDate'] ?? null;
        if (!is_null($maxDate))
        {
            $maxDateTimestamp = strtotime($maxDate);
            $currentDateTimestamp = strtotime($value);
            if ($currentDateTimestamp > $maxDateTimestamp)
                return new ValidatorResponse(false, Sys::mId('app', 'invalidFormfieldDate'));
        }

        return new ValidatorResponse(true);
    }

    /**
     * @param string $attribute
     * @param $value
     * @param array $params
     * @throws ValidatorException
     * @return ValidatorResponse
     */
    public function vCompare(string $attribute, $value, array $params = []): ValidatorResponse
    {
        if (!isset($params['compareAttribute']))
            throw new ValidatorException(__FUNCTION__ . ' is require a compareAttribute parameter');

        $message = $params['message'] ?? 'invalidFormfieldCompare';

        $compareAttribute = $params['compareAttribute'];

        if ($value != $this->$compareAttribute)
            return new ValidatorResponse(false, Sys::mId('app', $message));

        return new ValidatorResponse(true);
    }
}