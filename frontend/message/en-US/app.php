<?php
/**
 * Use by Sys::mId('[name this filename', 'name key in this file's array', [array $params]): string [value array by key]
 */
return [
    'invalidErrorFormRequire'       => 'This field is required',
    'invalidFormfieldEmail'         => 'Invalid format',
    'invalidFormfieldLengthMax'     => 'The maximum length must not exceed {max, number} {max, plural, 1{symbol}other{symbols}}',
    'invalidFormfieldLengthMin'     => 'Minimum length {min, number} {min, plural, one{symbol}other{symbols}}',
    'invalidFormfieldIn'            => 'Invalid value',
    'invalidFormfieldFileMimeType'  => 'Invalid file format {filename}, must be: {availableMimeTypes}',
    'invalidFormfieldFileExtension' => 'Invalid file extension {filename}, must be: {availableExt}',
    'invalidFormfieldExist'         => 'Value already taken',
    'invalidFormfieldCompare'       => 'Value does not match',
    'invalidFormfieldDate'          => 'Invalid date',
    'invalidFormfieldDateFormat'    => 'Invalid format',
    'invalidFormfieldFileMaxFileSize'    => 'File {filename} the size {currentFileSizeFormat} {currentFileSize, plural, 1{byte}other{bytes}} exceeds the allowable limit of {maxFileSizeFormat} {maxFileSize, plural, 1{byte}other{bytes}}',

    'errorValidationFormRegistrationEmailExist'               => 'This email is already in use',
    'errorValidationFormRegistrationRepeatPasswordNotCompare' => 'Doesn\'t match password',

    'notFoundUser' => 'User is not found',

    'messageSuccessUpdateProfile'     => 'Profile updated successfully',
    'messageErrorUpdateProfile'       => 'An error occurred while updating',
    'errorFormProfileInValidPassword' => 'Incorrect password',
    'messageErrorDeleteProfile'       => 'Error deleting profile',
    'messageSuccessDeleteProfile'     => 'Profile deleted successfully',

    'errorFormLoginInValidEmail'    => 'Invalid email',
    'errorFormLoginInValidPassword' => 'Invalid password',

    'errorActionLoginFailedLogin' => 'Login Failed',

    'messageErrorCreateUser'     => 'Failed to register',
    'messageSuccessRegistration' => 'You are successfully registered',

    'nameLanguage-ru-RU'  => 'Русский',
    'nameLanguage-en-US'  => 'English',

    // FRONTEND
    // General
    'nameSite'            => 'TestTask',
    'registration'        => 'Sign up',
    'login'               => 'Sign in',
    'logout'              => 'Logout',
    'profile'             => 'Profile',
    'deleteProfile'       => 'Delete profile',
    'placeholderEmail'    => 'Email',
    'placeholderPassword' => 'Password',

    'inputNoteDescription' => 'Max 1000 symbols',
    'inputNoteAvatar' => 'Formats allowed: jpg, jpeg, png, bmp <br> Maximum size 2 MB',
    'inputNotePassword' => 'Length from 3 to 20 characters',

    'countryRussia'               => 'Russia',
    'countryUSA'                  => 'USA',
    'countryItaly'                => 'Italy',
    'countryFrance'               => 'France',
    'countryGermany'              => 'Germany',
    'countryUnitedKingdom'        => 'United Kingdom',
    'countryNorway'               => 'Norway',
    'countryChina'                => 'China',
    'countryJapan'                => 'Japan',
    'countryIndia'                => 'India',
    'countryUAE'                  => 'UAE',

    // Profile forms
    'inputLabelName'              => 'Name',
    'inputLabelEmail'             => 'Email',
    'inputLabelCountry'           => 'Country',
    'inputLabelDateBirthday'      => 'Birthday',
    'inputLabelDescription'       => 'Biography',
    'inputLabelAvatar'            => 'Avatar',
    'inputLabelPassword'          => 'Password',
    'inputLabelRepeatPassword'    => 'Repeat password',

    // Index page
    'hellowGuest'                 => 'Hello',
    'hellowUsername'              => 'Hello {username}',
    'selectDo'                    => 'Choose action below',
    'editProfile'                 => 'Edit profile',

    // Login page
    'inputLabelRememberMe'        => 'Remember me',

    // Registration page
    'descRegistrationPage'        => 'Fill the fields for registration',

    // Profile form page
    'confirmDeleteAvatar'         => 'Are you sure you want to delete?',
    'buttonDeleteAvatar'          => 'Delete avatar',
    'inputLabelDateRegistration'  => 'Date registration',
    'inputLabelDateUpdateProfile' => 'Last date updated profile',
    'inputLabelID'                => 'ID',
    'confirmDeleteProfile'        => 'Are you sure you want to delete yourself?',
    'nameButtonSaveFormProfile'   => 'Save',
    'nameButtonDeleteProfile'     => 'Delete profile',
];
