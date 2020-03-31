<?php
/**
 * Use by Sys::mId('[name this filename', 'name key in this file's array', [array $params]): string [value array by key]
 */
return [
    'invalidErrorFormRequire'       => 'Это поле обязательно',
    'invalidFormfieldEmail'         => 'Неверный формат',
    'invalidFormfieldLengthMax'     => 'Максимальная длина не должна превышать {max, number} {max, plural, one{символ}few{символа}other{символов}}',
    'invalidFormfieldLengthMin'     => 'Минимальная длина {min, number} {min, plural, one{символ}few{символа}other{символов}}',
    'invalidFormfieldIn'            => 'Неверное значение',
    'invalidFormfieldFileMimeType'  => 'Недопустимый формат файла {filename}, должен быть: {availableMimeTypes}',
    'invalidFormfieldFileExtension' => 'Недопустимое расширение файла {filename}, должен быть: {availableExt}',
    'invalidFormfieldExist'         => 'Значение уже занято',
    'invalidFormfieldCompare'       => 'Значение не совпадает',
    'invalidFormfieldDate'          => 'Неверная дата',
    'invalidFormfieldDateFormat'    => 'Неверный формат',
    'invalidFormfieldFileMaxFileSize'    => 'Файл {filename} размером {currentFileSizeFormat} {currentFileSize, plural, one{байт}few{байта}other{байт}} первышает допустимый предел в {maxFileSizeFormat} {maxFileSize, plural, one{байт}few{байта}other{байт}}',

    'errorValidationFormRegistrationEmailExist'               => 'Этот email уже используется',
    'errorValidationFormRegistrationRepeatPasswordNotCompare' => 'Не совпадает с паролем',

    'notFoundUser' => 'Пользователь не найден',

    'nameButtonDeleteProfile'         => 'Удалить профиль',
    'messageSuccessUpdateProfile'     => 'Профиль успешно обновлен',
    'messageErrorUpdateProfile'       => 'При обновлении произошла ошибка',
    'errorFormProfileInValidPassword' => 'Неверный пароль',
    'messageErrorDeleteProfile'       => 'При удалении профиля произошла ошибка',
    'messageSuccessDeleteProfile'     => 'Профиль успешно удален',

    'errorFormLoginInValidEmail'    => 'Неверный email',
    'errorFormLoginInValidPassword' => 'Неверный пароль',

    'errorActionLoginFailedLogin' => 'Не удалось произвести вход',

    'messageErrorCreateUser'     => 'Не удалось провести регистрацию',
    'messageSuccessRegistration' => 'Вы успешно зарегистрированы',

    'nameLanguage-ru-RU'  => 'Русский',
    'nameLanguage-en-US'  => 'English',

    // FRONTEND
    // General
    'nameSite'            => 'TestTask',
    'registration'        => 'Регистрация',
    'login'               => 'Вход',
    'logout'              => 'Выход',
    'profile'             => 'Профиль',
    'deleteProfile'       => 'Удалить профиль',
    'placeholderEmail'    => 'Электронная почта',
    'placeholderPassword' => 'Пароль',

    'inputNoteDescription' => 'Максимум 1000 символов',
    'inputNoteAvatar' => 'Разрешены форматы: jpg, jpeg, png, bmp <br> Максимальный размер 2 Мб',
    'inputNotePassword' => 'Длина от 3 до 20 символов',

    'countryRussia'               => 'Россия',
    'countryUSA'                  => 'США',
    'countryItaly'                => 'Италия',
    'countryFrance'               => 'Франция',
    'countryGermany'              => 'Германия',
    'countryUnitedKingdom'        => 'Великобритания',
    'countryNorway'               => 'Норвегия',
    'countryChina'                => 'Китай',
    'countryJapan'                => 'Япония',
    'countryIndia'                => 'Индия',
    'countryUAE'                  => 'ОАЭ',

    // Profile forms
    'inputLabelName'              => 'Имя',
    'inputLabelEmail'             => 'Электронная почта',
    'inputLabelCountry'           => 'Страна',
    'inputLabelDateBirthday'      => 'День рождения',
    'inputLabelDescription'       => 'Биография',
    'inputLabelAvatar'            => 'Аватар',
    'inputLabelPassword'          => 'Пароль',
    'inputLabelRepeatPassword'    => 'Повторите пароль',

    // Index page
    'hellowGuest'                 => 'Добро пожаловать',
    'hellowUsername'              => 'Добро пожаловать {username}',
    'selectDo'                    => 'Выберите действие ниже',
    'editProfile'                 => 'Редактировать профиль',

    // Login page
    'inputLabelRememberMe'        => 'Запомнить меня',

    // Registration page
    'descRegistrationPage'        => 'Заполните поля для регистрации',

    // Profile form page
    'confirmDeleteAvatar'         => 'Вы уверены что хотите удалить?',
    'buttonDeleteAvatar'          => 'Удалить аватар',
    'inputLabelDateRegistration'  => 'Дата регистрации',
    'inputLabelDateUpdateProfile' => 'Дата последнего обновления профиля',
    'inputLabelID'                => 'ID',
    'confirmDeleteProfile'        => 'Вы уверены что хотите удалить себя?',
    'nameButtonSaveFormProfile'   => 'Сохранить',
];
