<?php
namespace MiniGameApp;

interface ApplicationUser {

    /**
     * @return string|int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();
}