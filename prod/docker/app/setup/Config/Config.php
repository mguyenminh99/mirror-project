<?php

class Config {

    public $DB_HOST;
    public $DB_NAME;
    public $DB_USER;
    public $DB_PASS;
    public $HOST_NAME;
    public $ADMIN_FIRSTNAME;
    public $ADMIN_LASTNAME;
    public $ADMIN_MAIL;
    public $ADMIN_USER;
    public $ADMIN_PASS;
    public $ADMIN_PAGE_SLUG;
    public $PROJECT_ROOT;

    function __construct() {
        $this->DB_HOST = getenv('DB_HOST');
        $this->DB_NAME = getenv('DB_NAME');
        $this->DB_USER = getenv('DB_USER');
        $this->DB_PASS = getenv('DB_PASS');
        $this->HOST_NAME = getenv('HOST_NAME');
        $this->ADMIN_FIRSTNAME = getenv('ADMIN_FIRSTNAME');
        $this->ADMIN_LASTNAME = getenv('ADMIN_LASTNAME');
        $this->ADMIN_MAIL = getenv('ADMIN_MAIL');
        $this->ADMIN_USER = getenv('ADMIN_USER');
        $this->ADMIN_PASS = getenv('ADMIN_PASS');
        $this->ADMIN_PAGE_SLUG = getenv('ADMIN_PAGE_SLUG');
        $this->PROJECT_ROOT = getenv('PROJECT_ROOT');
    }
}
