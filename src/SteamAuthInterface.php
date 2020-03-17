<?php

namespace LightAir\LumenAuthViaSteam;

/**
 * Interface SteamAuthInterface
 *
 * @package LightAir\LumenAuthViaSteam
 */
interface SteamAuthInterface
{
    public function redirect();

    public function validate();

    public function getAuthUrl();

    public function getSteamId();

    public function getUserInfo();
}