<?php

namespace frontend\controller;

use Exception;
use ReflectionException;
use Throwable;
use Sys;
use frontend\form\FormLogin;
use frontend\form\FormProfile;
use frontend\form\FormRegistration;
use frontend\model\User;
use system\application\WebUser;
use system\controller\Controller;
use system\exception\HttpException404;

/**
 * {@inheritdoc}
 * Class DefaultController
 *
 * @package frontend
 */
class DefaultController extends Controller
{
    /**
     * @throws Throwable
     * @return string
     */
    public function actionIndex(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        $isGuest = $webUser->isGuest();
        $username = '';

        if (!$isGuest && !is_null($webUser->getUser()))
            $username = $webUser->getUser()->name;

        return $this->render('index', ['isGuest' => $isGuest, 'username' => $username]);
    }

    /**
     * @throws Throwable
     * @return string
     */
    public function actionLogin(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if (!$webUser->isGuest())
            return $this->redirect('/');

        $form = new FormLogin();
        if (!empty($_POST) && $webUser->isValidCsrf()) {
            $form->load($_POST);
            if ($form->validate()) {
                /** @var User $user */
                $user = $this->getApp()->getUser($form->email);
                if (!is_null($user) && $user->verifyPassword($form->password)) {
                    if ($this->getApp()->login($user, $form->remember ? null : 0)) {
                        return $this->redirect('/profile');
                    } else {
                        $form->addError('email', Sys::mId('app', 'errorActionLoginFailedLogin'));
                    }
                } else {
                    $form->addError('email', Sys::mId('app', 'errorFormLoginInValidEmail'));
                    $form->addError('password', Sys::mId('app', 'errorFormLoginInValidPassword'));
                }
            }
        }

        $form->password = null;

        return $this->render('login', ['form' => $form]);
    }

    /**
     * @throws Throwable
     * @return string
     */
    public function actionRegistration(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if (!$webUser->isGuest())
            return $this->redirect('/index');

        $form = new FormRegistration();
        if (!empty($_POST) && $webUser->isValidCsrf()) {
            $form->load($_POST);
            if ($form->validate()) {
                $user = new User();

                $user->loadAttributes($form);
                $user->setPassword($form->password);
                if (isset($form->avatar->files[0]))
                    $user->setAvatar($form->avatar->files[0]);

                if ($this->getApp()->addUser($user)) {
                    $webUser->addFlash(Sys::mId('app', 'messageSuccessRegistration'), 'messageSuccessRegistration', 'successes');
                    return $this->redirect('/profile');
                } else {
                    $webUser->addFlash(Sys::mId('app', 'messageErrorCreateUser'), 'messageErrorCreateUser', 'errors');
                }
            }
        }
        return $this->render('registration', ['form' => $form, 'countries' => $this->getApp()->getCountries()]);
    }

    /**
     * @throws HttpException404
     * @throws Throwable
     * @throws ReflectionException
     * @return string
     */
    public function actionProfile(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if ($webUser->isGuest())
            $this->redirect('/login');

        $form = new FormProfile();

        /** @var User $user */
        $user = $webUser->getUser();

        if (is_null($user))
            throw new HttpException404(Sys::mId('app', 'notFoundUser'));

        if (!empty($_POST) && $webUser->isValidCsrf()) {
            $form->load($_POST);
            if ($form->validate()) {
                if ($user->verifyPassword($form->password)) {
                    $user->loadAttributes($form);
                    if (isset($form->avatar->files[0]))
                        $user->setAvatar($form->avatar->files[0]);

                    if ($user->save()) {
                        $form->flushValidate();
                        $webUser->addFlash(Sys::mId('app', 'messageSuccessUpdateProfile'), 'messageSuccessUpdateProfile', 'successes');
                        return $this->redirect('/profile');
                    } else {
                        $webUser->addFlash(Sys::mId('app', 'messageErrorUpdateProfile'), 'messageErrorUpdateProfile', 'errors');
                    }
                } else {
                    $form->addError('password', Sys::mId('app', 'errorFormProfileInValidPassword'));
                }
            }
        } else {
            $form->loadAttributes($user);
        }

        $form->password = null;

        return $this->render('profile', ['user' => $user, 'form' => $form, 'countries' => $this->getApp()->getCountries()]);
    }

    /**
     * @throws Exception
     * @return string
     */
    public function actionLogout(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if ($webUser->isGuest())
            return $this->redirect('/');

        if ($webUser->isValidCsrf() &&
            $this->getApp()->getRequest()->isPost() &&
            $webUser->logout()
        )
            return $this->redirect('/');

        return $this->redirect('/profile');
    }

    /**
     * @throws Exception
     * @return string
     */
    public function actionDeleteAvatar(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if ($webUser->isGuest())
            return $this->redirect('/index');

        $user = $webUser->getUser();

        if (is_null($user))
            return $this->redirect('/profile');

        if ($this->getApp()->getRequest()->isPost() &&
            $webUser->isValidCsrf() &&
            $user->deleteAvatar() &&
            $user->save()
        ) {
            $webUser->addFlash(Sys::mId('app', 'messageSuccessUpdateProfile'), 'messageSuccessUpdateProfile', 'successes');
            return $this->redirect('/profile');
        } else {
            $webUser->addFlash(Sys::mId('app', 'messageErrorUpdateProfile'), 'messageErrorUpdateProfile', 'errors');
        }

        return $this->redirect('/profile');
    }

    /**
     * @throws Exception
     * @return bool
     */
    public function actionDeleteProfile()
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if ($webUser->isGuest())
            return $this->redirect('/login');

        $user = $webUser->getUser();

        if (is_null($user))
            return $this->redirect('/profile');

        if ($webUser->isValidCsrf() &&
            $this->getApp()->getRequest()->isPost() &&
            $user->delete()
        ) {
            $webUser->logout();
            $webUser->addFlash(Sys::mId('app', 'messageSuccessDeleteProfile'), 'messageSuccessDeleteProfile', 'successes');
            return $this->redirect('/index');
        }

        $webUser->addFlash(Sys::mId('app', 'messageErrorDeleteProfile'), 'messageErrorDeleteProfile', 'errors');

        return $this->redirect('/index');
    }
}