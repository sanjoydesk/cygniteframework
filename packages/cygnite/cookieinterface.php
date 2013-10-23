<?php
namespace Cygnite;

interface CookieInterface
{
    public function get($cookie);

    public function save();

    public function destroy($cookie);

    public function has($cookie);
}