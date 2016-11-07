<?php

/*
    google user base migration class
    to determine if db drive is supported or not
*/

namespace vip9008\googleuser\migrations;

use Yii;

class Migration extends \yii\db\Migration
{
    protected $tableOptions;

    public function init()
    {
        parent::init();

        switch (Yii::$app->db->driverName) {
            case 'mysql':
                $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
                break;
            case 'pgsql':
                $this->tableOptions = null;
                break;
            default:
                throw new \RuntimeException('Your database is not supported!');
        }
    }
}
