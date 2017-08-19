<?php

namespace App;

use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService
{

    public function getId(ProviderUser $providerUser) {

        return $providerUser->getId();

    }

    public function getEmail(ProviderUser $providerUser) {

        return $providerUser->getEmail();

    }

    public function getAvatar(ProviderUser $providerUser) {

        return $providerUser->getAvatar();

    }

    public function getName(ProviderUser $providerUser) {

        return $providerUser->getName();

    }

}